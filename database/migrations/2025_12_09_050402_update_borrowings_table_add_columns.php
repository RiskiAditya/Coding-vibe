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
        Schema::table('borrowings', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('borrowings', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('borrowings', 'book_id')) {
                $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            }
            if (!Schema::hasColumn('borrowings', 'borrow_date')) {
                $table->date('borrow_date');
            }
            if (!Schema::hasColumn('borrowings', 'due_date')) {
                $table->date('due_date');
            }
            if (!Schema::hasColumn('borrowings', 'return_date')) {
                $table->date('return_date')->nullable();
            }
            if (!Schema::hasColumn('borrowings', 'status')) {
                $table->enum('status', ['active', 'returned', 'overdue'])->default('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('borrowings', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('borrowings', 'book_id')) {
                $table->dropForeign(['book_id']);
                $table->dropColumn('book_id');
            }
            if (Schema::hasColumn('borrowings', 'borrow_date')) {
                $table->dropColumn('borrow_date');
            }
            if (Schema::hasColumn('borrowings', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('borrowings', 'return_date')) {
                $table->dropColumn('return_date');
            }
            if (Schema::hasColumn('borrowings', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
