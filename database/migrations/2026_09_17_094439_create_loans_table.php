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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 40)->unique();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('batas_kembali');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'dipinjam', 'sebagian_kembali', 'selesai', 'terlambat', 'dibatalkan'])->default('dipinjam');
            $table->text('catatan')->nullable();
            $table->index(['member_id', 'status']);
            $table->index(['status', 'batas_kembali']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
