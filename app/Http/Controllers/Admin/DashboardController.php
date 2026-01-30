<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Models\Media;
use App\Models\Category;
use App\Models\DownloadLog;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $totalPins = Pin::count();
        $activePins = Pin::where('status', 'active')->count();
        $expiredPins = Pin::where('status', 'expired')->count();

        $totalMedia = Media::count();
        $totalDownloads = DownloadLog::count();
        $categories = Category::count();

        $recentDownloads = DownloadLog::with('pin', 'media')
            ->orderByDesc('downloaded_at')
            ->limit(10)
            ->get();

        $recentPins = Pin::orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPins',
            'activePins',
            'expiredPins',
            'totalMedia',
            'totalDownloads',
            'categories',
            'recentDownloads',
            'recentPins'
        ));
    }
}
