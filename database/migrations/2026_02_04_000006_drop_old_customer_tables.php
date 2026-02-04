<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Drops old customer-ledger system tables
     */
    public function up(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('customers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse - old system is deprecated
    }
};
