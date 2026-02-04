<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Creates settlements table for inter-bank transfers
     */
    public function up(): void
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('from_bank_id')->constrained('banks')->onDelete('cascade');
            $table->foreignId('to_bank_id')->constrained('banks')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('utr')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate UTR per date
            $table->unique(['date', 'utr'], 'unique_settlement_utr');
            $table->index(['from_bank_id', 'date']);
            $table->index(['to_bank_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
