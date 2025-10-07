<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccessLogExport;

class AccessLogController extends Controller
{
    // Method index() yang sudah ada, biarkan saja
  public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = AccessLog::with(['gate', 'card', 'userable'])->latest('tap_time');

        if ($startDate && $endDate) {
            // Pastikan format tanggal benar dan tambahkan waktu
            $start = date('Y-m-d 00:00:00', strtotime($startDate));
            $end = date('Y-m-d 23:59:59', strtotime($endDate));
            $query->whereBetween('tap_time', [$start, $end]);
        }

        $accessLogs = $query->paginate(25)->appends($request->query());

        return view('access-logs.index', compact('accessLogs', 'startDate', 'endDate'));
    }

    /**
     * Menangani proses ekspor ke Excel.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $filename = 'laporan-log-pintu_' . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new AccessLogExport($startDate, $endDate), $filename);
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