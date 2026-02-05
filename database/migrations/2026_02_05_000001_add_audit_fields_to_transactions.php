<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('remark');
            }
            if (!Schema::hasColumn('deposits', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('deposits', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('remark');
            }
            if (!Schema::hasColumn('withdrawals', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('withdrawals', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('settlements', function (Blueprint $table) {
            if (!Schema::hasColumn('settlements', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('remark');
            }
            if (!Schema::hasColumn('settlements', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('settlements', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('bank_closings', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_closings', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('actual_closing');
            }
            if (!Schema::hasColumn('bank_closings', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('bank_closings', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
            $table->dropSoftDeletes();
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
            $table->dropSoftDeletes();
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
            $table->dropSoftDeletes();
        });

        Schema::table('bank_closings', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
            $table->dropSoftDeletes();
        });
    }
};
