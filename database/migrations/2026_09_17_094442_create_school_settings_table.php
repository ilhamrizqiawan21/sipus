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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name', 150);
            $table->string('logo_path')->nullable();
            $table->text('address')->nullable();
            $table->string('education_level', 50)->nullable();
            $table->unsignedSmallInteger('teacher_loan_days')->default(14);
            $table->unsignedSmallInteger('student_loan_days')->default(7);
            $table->unsignedSmallInteger('teacher_loan_limit')->default(5);
            $table->unsignedSmallInteger('student_loan_limit')->default(2);
            $table->decimal('late_fee_per_day', 14, 2)->default(1000);
            $table->decimal('lost_book_fee', 14, 2)->default(0);
            $table->decimal('damaged_book_fee', 14, 2)->default(0);
            $table->string('member_number_format', 50)->default('SIPUS-YYYY-NNNNN');
            $table->string('loan_number_format', 50)->default('TRX-YYYYMM-NNNNN');
            $table->boolean('fine_blocks_loan')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
