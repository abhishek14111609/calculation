<?php

namespace App\Services;

use App\Models\BankClosing;
use Illuminate\Support\Facades\DB;

class BankClosingService
{
    public function __construct(protected AuditService $auditService)
    {
    }

    public function create(array $data): BankClosing
    {
        return DB::transaction(function () use ($data) {
            $closing = BankClosing::create($data);
            $this->auditService->log($closing, 'create', null, $closing->toArray());
            return $closing;
        });
    }

    public function update(BankClosing $closing, array $data): BankClosing
    {
        return DB::transaction(function () use ($closing, $data) {
            $oldValues = $closing->toArray();
            $closing->update($data);
            $this->auditService->log($closing, 'update', $oldValues, $closing->fresh()->toArray());
            return $closing->fresh();
        });
    }

    public function delete(BankClosing $closing): bool
    {
        return DB::transaction(function () use ($closing) {
            $oldValues = $closing->toArray();
            $result = $closing->delete();
            $this->auditService->log($closing, 'delete', $oldValues, null);
            return $result;
        });
    }

    public function restore(int $id): BankClosing
    {
        return DB::transaction(function () use ($id) {
            $closing = BankClosing::withTrashed()->findOrFail($id);
            $closing->restore();
            $this->auditService->log($closing, 'restore', null, $closing->fresh()->toArray());
            return $closing->fresh();
        });
    }
}
