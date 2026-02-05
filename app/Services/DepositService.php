<?php

namespace App\Services;

use App\Models\Deposit;
use Illuminate\Support\Facades\DB;

class DepositService
{
    public function __construct(protected AuditService $auditService)
    {
    }

    public function create(array $data): Deposit
    {
        return DB::transaction(function () use ($data) {
            $deposit = Deposit::create($data);
            $this->auditService->log($deposit, 'create', null, $deposit->toArray());
            return $deposit;
        });
    }

    public function update(Deposit $deposit, array $data): Deposit
    {
        return DB::transaction(function () use ($deposit, $data) {
            $oldValues = $deposit->toArray();
            $deposit->update($data);
            $this->auditService->log($deposit, 'update', $oldValues, $deposit->fresh()->toArray());
            return $deposit->fresh();
        });
    }

    public function delete(Deposit $deposit): bool
    {
        return DB::transaction(function () use ($deposit) {
            $oldValues = $deposit->toArray();
            $result = $deposit->delete();
            $this->auditService->log($deposit, 'delete', $oldValues, null);
            return $result;
        });
    }

    public function restore(int $id): Deposit
    {
        return DB::transaction(function () use ($id) {
            $deposit = Deposit::withTrashed()->findOrFail($id);
            $deposit->restore();
            $this->auditService->log($deposit, 'restore', null, $deposit->fresh()->toArray());
            return $deposit->fresh();
        });
    }
}
