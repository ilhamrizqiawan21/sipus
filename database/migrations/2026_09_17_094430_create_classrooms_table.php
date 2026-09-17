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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('tingkat', 20);
            $table->foreignId('school_year_id')->constrained('school_years')->restrictOnDelete();
            $table->string('wali_nama')->nullable();
            $table->boolean('aktif')->default(true);
            $table->unique(['nama', 'school_year_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
