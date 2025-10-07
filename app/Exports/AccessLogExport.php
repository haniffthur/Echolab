<?php

namespace App\Exports;

use App\Models\AccessLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccessLogExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Menentukan query data yang akan diekspor.
     */
    public function query()
    {
        $query = AccessLog::with(['gate', 'card', 'userable'])->latest('tap_time');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tap_time', [$this->startDate, $this->endDate]);
        }

        return $query;
    }

    /**
     * Menentukan judul kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Tanggal & Waktu',
            'Nama',
            'NIP',
            'Nomor Kartu',
            'Gate',
            'Status',
            'Jenis Kartu',
        ];
    }

    /**
     * Memetakan data dari setiap baris log ke kolom Excel.
     */
    public function map($log): array
    {
        return [
            $log->tap_time->format('d-m-Y H:i:s'),
            $log->userable->name ?? 'Tamu',
            $log->userable->employee_id ?? '-',
            $log->card->cardno ?? 'N/A',
            $log->gate->name ?? 'N/A',
            ucfirst($log->type), // 'In' atau 'Out'
            ucfirst($log->card->type ?? 'unknown'), // 'Employee' atau 'Guest'
        ];
    }
}
