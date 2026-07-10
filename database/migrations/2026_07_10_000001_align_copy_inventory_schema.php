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
        Schema::table('bookshelves', function (Blueprint $table) {
            if (! Schema::hasColumn('bookshelves', 'code')) {
                $table->string('code', 20)->nullable()->after('id');
            }

            if (! Schema::hasColumn('bookshelves', 'capacity')) {
                $table->integer('capacity')->unsigned()->nullable()->after('location');
            }

            if (! Schema::hasColumn('bookshelves', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            }

            if (! Schema::hasColumn('bookshelves', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });

        if (Schema::hasColumn('bookshelves', 'code')) {
            DB::statement(<<<'SQL'
UPDATE `bookshelves`
SET `code` = CONCAT('RAK-', `id`)
WHERE `code` IS NULL OR `code` = ''
SQL
            );
        }

        Schema::table('book_copies', function (Blueprint $table) {
            if (! Schema::hasColumn('book_copies', 'inventory_code')) {
                $table->string('inventory_code', 30)->nullable()->after('book_id');
            }

            if (! Schema::hasColumn('book_copies', 'bookshelf_id')) {
                $table->unsignedBigInteger('bookshelf_id')->nullable()->after('barcode');
            }

            if (! Schema::hasColumn('book_copies', 'source_id')) {
                $table->unsignedBigInteger('source_id')->nullable()->after('condition_id');
            }

            if (! Schema::hasColumn('book_copies', 'procurement_item_id')) {
                $table->unsignedBigInteger('procurement_item_id')->nullable()->after('status_id');
            }

            if (! Schema::hasColumn('book_copies', 'acquisition_price')) {
                $table->decimal('acquisition_price', 12, 2)->nullable()->after('acquisition_date');
            }

            if (! Schema::hasColumn('book_copies', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('book_copies', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });

        if (Schema::hasColumn('book_copies', 'inventory_code')) {
            DB::statement(<<<'SQL'
UPDATE `book_copies`
SET `inventory_code` = CONCAT('COPY-', `id`)
WHERE `inventory_code` IS NULL OR `inventory_code` = ''
SQL
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_copies', function (Blueprint $table) {
            foreach (['updated_by', 'created_by', 'acquisition_price', 'procurement_item_id', 'source_id', 'bookshelf_id', 'inventory_code'] as $column) {
                if (Schema::hasColumn('book_copies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('bookshelves', function (Blueprint $table) {
            foreach (['updated_by', 'created_by', 'capacity', 'code'] as $column) {
                if (Schema::hasColumn('bookshelves', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
