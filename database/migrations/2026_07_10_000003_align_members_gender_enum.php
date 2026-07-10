<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::table('members')->where('gender', 'M')->update(['gender' => 'L']);
            DB::table('members')->where('gender', 'F')->update(['gender' => 'P']);
            DB::statement("ALTER TABLE `members` MODIFY `gender` ENUM('L','P') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::table('members')->where('gender', 'L')->update(['gender' => 'M']);
            DB::table('members')->where('gender', 'P')->update(['gender' => 'F']);
            DB::statement("ALTER TABLE `members` MODIFY `gender` ENUM('M','F') NULL");
        }
    }
};
