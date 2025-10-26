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
        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_import_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->date('journal_date');
            $table->string('document_no')->nullable();
            $table->string('description')->nullable();
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->string('branch_code', 50)->nullable();
            $table->string('invoice_id', 100)->nullable();
            $table->string('project_id', 100)->nullable();
            $table->string('vehicle_id', 100)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['journal_date', 'account_id']);
            $table->index('branch_code');
            $table->index('invoice_id');
            $table->index('project_id');
            $table->index('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_lines');
    }
};
