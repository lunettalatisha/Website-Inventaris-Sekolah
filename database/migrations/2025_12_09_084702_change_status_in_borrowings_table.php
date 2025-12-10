<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // pastikan nama tabel benar: 'borrowings'
        // ubah kolom status menjadi enum dengan opsi yang diinginkan
        DB::statement("ALTER TABLE `borrowings` MODIFY `status` ENUM('borrowed','returned','denda') NOT NULL DEFAULT 'borrowed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // rollback ke varchar agar bisa dikembalikan
        DB::statement("ALTER TABLE `borrowings` MODIFY `status` VARCHAR(255) NOT NULL DEFAULT 'borrowed'");
    }
};
