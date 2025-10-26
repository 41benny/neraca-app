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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('level')->default(1);
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->string('normal_balance', 10);
            $table->string('account_type', 50);
            $table->boolean('is_cash_account')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
