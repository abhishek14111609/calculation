<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Creates withdrawals table for Pay OUT transactions
     */
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('utr')->nullable();
            $table->string('source_name')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('completed');
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate UTR per bank per date
            $table->unique(['bank_id', 'date', 'utr'], 'unique_withdrawal_utr');
            $table->index(['bank_id', 'date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
