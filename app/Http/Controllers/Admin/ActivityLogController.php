<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->filter($request->only(['search', 'modul', 'aksi', 'tanggal']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $daftarModul = ActivityLog::query()
            ->select('modul')
            ->distinct()
            ->orderBy('modul')
            ->pluck('modul');

        return view('pages.admin.log-aktivitas.index', compact('logs', 'daftarModul'));
    }

    public function bersihkan(): RedirectResponse
    {
        $terhapus = ActivityLog::where('created_at', '<', now()->subDays(30))->delete();

        ActivityLog::catat('Log Aktivitas', 'delete', "Membersihkan {$terhapus} riwayat log aktivitas yang lebih dari 30 hari.");

        return redirect()
            ->route('admin.log-aktivitas.index')
            ->with('success', "Berhasil membersihkan {$terhapus} log aktivitas yang berusia lebih dari 30 hari.");
    }
}