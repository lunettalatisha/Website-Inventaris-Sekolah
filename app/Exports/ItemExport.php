<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
//class laravel untuk manipulasi datetime
use Carbon\Carbon;

class ItemExport implements FromCollection, WithHeadings, WithMapping
{
    //membuat property untuk no urutan data
    private $key = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //memanggil data yg bakal di munculin di excel
        return Item::all();
    }

    //menentukan header data (th)
    public function headings(): array
    {
        return [
            'No','Nama Barang','Keterangan','Kategori','Jumlah','Gambar',
        ];
    }

    //menetukan isi data (td)
    public function map($item): array
    {
        return [
            //menambahkan sebanyak 1 setiap data dari $key = 0 diatas
            ++$this->key,
            $item->title,
            $item->description,
            $item->category->name ?? 'Elektronik', 'Buku', 'Alat Tulis', 'Alat Kebersihan',
            $item->quantity,
            //asset(): link buat liat gambar
            asset('storage/') . "/" . $item->gambar,
        ];
    }
}
