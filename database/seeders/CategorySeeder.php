<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::updateOrCreate(
            ['category_name' => 'Elektronik'],
            ['category_name' => 'Elektronik']
        );

        \App\Models\Category::updateOrCreate(
            ['category_name' => 'Buku'],
            ['category_name' => 'Buku']
        );

        \App\Models\Category::updateOrCreate(
            ['category_name' => 'Alat Tulis'],
            ['category_name' => 'Alat Tulis']
        );

        \App\Models\Category::updateOrCreate(
            ['category_name' => 'Alat Kebersihan'],
            ['category_name' => 'Alat Kebersihan']
        );
    }
}
