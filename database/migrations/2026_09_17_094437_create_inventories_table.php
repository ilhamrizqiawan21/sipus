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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('kode_inventaris', 40)->unique();
            $table->string('nama_barang', 150);
            $table->string('jenis', 100);
            $table->unsignedInteger('jumlah')->default(1);
            $table->string('satuan', 30)->default('unit');
            $table->string('lokasi', 100)->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('status', ['aktif', 'diperbaiki', 'dihapus'])->default('aktif');
            $table->date('tanggal_perolehan')->nullable();
            $table->decimal('harga_perolehan', 14, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
