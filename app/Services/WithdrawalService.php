<?php

namespace App\Services;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    public function __construct(protected AuditService $auditService)
    {
    }

    public function create(array $data): Withdrawal
    {
        return DB::transaction(function () use ($data) {
            $withdrawal = Withdrawal::create($data);
            $this->auditService->log($withdrawal, 'create', null, $withdrawal->toArray());
            return $withdrawal;
        });
    }

    public function update(Withdrawal $withdrawal, array $data): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal, $data) {
            $oldValues = $withdrawal->toArray();
            $withdrawal->update($data);
            $this->auditService->log($withdrawal, 'update', $oldValues, $withdrawal->fresh()->toArray());
            return $withdrawal->fresh();
        });
    }

    public function delete(Withdrawal $withdrawal): bool
    {
        return DB::transaction(function () use ($withdrawal) {
            $oldValues = $withdrawal->toArray();
            $result = $withdrawal->delete();
            $this->auditService->log($withdrawal, 'delete', $oldValues, null);
            return $result;
        });
    }

    public function restore(int $id): Withdrawal
    {
        return DB::transaction(function () use ($id) {
            $withdrawal = Withdrawal::withTrashed()->findOrFail($id);
            $withdrawal->restore();
            $this->auditService->log($withdrawal, 'restore', null, $withdrawal->fresh()->toArray());
            return $withdrawal->fresh();
        });
    }
}
