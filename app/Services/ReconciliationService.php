<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Settlement;
use App\Models\BankClosing;
use Illuminate\Support\Facades\DB;

class ReconciliationService
{
    /**
     * Calculate reconciliation data for all banks within date range
     * 
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $bankId
     * @return array
     */
    public function getReconciliationData($startDate = null, $endDate = null, $bankId = null)
    {
        $query = Bank::query();

        if ($bankId) {
            $query->where('id', $bankId);
        }

        $banks = $query->get();
        $results = [];

        foreach ($banks as $bank) {
            $data = $this->calculateBankReconciliation($bank->id, $startDate, $endDate);
            $data['bank_name'] = $bank->name;
            $data['bank_type'] = $bank->type;
            $results[] = $data;
        }

        return $results;
    }

    /**
     * Calculate reconciliation for a single bank
     * 
     * @param int $bankId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function calculateBankReconciliation($bankId, $startDate = null, $endDate = null)
    {
        // Build date filter
        $dateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->where('date', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('date', '<=', $endDate);
            }
        };

        // Calculate deposits (Pay IN)
        $totalDeposits = Deposit::where('bank_id', $bankId)
            ->where($dateFilter)
            ->sum('amount');

        // Calculate completed withdrawals (Pay OUT)
        $totalWithdrawals = Withdrawal::where('bank_id', $bankId)
            ->where('status', 'completed')
            ->where($dateFilter)
            ->sum('amount');

        // Calculate pending withdrawals
        $pendingWithdrawals = Withdrawal::where('bank_id', $bankId)
            ->where('status', 'pending')
            ->where($dateFilter)
            ->sum('amount');

        // Calculate settlements OUT
        $settlementsOut = Settlement::where('from_bank_id', $bankId)
            ->where($dateFilter)
            ->sum('amount');

        // Calculate settlements IN
        $settlementsIn = Settlement::where('to_bank_id', $bankId)
            ->where($dateFilter)
            ->sum('amount');

        // Net settlements
        $netSettlements = $settlementsIn - $settlementsOut;

        // System balance calculation
        $systemBalance = $totalDeposits - $totalWithdrawals + $netSettlements;

        // Get actual closing (latest within date range)
        $actualClosing = BankClosing::where('bank_id', $bankId)
            ->where($dateFilter)
            ->orderBy('date', 'desc')
            ->value('actual_closing') ?? 0;

        // Calculate difference
        $difference = $actualClosing - $systemBalance;

        return [
            'bank_id' => $bankId,
            'total_deposits' => round($totalDeposits, 2),
            'total_withdrawals' => round($totalWithdrawals, 2),
            'pending_withdrawals' => round($pendingWithdrawals, 2),
            'settlements_in' => round($settlementsIn, 2),
            'settlements_out' => round($settlementsOut, 2),
            'net_settlements' => round($netSettlements, 2),
            'system_balance' => round($systemBalance, 2),
            'actual_closing' => round($actualClosing, 2),
            'difference' => round($difference, 2),
        ];
    }

    /**
     * Get filtered deposits
     */
    public function getDeposits($filters = [])
    {
        $query = Deposit::with('bank');

        if (!empty($filters['bank_id'])) {
            $query->where('bank_id', $filters['bank_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        if (!empty($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }

        return $query->orderBy('date', 'desc')->orderBy('id', 'desc');
    }

    /**
     * Get filtered withdrawals
     */
    public function getWithdrawals($filters = [])
    {
        $query = Withdrawal::with('bank');

        if (!empty($filters['bank_id'])) {
            $query->where('bank_id', $filters['bank_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        if (!empty($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }

        return $query->orderBy('date', 'desc')->orderBy('id', 'desc');
    }

    /**
     * Get filtered settlements
     */
    public function getSettlements($filters = [])
    {
        $query = Settlement::with(['fromBank', 'toBank']);

        if (!empty($filters['from_bank_id'])) {
            $query->where('from_bank_id', $filters['from_bank_id']);
        }

        if (!empty($filters['to_bank_id'])) {
            $query->where('to_bank_id', $filters['to_bank_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        if (!empty($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (!empty($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }

        return $query->orderBy('date', 'desc')->orderBy('id', 'desc');
    }
}
