<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            // tambahkan kolom fine, tipe decimal untuk menyimpan jumlah denda
            if (!Schema::hasColumn('borrowings', 'fine')) {
                $table->decimal('fine', 10, 2)->default(0.00)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'fine')) {
                $table->dropColumn('fine');
            }
        });
    }
};
