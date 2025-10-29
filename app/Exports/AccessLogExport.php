<?php

namespace App\Exports;

use App\Models\AccessLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles; // <-- 1. Tambahkan use untuk styling
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // <-- Tambahkan use untuk lebar kolom otomatis
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // <-- Tambahkan use untuk Worksheet
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// <-- 2. Tambahkan interface WithStyles dan ShouldAutoSize
class AccessLogExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = AccessLog::with(['gate', 'card', 'userable'])->latest('tap_time');
        if ($this->startDate && $this->endDate) {
            $start = date('Y-m-d 00:00:00', strtotime($this->startDate));
            $end = date('Y-m-d 23:59:59', strtotime($this->endDate));
            $query->whereBetween('tap_time', [$start, $end]);
        }
        return $query;
    }

    public function headings(): array
    {
        return [
            'Tanggal & Waktu',
            'Nama',
            'NIP',
            'Nomor Kartu', // Kolom ke-4 (Indeks D)
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
        $cardNumber = $log->card->cardno ?? 'N/A';

        return [
            $log->tap_time->format('d-m-Y H:i:s'),
            $log->userable->name ?? 'Tamu',
            $log->userable->employee_id ?? '-',
            $cardNumber, // Sekarang hanya nomor kartu biasa
            $log->gate->name ?? 'N/A',
            ucfirst($log->type),
            ucfirst($log->card->type ?? 'unknown'),
        ];
    }

    /**
     * Memberitahu Excel format spesifik untuk kolom tertentu.
     */
    public function columnFormats(): array
    {
        return [
            // Kolom D (Nomor Kartu) akan diformat sebagai Teks
            'D' => NumberFormat::FORMAT_TEXT, 
            // Format kolom A sebagai Tanggal Waktu
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' hh:mm:ss',
        ];
    }

    /**
     * 3. Tambahkan method baru ini untuk styling
     * Method ini akan dipanggil oleh package untuk menerapkan gaya.
     */
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan baris terakhir yang berisi data
        $lastRow = $sheet->getHighestRow();

        // Gaya untuk Header (Baris 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Warna teks putih
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4E73DF'], // Warna latar biru SB Admin 2
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Gaya untuk Seluruh Tabel Data (A2 sampai G terakhir)
        $sheet->getStyle('A2:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E3E6F0'], // Warna border abu-abu
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Atur alignment untuk kolom tertentu jika perlu
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('F2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Mengatur agar semua kolom menyesuaikan lebar secara otomatis sudah dihandle
        // oleh interface ShouldAutoSize, tapi jika ingin manual:
        // $sheet->getColumnDimension('A')->setWidth(20);
        // $sheet->getColumnDimension('B')->setWidth(30);
        // ... dst
    }
}

