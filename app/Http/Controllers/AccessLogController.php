<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    // Method index() yang sudah ada, biarkan saja
    public function index()
    {
        $accessLogs = AccessLog::with(['gate', 'card', 'userable'])
                                ->latest('tap_time')
                                ->paginate(15);

        return view('access-logs.index', compact('accessLogs'));
    }

    // METHOD BARU UNTUK API
    public function getApiData()
    {
        $logs = AccessLog::with(['gate', 'card', 'userable'])
                        ->latest('tap_time')
                        ->limit(20) // Ambil 20 log terbaru untuk ditampilkan
                        ->get()
                        ->map(function ($log) {
                            // Format data agar mudah digunakan oleh JavaScript
                            return [
                                'time' => $log->tap_time->format('d M Y, H:i:s'),
                                'user_name' => $log->userable->name ?? '<i class="text-muted">Tamu</i>',
                                'user_nip' => $log->userable->employee_id ?? '-',
                                'card_no' => $log->card->cardno ?? 'N/A',
                                'gate_name' => $log->gate->name ?? 'N/A',
                                'type' => $log->type,
                            ];
                        });

        return response()->json($logs);
    }
}