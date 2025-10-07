<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Gate;
use App\Models\Employee;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function handleTap(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'cardno' => 'required|string|max:50',
            'termno' => 'required|string',
            'IO'     => 'required|in:0,1',
            'name'   => 'nullable|string|max:255',
        ]);

        // 2. Tentukan Arah Masuk/Keluar (IN/OUT) di awal
        // DIPERBAIKI: Gunakan 'in' dan 'out' (huruf kecil) sesuai database
        $logType = ($validated['IO'] == 1) ? 'In' : 'Out';

        // 3. Verifikasi Gate
        $gate = Gate::where('termno', $validated['termno'])->first();
        if (!$gate) {
            return response()->json([
                "Status" => 0, "Date" => now()->format('d-m-Y H:i:s'),
                "Message" => "Akses ditolak: Terminal tidak terdaftar",
                "CardNo" => $validated['cardno'],
                "Direction" => $logType // Tambahkan Direction di respon error
            ]);
        }
        
        // 4. Cari Kartu di Database
        $card = Card::where('cardno', $validated['cardno'])->first();
        $employee = null;

        if (!$card) {
            // --- SKENARIO: KARTU BARU (REGISTRASI OTOMATIS) ---
            if ($request->has('name') && !empty($validated['name'])) {
                
                // Buat Karyawan baru
                $employee = Employee::create([
                    'employee_id' => null, // Biarkan NIP kosong sesuai permintaan
                    'name'        => $validated['name'],
                    'is_active'   => true,
                ]);

                // Buat Kartu baru dan hubungkan
                $card = Card::create([
                    'cardno'        => $validated['cardno'],
                    'type'          => 'employee',
                    'cardable_id'   => $employee->id,
                    'cardable_type' => Employee::class,
                    'is_active'     => true,
                ]);

            } else {
                // Jika kartu tidak ada DAN tidak ada nama, tolak akses
                return response()->json([
                    "Status" => 0, "Date" => now()->format('d-m-Y H:i:s'),
                    "Message" => "Akses ditolak: Kartu tidak terdaftar",
                    "CardNo" => $validated['cardno'],
                    "Direction" => $logType
                ]);
            }
        }
        
        // 5. Cek status kartu dan karyawan
        if (!$card->is_active) {
            return response()->json(["Status" => 0, "Date" => now()->format('d-m-Y H:i:s'), "Message" => "Akses ditolak: Kartu tidak aktif", "CardNo" => $card->cardno]);
        }

        $employee = $card->cardable;
        if ($card->type === 'employee' && $employee && !$employee->is_active) {
            return response()->json(["Status" => 0, "Date" => now()->format('d-m-Y H:i:s'), "Message" => "Akses ditolak: Karyawan tidak aktif", "CardNo" => $card->cardno, "FullName" => $employee->name]);
        }

        // 6. Simpan Log Aktivitas
        AccessLog::create([
            'gate_id'       => $gate->id,
            'card_id'       => $card->id,
            'userable_id'   => $employee->id ?? null,
            'userable_type' => $employee ? Employee::class : null,
            'tap_time'      => now(),
            'type'          => $logType,
            'status'        => 'granted',
        ]);
        
        // 7. Kirim Respon Sukses
        return response()->json([
            "Status"    => 1,
            "Date"      => now()->format('d-m-Y H:i:s'),
            "Message"   => "Akses diterima",
            "CardNo"    => $card->cardno,
            "FullName"  => $employee->name ?? 'Tamu',
            "Direction" => $logType
        ]);
    }
}