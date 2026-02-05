<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Settlement;
use App\Models\BankClosing;
use Illuminate\Support\Collection;

class PassbookService
{
    public function getPassbookData(array $filters): array
    {
        $bankId = $filters['bank_id'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $transactionType = $filters['transaction_type'] ?? 'all';

        $openingBalance = $this->getOpeningBalance($bankId, $startDate);

        $transactions = collect();

        if (in_array($transactionType, ['all', 'deposit'])) {
            $transactions = $transactions->merge($this->getDeposits($bankId, $startDate, $endDate));
        }

        if (in_array($transactionType, ['all', 'withdrawal'])) {
            $transactions = $transactions->merge($this->getWithdrawals($bankId, $startDate, $endDate));
        }

        if (in_array($transactionType, ['all', 'settlement'])) {
            $transactions = $transactions->merge($this->getSettlements($bankId, $startDate, $endDate));
        }

        $transactions = $transactions->sortBy([
            ['date', $filters['sort_order'] ?? 'asc'],
            ['created_at', $filters['sort_order'] ?? 'asc']
        ])->values();

        $runningBalance = $openingBalance;
        $allTransactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['amount'];
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });

        return [
            'opening_balance' => $openingBalance,
            'closing_balance' => $runningBalance,
            'transactions' => $allTransactions,
            'total_credit' => $allTransactions->where('amount', '>', 0)->sum('amount'),
            'total_debit' => abs($allTransactions->where('amount', '<', 0)->sum('amount')),
            'transaction_count' => $allTransactions->count(),
        ];
    }

    protected function getOpeningBalance($bankId, $startDate): float
    {
        if (!$bankId || !$startDate) {
            return 0;
        }

        $closing = BankClosing::where('bank_id', $bankId)
            ->where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->first();

        return $closing ? $closing->actual_closing : 0;
    }

    protected function getDeposits($bankId, $startDate, $endDate): Collection
    {
        $query = Deposit::with('bank');

        if ($bankId) {
            $query->where('bank_id', $bankId);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->get()->map(function ($deposit) {
            return [
                'id' => $deposit->id,
                'date' => $deposit->date,
                'type' => 'deposit',
                'type_label' => 'Deposit',
                'description' => 'Deposit' . ($deposit->source_name ? ' from ' . $deposit->source_name : ''),
                'bank_name' => $deposit->bank->name,
                'utr' => $deposit->utr,
                'amount' => $deposit->amount,
                'remark' => $deposit->remark,
                'created_at' => $deposit->created_at,
            ];
        });
    }

    protected function getWithdrawals($bankId, $startDate, $endDate): Collection
    {
        $query = Withdrawal::with('bank')->where('status', 'completed');

        if ($bankId) {
            $query->where('bank_id', $bankId);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->get()->map(function ($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'date' => $withdrawal->date,
                'type' => 'withdrawal',
                'type_label' => 'Withdrawal',
                'description' => 'Withdrawal' . ($withdrawal->source_name ? ' to ' . $withdrawal->source_name : ''),
                'bank_name' => $withdrawal->bank->name,
                'utr' => $withdrawal->utr,
                'amount' => -$withdrawal->amount,
                'remark' => $withdrawal->remark,
                'created_at' => $withdrawal->created_at,
            ];
        });
    }

    protected function getSettlements($bankId, $startDate, $endDate): Collection
    {
        $query = Settlement::with(['fromBank', 'toBank']);

        if ($bankId) {
            $query->where(function ($q) use ($bankId) {
                $q->where('from_bank_id', $bankId)
                    ->orWhere('to_bank_id', $bankId);
            });
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->get()->flatMap(function ($settlement) use ($bankId) {
            $transactions = [];

            if ($bankId) {
                if ($settlement->from_bank_id == $bankId) {
                    $transactions[] = [
                        'id' => $settlement->id,
                        'date' => $settlement->date,
                        'type' => 'settlement_out',
                        'type_label' => 'Settlement OUT',
                        'description' => 'Settlement to ' . $settlement->toBank->name,
                        'bank_name' => $settlement->fromBank->name,
                        'utr' => $settlement->utr,
                        'amount' => -$settlement->amount,
                        'remark' => $settlement->remark,
                        'created_at' => $settlement->created_at,
                    ];
                }

                if ($settlement->to_bank_id == $bankId) {
                    $transactions[] = [
                        'id' => $settlement->id,
                        'date' => $settlement->date,
                        'type' => 'settlement_in',
                        'type_label' => 'Settlement IN',
                        'description' => 'Settlement from ' . $settlement->fromBank->name,
                        'bank_name' => $settlement->toBank->name,
                        'utr' => $settlement->utr,
                        'amount' => $settlement->amount,
                        'remark' => $settlement->remark,
                        'created_at' => $settlement->created_at,
                    ];
                }
            } else {
                $transactions[] = [
                    'id' => $settlement->id,
                    'date' => $settlement->date,
                    'type' => 'settlement',
                    'type_label' => 'Settlement',
                    'description' => $settlement->fromBank->name . ' → ' . $settlement->toBank->name,
                    'bank_name' => 'Settlement',
                    'utr' => $settlement->utr,
                    'amount' => 0,
                    'remark' => $settlement->remark,
                    'created_at' => $settlement->created_at,
                ];
            }

            return $transactions;
        });
    }
}
