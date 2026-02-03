<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class LedgerQuery
{
    /** Whitelisted sortable columns */
    private const SORTABLE = [
        'customer_name' => 'c.customer_name',
        'final_balance' => 'final_balance',
        'total_credit' => 'total_credit',
        'total_debit' => 'total_debit',
        'customer_id' => 'c.customer_id',
        'last_txn_date' => 'last_txn_date',
    ];

    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public static function fromRequest($request): self
    {
        return new self([
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'type' => $request->input('type'),
            'amount_min' => $request->input('amount_min'),
            'amount_max' => $request->input('amount_max'),
            'q' => $request->input('q'),
            'sort' => $request->input('sort'),
            'direction' => $request->input('direction', 'desc'),
        ]);
    }

    private function baseQuery()
    {
        $q = DB::table('customers as c')
            ->leftJoin('transactions as t', function ($join) {
                $join->on('t.customer_id', '=', 'c.customer_id');
            });

        if ($this->filters['from'] ?? null) {
            $q->whereDate('t.transaction_date', '>=', $this->filters['from']);
        }
        if ($this->filters['to'] ?? null) {
            $q->whereDate('t.transaction_date', '<=', $this->filters['to']);
        }
        if ($this->filters['type'] ?? null) {
            $q->where('t.transaction_type', $this->filters['type']);
        }
        if ($this->filters['amount_min'] ?? null) {
            $q->where('t.amount', '>=', $this->filters['amount_min']);
        }
        if ($this->filters['amount_max'] ?? null) {
            $q->where('t.amount', '<=', $this->filters['amount_max']);
        }
        if ($this->filters['q'] ?? null) {
            $term = '%' . $this->filters['q'] . '%';
            $q->where(function ($sub) use ($term) {
                $sub->where('c.customer_id', 'like', $term)
                    ->orWhere('c.customer_name', 'like', $term)
                    ->orWhere('c.mobile_number', 'like', $term)
                    ->orWhere('c.email', 'like', $term);
            });
        }

        $q->select([
            'c.customer_id',
            'c.customer_name',
            'c.mobile_number',
            'c.email',
            'c.address',
            'c.opening_balance',
            DB::raw("COALESCE(SUM(CASE WHEN t.transaction_type = 'credit' THEN t.amount END),0) as total_credit"),
            DB::raw("COALESCE(SUM(CASE WHEN t.transaction_type = 'debit' THEN t.amount END),0) as total_debit"),
            DB::raw('COALESCE(MAX(t.transaction_date), c.created_at) as last_txn_date'),
            DB::raw("c.opening_balance + COALESCE(SUM(CASE WHEN t.transaction_type = 'credit' THEN t.amount END),0) - COALESCE(SUM(CASE WHEN t.transaction_type = 'debit' THEN t.amount END),0) as final_balance"),
        ])->groupBy(
                'c.customer_id',
                'c.customer_name',
                'c.mobile_number',
                'c.email',
                'c.address',
                'c.opening_balance',
                'c.created_at'
            );

        $sortKey = $this->filters['sort'] ?? 'final_balance';
        $dir = strtolower($this->filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $column = self::SORTABLE[$sortKey] ?? self::SORTABLE['final_balance'];
        $q->orderBy(DB::raw($column), $dir);

        return $q;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery()->paginate($perPage)->appends(request()->query());
    }

    public function kpis(): array
    {
        $base = $this->baseQuery();
        $aggregate = DB::table(DB::raw("({$base->toSql()}) as ledger"))
            ->mergeBindings($base)
            ->selectRaw('COUNT(*) as customers_count, SUM(total_credit) as grand_credit, SUM(total_debit) as grand_debit, SUM(final_balance) as grand_final')
            ->first();

        return [
            'customers_count' => $aggregate->customers_count ?? 0,
            'grandTotalCredit' => $aggregate->grand_credit ?? 0,
            'grandTotalDebit' => $aggregate->grand_debit ?? 0,
            'grandFinalBalance' => $aggregate->grand_final ?? 0,
        ];
    }

    public function cursor(): Collection
    {
        return $this->baseQuery()->get();
    }
}