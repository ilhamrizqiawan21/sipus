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
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete();
            $table->string('kode_inventaris', 40)->unique();
            $table->string('lokasi_rak', 100)->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam', 'diperbaiki', 'hilang', 'dihapus'])->default('tersedia');
            $table->date('tanggal_masuk')->nullable();
            $table->decimal('harga_perolehan', 14, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->index(['status', 'kondisi']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};
