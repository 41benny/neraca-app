<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_lines', function (Blueprint $table) {
            $table->string('party_type', 20)->nullable()->after('vehicle_id');
            $table->string('party_code', 100)->nullable()->after('party_type');
            $table->string('party_name')->nullable()->after('party_code');

            $table->index('party_code');
        });
    }

    public function down(): void
    {
        Schema::table('journal_lines', function (Blueprint $table) {
            $table->dropIndex(['party_code']);
            $table->dropColumn(['party_type', 'party_code', 'party_name']);
        });
    }
};
