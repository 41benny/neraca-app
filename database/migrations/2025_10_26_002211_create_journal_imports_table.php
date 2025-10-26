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
        Schema::create('journal_imports', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name');
            $table->string('original_filename');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('rows_count')->default(0);
            $table->decimal('total_debit', 18, 2)->default(0);
            $table->decimal('total_credit', 18, 2)->default(0);
            $table->string('status', 30)->default('completed');
            $table->json('context')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_imports');
    }
};
