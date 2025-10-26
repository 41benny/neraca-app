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
        Schema::create('account_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->string('report_type', 50);
            $table->string('group_name');
            $table->string('side', 50);
            $table->tinyInteger('sign')->default(1);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();

            $table->unique(['account_id', 'report_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_mappings');
    }
};
