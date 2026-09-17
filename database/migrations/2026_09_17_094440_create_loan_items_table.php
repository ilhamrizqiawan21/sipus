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
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('kondisi_saat_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable();
            $table->enum('status', ['dipinjam', 'kembali', 'hilang', 'rusak'])->default('dipinjam');
            $table->text('catatan')->nullable();
            $table->unique(['loan_id', 'book_copy_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_items');
    }
};
