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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku', 30)->nullable()->unique();
            $table->string('judul', 255);
            $table->foreignId('jenis_buku_id')->constrained('book_types')->restrictOnDelete();
            $table->foreignId('penerbit_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->unsignedSmallInteger('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cover_path')->nullable();
            $table->index('judul');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
