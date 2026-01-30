<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Models\DownloadLog;
use App\Services\PinService;
use Illuminate\Http\Request;

class PinController extends Controller
{
    protected $pinService;

    public function __construct(PinService $pinService)
    {
        $this->pinService = $pinService;
    }

    /**
     * Show all pins
     */
    public function index()
    {
        $pins = Pin::withCount('downloads')->paginate(15);
        return view('admin.pins.index', compact('pins'));
    }

    /**
     * Show create single PIN form
     */
    public function create()
    {
        return view('admin.pins.create');
    }

    /**
     * Store single PIN
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'max_downloads' => 'required|integer|min:1|max:10',
        ]);

        $pin = $this->pinService->createPin($validated['max_downloads']);

        return redirect()->route('admin.pins.index')->with('success', 'PIN created: ' . $pin->pin_code);
    }

    /**
     * Show bulk PIN generation form
     */
    public function createBulk()
    {
        return view('admin.pins.create-bulk');
    }

    /**
     * Generate bulk PINs
     */
    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:1000',
            'max_downloads' => 'required|integer|min:1|max:10',
        ]);

        $pins = $this->pinService->createBulkPins(
            $validated['quantity'],
            $validated['max_downloads']
        );

        return redirect()->route('admin.pins.print-bulk', [
            'ids' => implode(',', $pins->pluck('id')->toArray())
        ])->with('success', count($pins) . ' PINs created');
    }

    /**
     * Show PIN details
     */
    public function show(Pin $pin)
    {
        $downloads = $pin->downloads()->with('media')->paginate(10);
        return view('admin.pins.show', compact('pin', 'downloads'));
    }

    /**
     * Revoke PIN
     */
    public function revoke(Pin $pin)
    {
        $pin->update(['status' => 'revoked']);
        return back()->with('success', 'PIN revoked successfully');
    }

    /**
     * Print single PIN
     */
    public function print(Pin $pin)
    {
        return view('admin.pins.print', compact('pin'));
    }

    /**
     * Print bulk PINs
     */
    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);
        $pins = Pin::whereIn('id', $ids)->get();
        return view('admin.pins.print-bulk', compact('pins'));
    }

    /**
     * Export PINs as CSV
     */
    public function export(Request $request)
    {
        $query = Pin::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pins = $query->get();

        $csv = "PIN Code,Status,Used Downloads,Max Downloads,Created At,Remaining\n";
        foreach ($pins as $pin) {
            $csv .= "{$pin->pin_code},{$pin->status},{$pin->used_downloads},{$pin->max_downloads},{$pin->created_at},{$pin->getRemainingDownloads()}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pins.csv"',
        ]);
    }
}
