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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('code', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 20)->nullable()->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete()->cascadeOnUpdate();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('biography')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bookshelves', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('location', 150)->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_copy_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 50);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->integer('borrow_limit')->unsigned()->default(2);
            $table->integer('borrow_duration_days')->unsigned()->default(7);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('grade_level', 10)->nullable();
            $table->string('academic_year', 9)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visit_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete()->cascadeOnUpdate();
            $table->smallInteger('publication_year')->unsigned()->nullable();
            $table->string('edition', 50)->nullable();
            $table->integer('pages')->unsigned()->nullable();
            $table->string('call_number', 50)->nullable();
            $table->text('synopsis')->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
            $table->index('title');
        });

        Schema::create('book_procurements', function (Blueprint $table) {
            $table->id();
            $table->string('procurement_code', 30)->unique();
            $table->foreignId('source_id')->constrained('book_sources')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('supplier_name', 150)->nullable();
            $table->string('invoice_number', 50)->nullable();
            $table->date('procurement_date');
            $table->decimal('total_amount', 14, 2)->default('0.00');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_id')->constrained('book_procurements')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('quantity')->unsigned();
            $table->decimal('price_per_unit', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('inventory_code', 30)->unique();
            $table->string('barcode', 50)->nullable()->unique();
            $table->foreignId('bookshelf_id')->nullable()->constrained('bookshelves')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('condition_id')->constrained('book_conditions')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('source_id')->constrained('book_sources')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('status_id')->constrained('book_copy_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('procurement_item_id')->nullable()->constrained('book_procurement_items')->nullOnDelete()->cascadeOnUpdate();
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_price', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
            $table->unique(['book_id', 'author_id']);
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 30)->unique();
            $table->string('nis_nisn', 30)->nullable();
            $table->string('nip', 30)->nullable();
            $table->foreignId('member_type_id')->constrained('member_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name', 150);
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('card_number', 50)->nullable()->unique();
            $table->date('join_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete()->cascadeOnUpdate();
            $table->string('guest_name', 150)->nullable();
            $table->foreignId('visit_purpose_id')->constrained('visit_purposes')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('visit_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('notes', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('borrowing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 30)->unique();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete()->cascadeOnUpdate();
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
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('book_title_snapshot', 255);
            $table->string('inventory_code_snapshot', 30);
            $table->foreignId('condition_at_borrow_id')->constrained('book_conditions')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->foreignId('condition_at_return_id')->nullable()->constrained('book_conditions')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['borrowed', 'returned', 'lost', 'damaged'])->default('borrowed');
            $table->decimal('fine_amount', 12, 2)->default('0.00');
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_item_id')->constrained('borrowing_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('fine_type', ['late', 'damage', 'lost']);
            $table->decimal('amount', 12, 2);
            $table->string('reason', 255)->nullable();
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->decimal('paid_amount', 12, 2)->default('0.00');
            $table->date('paid_date')->nullable();
            $table->foreignId('waived_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('waived_reason', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('opname_code', 30)->unique();
            $table->date('opname_date');
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->foreignId('conducted_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('system_status_id')->constrained('book_copy_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('physical_status_id')->constrained('book_copy_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('condition_found_id')->constrained('book_conditions')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('book_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('incident_type', ['damaged', 'lost']);
            $table->date('incident_date');
            $table->foreignId('related_borrowing_item_id')->nullable()->constrained('borrowing_items')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('description')->nullable();
            $table->text('resolution')->nullable();
            $table->enum('status', ['reported', 'resolved'])->default('reported');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('import_type', ['member', 'book']);
            $table->string('file_name', 255);
            $table->integer('total_rows')->unsigned()->default(0);
            $table->integer('success_rows')->unsigned()->default(0);
            $table->integer('failed_rows')->unsigned()->default(0);
            $table->json('error_details')->nullable();
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('action', 50);
            $table->string('module', 100)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('model_type', 150)->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string');
            $table->string('group', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
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
        Schema::dropIfExists('fines');
        Schema::dropIfExists('borrowing_items');
        Schema::dropIfExists('borrowing_transactions');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('members');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('book_procurement_items');
        Schema::dropIfExists('book_procurements');
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
