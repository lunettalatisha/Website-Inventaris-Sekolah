<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
//class laravel untuk manipulasi datetime
use Carbon\Carbon;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    //membuat property untuk no urutan data
    private $key = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //memanggil data yg bakal di munculin di excel
        return User::all();
    }

    //menentukan header data (th)
    public function headings(): array
    {
        return [
            'No','Nama','Email','Role','Tanggal Bergabung',
        ];
    }

    //menetukan isi data (td)
    public function map($user): array
    {
        return [
            //menambahkan sebanyak 1 setiap data dari $key = 0 diatas
            ++$this->key,
            $user->name,
            $user->email,
            $user->role,
            carbon::parse($user->created_at)->format("d-m-Y"),

        ];
    }
}
