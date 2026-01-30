<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Pin;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Storage;

class DownloadService
{
    /**
     * Validate if a PIN can download a specific media
     */
    public function canDownload(string $pinCode, int $mediaId): array
    {
        $pin = Pin::where('pin_code', $pinCode)->first();

        if (!$pin) {
            return [
                'allowed' => false,
                'message' => 'Invalid PIN code',
                'pin' => null,
                'media' => null,
            ];
        }

        if (!$pin->canDownload()) {
            return [
                'allowed' => false,
                'message' => 'PIN has expired or reached download limit',
                'pin' => $pin,
                'media' => null,
            ];
        }

        $media = Media::find($mediaId);

        if (!$media) {
            return [
                'allowed' => false,
                'message' => 'Media not found',
                'pin' => $pin,
                'media' => null,
            ];
        }

        return [
            'allowed' => true,
            'message' => 'Download allowed',
            'pin' => $pin,
            'media' => $media,
        ];
    }

    /**
     * Record a download and update PIN usage
     */
    public function recordDownload(Pin $pin, Media $media, string $ipAddress, string $userAgent = null): DownloadLog
    {
        $log = DownloadLog::create([
            'pin_id' => $pin->id,
            'media_id' => $media->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'downloaded_at' => now(),
        ]);

        $pin->recordDownload();

        return $log;
    }

    /**
     * Get the file path for a media
     */
    public function getMediaFilePath(Media $media): string
    {
        return storage_path('app/media/' . $media->filename);
    }

    /**
     * Check if a media file exists
     */
    public function mediaFileExists(Media $media): bool
    {
        $path = $this->getMediaFilePath($media);
        return file_exists($path);
    }

    /**
     * Get download statistics for a PIN
     */
    public function getPinDownloadStats(Pin $pin): array
    {
        $downloads = $pin->downloads()->with('media')->get();

        return [
            'pin_code' => $pin->pin_code,
            'total_downloads' => count($downloads),
            'remaining_downloads' => $pin->getRemainingDownloads(),
            'downloads' => $downloads->map(function ($download) {
                return [
                    'media_title' => $download->media->title,
                    'media_type' => $download->media->type,
                    'downloaded_at' => $download->downloaded_at,
                    'ip_address' => $download->ip_address,
                ];
            }),
        ];
    }
}
