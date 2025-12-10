<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('restores', function (Blueprint $table) {
    $table->id();
    $table->foreignId('borrowing_id')->constrained('borrowings');
    $table->date('actual_restore_date')->nullable();
    $table->enum('item_condition', ['good','broken'])->nullable();
    $table->enum('restore_status', ['on_time','late','damaged'])->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restores');
    }
};
