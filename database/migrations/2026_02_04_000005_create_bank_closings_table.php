<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Creates bank_closings table for daily actual closing balances
     */
    public function up(): void
    {
        Schema::create('bank_closings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            $table->decimal('actual_closing', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            // One closing per bank per date
            $table->unique(['date', 'bank_id'], 'unique_bank_closing');
            $table->index(['bank_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_closings');
    }
};
