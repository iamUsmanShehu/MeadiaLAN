<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Pin;
use App\Services\DownloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    protected $downloadService;

    public function __construct(DownloadService $downloadService)
    {
        $this->downloadService = $downloadService;
    }

    /**
     * Show PIN verification page
     */
    public function showVerification(Media $medium)
    {
        return view('download.verify', compact('medium'));
    }

    /**
     * Verify PIN and initiate download
     */
    public function verify(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'pin' => 'required|string',
        ]);

        $pin = $validated['pin'];
        $validation = $this->downloadService->canDownload($pin, $medium->id);

        if (!$validation['allowed']) {
            return back()->withErrors(['pin' => $validation['message']]);
        }

        // Record download and deduct usage BEFORE the download loop starts
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        $this->downloadService->recordDownload(
            $validation['pin'],
            $validation['media'],
            $ipAddress,
            $userAgent
        );

        // Generate a Temporary Signed URL (valid for 24 hours)
        // This bypasses session expiration during long downloads
        $downloadUrl = URL::temporarySignedRoute(
            'download.stream',
            now()->addHours(24),
            ['medium' => $medium->id, 'version' => $request->version]
        );

        return redirect($downloadUrl);
    }

    /**
     * Bulletproof Streaming Method
     * Works outside standard session middleware
     */
    public function streamFile(Request $request, Media $medium)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Download link expired or invalid.');
        }

        $filePath = $this->downloadService->getMediaFilePath($medium);
        $fileName = $medium->filename;

        // Support for specific versions (resolutions)
        if ($request->version) {
            $version = \App\Models\MediaVersion::find($request->version);
            if ($version && $version->media_id == $medium->id) {
                $filePath = storage_path('app/media/' . $version->filename);
                $fileName = $version->resolution . '_' . $medium->filename;
            }
        }

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        // BinaryFileResponse handles Range requests (Resume support)
        // and is highly memory efficient for large local network files.
        $response = new BinaryFileResponse($filePath);
        
        // Force as attachment
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;
    }

    /**
     * Show PIN status
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string',
        ]);

        $pin = \App\Models\Pin::where('pin_code', $validated['pin'])->first();

        if (!$pin) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid PIN',
            ]);
        }

        return response()->json([
            'valid' => true,
            'status' => $pin->status,
            'remaining_downloads' => $pin->getRemainingDownloads(),
            'used_downloads' => $pin->used_downloads,
            'max_downloads' => $pin->max_downloads,
        ]);
    }
}
