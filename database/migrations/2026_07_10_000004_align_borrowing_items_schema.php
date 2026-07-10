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
        Schema::table('borrowing_items', function (Blueprint $table) {
            if (! Schema::hasColumn('borrowing_items', 'condition_at_borrow_id')) {
                $table->unsignedBigInteger('condition_at_borrow_id')->nullable()->after('inventory_code_snapshot');
            }

            if (! Schema::hasColumn('borrowing_items', 'condition_at_return_id')) {
                $table->unsignedBigInteger('condition_at_return_id')->nullable()->after('return_date');
            }

            if (! Schema::hasColumn('borrowing_items', 'fine_amount')) {
                $table->decimal('fine_amount', 12, 2)->default('0.00')->after('status');
            }

            if (! Schema::hasColumn('borrowing_items', 'returned_by')) {
                $table->unsignedBigInteger('returned_by')->nullable()->after('fine_amount');
            }

            if (! Schema::hasColumn('borrowing_items', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('borrowing_items', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });

        if (Schema::hasColumn('borrowing_items', 'returned_condition_id')) {
            DB::statement('UPDATE `borrowing_items` SET `condition_at_return_id` = `returned_condition_id` WHERE `condition_at_return_id` IS NULL');
        }

        if (DB::connection()->getDriverName() === 'mysql' && Schema::hasColumn('book_copies', 'condition_id')) {
            DB::statement(<<<'SQL'
UPDATE `borrowing_items` `bi`
JOIN `book_copies` `bc` ON `bc`.`id` = `bi`.`book_copy_id`
SET `bi`.`condition_at_borrow_id` = `bc`.`condition_id`
WHERE `bi`.`condition_at_borrow_id` IS NULL
SQL
            );
        }

        Schema::table('fines', function (Blueprint $table) {
            if (! Schema::hasColumn('fines', 'borrowing_item_id')) {
                $table->unsignedBigInteger('borrowing_item_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('fines', 'fine_type')) {
                $table->string('fine_type', 30)->default('late')->after('borrowing_item_id');
            }

            if (! Schema::hasColumn('fines', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default('0.00')->after('status');
            }

            if (! Schema::hasColumn('fines', 'paid_date')) {
                $table->date('paid_date')->nullable()->after('paid_amount');
            }

            if (! Schema::hasColumn('fines', 'waived_by')) {
                $table->unsignedBigInteger('waived_by')->nullable()->after('paid_date');
            }

            if (! Schema::hasColumn('fines', 'waived_reason')) {
                $table->string('waived_reason', 255)->nullable()->after('waived_by');
            }

            if (! Schema::hasColumn('fines', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('waived_reason');
            }

            if (! Schema::hasColumn('fines', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });

        if (Schema::hasColumn('fines', 'payment_date')) {
            DB::statement('UPDATE `fines` SET `paid_date` = `payment_date` WHERE `paid_date` IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            foreach (['updated_by', 'created_by', 'waived_reason', 'waived_by', 'paid_date', 'paid_amount', 'fine_type', 'borrowing_item_id'] as $column) {
                if (Schema::hasColumn('fines', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('borrowing_items', function (Blueprint $table) {
            foreach (['updated_by', 'created_by', 'returned_by', 'fine_amount', 'condition_at_return_id', 'condition_at_borrow_id'] as $column) {
                if (Schema::hasColumn('borrowing_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
