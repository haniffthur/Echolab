<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Card;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getData()
    {
        // --- Karyawan di Dalam ---
        $latestEmployeeLogs = AccessLog::select(DB::raw('MAX(id) as id'))
            ->where('userable_type', Employee::class)
            ->groupBy('userable_id', 'userable_type')
            ->pluck('id');

        // PERUBAHAN 1: Tambahkan 'gate' di with()
        $employeesInside = AccessLog::with(['userable', 'card', 'gate'])
            ->whereIn('id', $latestEmployeeLogs)->where('type', 'in')
            ->latest('tap_time')->get()->map(function ($log) {
                if (!$log->userable) return null;
                return [
                    'id'        => $log->userable->id,
                    'name'      => $log->userable->name,
                    'detail'    => 'Kartu: ' . ($log->card->cardno ?? 'N/A'),
                    'time_in'   => $log->tap_time->format('H:i'),
                    'tap_time_raw' => $log->tap_time->toIso8601String(),
                    'icon'      => 'fas fa-user',
                    'gate_name' => $log->gate->name ?? 'N/A' // <-- TAMBAHKAN INI
                ];
            })->filter();

        // --- Tamu di Dalam ---
        $guestCardIds = Card::where('type', 'guest')->pluck('id');
        $latestGuestLogs = AccessLog::select(DB::raw('MAX(id) as id'))
            ->whereIn('card_id', $guestCardIds)->groupBy('card_id')->pluck('id');

        // PERUBAHAN 2: Tambahkan 'gate' di with()
        $guestsInside = AccessLog::with(['card', 'gate'])
            ->whereIn('id', $latestGuestLogs)->where('type', 'in')
            ->latest('tap_time')->get()->map(function ($log) {
                return [
                    'id'        => $log->card->id,
                    'name'      => 'Tamu',
                    'detail'    => 'Kartu: ' . $log->card->cardno,
                    'time_in'   => $log->tap_time->format('H:i'),
                    'tap_time_raw' => $log->tap_time->toIso8601String(),
                    'icon'      => 'fas fa-user-tie',
                    'gate_name' => $log->gate->name ?? 'N/A' // <-- TAMBAHKAN INI
                ];
            });
            
        return response()->json([
            'employeeInsideCount' => $employeesInside->count(),
            'guestInsideCount'    => $guestsInside->count(),
            'totalInsideCount'    => $employeesInside->count() + $guestsInside->count(),
            'employeesInside'     => $employeesInside->values(),
            'guestsInside'        => $guestsInside->values(),
        ]);
    }
}