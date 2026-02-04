<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Creates deposits table for Pay IN transactions
     */
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('utr')->nullable();
            $table->string('source_name')->nullable(); // ID / exchange name
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate UTR per bank per date
            $table->unique(['bank_id', 'date', 'utr'], 'unique_deposit_utr');
            $table->index(['bank_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
