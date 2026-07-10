<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'key')) {
                $table->string('key', 100)->nullable()->after('id');
            }

            if (! Schema::hasColumn('settings', 'value')) {
                $table->text('value')->nullable()->after('key');
            }

            if (! Schema::hasColumn('settings', 'type')) {
                $table->string('type', 20)->default('string')->after('value');
            }

            if (! Schema::hasColumn('settings', 'group')) {
                $table->string('group', 50)->nullable()->after('type');
            }

            if (! Schema::hasColumn('settings', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('description');
            }

            if (! Schema::hasColumn('settings', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('settings', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (Schema::hasColumn('settings', 'setting_key')) {
            DB::statement('UPDATE `settings` SET `key` = `setting_key` WHERE `key` IS NULL');
        }

        if (Schema::hasColumn('settings', 'setting_value')) {
            DB::statement('UPDATE `settings` SET `value` = `setting_value` WHERE `value` IS NULL');
        }

        if (Schema::hasColumn('settings', 'setting_type')) {
            DB::statement('UPDATE `settings` SET `type` = `setting_type` WHERE `type` IS NULL OR `type` = ""');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['updated_at', 'created_at', 'updated_by', 'group', 'type', 'value', 'key'] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
