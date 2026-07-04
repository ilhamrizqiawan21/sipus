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
        // Users table (extended from default)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Master Data Tables
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('code', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('biography')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bookshelves', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('location', 255)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('fine_multiplier', 3, 2)->default(1.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_copy_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->integer('max_borrow_limit')->default(5);
            $table->integer('borrow_duration_days')->default(14);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('level', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visit_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Books table
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete()->cascadeOnUpdate();
            $table->smallInteger('publication_year')->nullable();
            $table->string('edition', 50)->nullable();
            $table->integer('pages')->nullable();
            $table->string('call_number', 50)->nullable();
            $table->longText('synopsis')->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
            $table->index('title');
        });

        // Book Authors pivot table
        Schema::create('book_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
            $table->unique(['book_id', 'author_id']);
        });

        // Book Copies table
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('barcode', 100)->nullable();
            $table->string('location', 255)->nullable();
            $table->foreignId('status_id')->nullable()->constrained('book_copy_statuses')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('condition_id')->nullable()->constrained('book_conditions')->nullOnDelete()->cascadeOnUpdate();
            $table->date('acquisition_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Members table
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 30)->unique();
            $table->string('nis_nisn', 50)->nullable();
            $table->string('nip', 50)->nullable();
            $table->foreignId('member_type_id')->nullable()->constrained('member_types')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name', 150);
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->date('join_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        // Visitors table
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('institution', 255)->nullable();
            $table->date('visit_date');
            $table->foreignId('visit_purpose_id')->nullable()->constrained('visit_purposes')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Circulation tables
        Schema::create('borrowing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 30)->unique();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('member_code_snapshot', 30);
            $table->string('member_name_snapshot', 150);
            $table->string('member_class_snapshot', 50)->nullable();
            $table->string('member_type_snapshot', 50);
            $table->date('borrow_date');
            $table->date('due_date');
            $table->enum('status', ['borrowed', 'partially_returned', 'returned', 'overdue'])->default('borrowed');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
            $table->index('borrow_date');
        });

        Schema::create('borrowing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_transaction_id')->constrained('borrowing_transactions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_copy_id')->constrained('book_copies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('book_title_snapshot', 255);
            $table->string('book_isbn_snapshot', 20)->nullable();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'lost', 'damaged'])->default('borrowed');
            $table->foreignId('returned_condition_id')->nullable()->constrained('book_conditions')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_transaction_id')->constrained('borrowing_transactions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_copy_id')->nullable()->constrained('book_copies')->nullOnDelete()->cascadeOnUpdate();
            $table->string('reason', 100);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Procurement tables
        Schema::create('book_procurements', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name', 255);
            $table->date('order_date');
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending');
            $table->decimal('total', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_procurement_id')->constrained('book_procurements')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity_received')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Opname tables
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('opname_code', 50)->unique();
            $table->date('opname_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_copy_id')->constrained('book_copies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('expected_condition', 50)->nullable();
            $table->string('actual_condition', 50);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Book Incidents
        Schema::create('book_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_copy_id')->constrained('book_copies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('incident_type', ['damage', 'loss', 'theft', 'other'])->default('damage');
            $table->text('description')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->date('incident_date');
            $table->timestamps();
            $table->softDeletes();
        });

        // System tables
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('import_type', ['books', 'members', 'inventory']);
            $table->string('file_name', 255);
            $table->integer('total_rows');
            $table->integer('successful_rows');
            $table->integer('failed_rows');
            $table->enum('status', ['success', 'partial', 'failed'])->default('success');
            $table->json('error_details')->nullable();
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('action', 50);
            $table->string('table_name', 100);
            $table->bigInteger('record_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique();
            $table->longText('setting_value')->nullable();
            $table->string('setting_type', 50)->default('string');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('import_logs');
        Schema::dropIfExists('book_incidents');
        Schema::dropIfExists('stock_opname_details');
        Schema::dropIfExists('stock_opnames');
        Schema::dropIfExists('book_procurement_items');
        Schema::dropIfExists('book_procurements');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('borrowing_items');
        Schema::dropIfExists('borrowing_transactions');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('members');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('book_authors');
        Schema::dropIfExists('books');
        Schema::dropIfExists('visit_purposes');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('member_types');
        Schema::dropIfExists('book_copy_statuses');
        Schema::dropIfExists('book_sources');
        Schema::dropIfExists('book_conditions');
        Schema::dropIfExists('bookshelves');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('languages');
    }
};
