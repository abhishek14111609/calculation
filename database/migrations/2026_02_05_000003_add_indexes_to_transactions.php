<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!$this->indexExists('deposits', 'deposits_bank_id_index')) {
                $table->index('bank_id');
            }
            if (!$this->indexExists('deposits', 'deposits_date_index')) {
                $table->index('date');
            }
            if (!$this->indexExists('deposits', 'deposits_utr_index')) {
                $table->index('utr');
            }
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            if (!$this->indexExists('withdrawals', 'withdrawals_bank_id_index')) {
                $table->index('bank_id');
            }
            if (!$this->indexExists('withdrawals', 'withdrawals_date_index')) {
                $table->index('date');
            }
            if (!$this->indexExists('withdrawals', 'withdrawals_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('withdrawals', 'withdrawals_utr_index')) {
                $table->index('utr');
            }
        });

        Schema::table('settlements', function (Blueprint $table) {
            if (!$this->indexExists('settlements', 'settlements_from_bank_id_index')) {
                $table->index('from_bank_id');
            }
            if (!$this->indexExists('settlements', 'settlements_to_bank_id_index')) {
                $table->index('to_bank_id');
            }
            if (!$this->indexExists('settlements', 'settlements_date_index')) {
                $table->index('date');
            }
        });

        Schema::table('bank_closings', function (Blueprint $table) {
            if (!$this->indexExists('bank_closings', 'bank_closings_bank_id_date_unique')) {
                $table->unique(['bank_id', 'date']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropIndex('deposits_bank_id_index');
            $table->dropIndex('deposits_date_index');
            $table->dropIndex('deposits_utr_index');
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropIndex('withdrawals_bank_id_index');
            $table->dropIndex('withdrawals_date_index');
            $table->dropIndex('withdrawals_status_index');
            $table->dropIndex('withdrawals_utr_index');
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->dropIndex('settlements_from_bank_id_index');
            $table->dropIndex('settlements_to_bank_id_index');
            $table->dropIndex('settlements_date_index');
        });

        Schema::table('bank_closings', function (Blueprint $table) {
            $table->dropUnique('bank_closings_bank_id_date_unique');
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]);
        return count($indexes) > 0;
    }
};
