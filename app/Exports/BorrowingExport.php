<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BorrowingExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Borrowing::with(['user', 'item'])->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Peminjam', 'Barang', 'Jumlah', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status'
        ];
    }

    public function map($borrowing): array
    {
        return [
            ++$this->key,
            $borrowing->user->name ?? 'N/A',
            $borrowing->item->name ?? 'N/A',
            $borrowing->quantity,
            Carbon::parse($borrowing->borrow_date)->format('d-m-Y'),
            $borrowing->return_date ? Carbon::parse($borrowing->return_date)->format('d-m-Y') : 'N/A',
            $borrowing->status,
        ];
    }
}
