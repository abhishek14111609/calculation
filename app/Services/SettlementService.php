<?php

namespace App\Services;

use App\Models\Settlement;
use Illuminate\Support\Facades\DB;

class SettlementService
{
    public function __construct(protected AuditService $auditService)
    {
    }

    public function create(array $data): Settlement
    {
        return DB::transaction(function () use ($data) {
            $settlement = Settlement::create($data);
            $this->auditService->log($settlement, 'create', null, $settlement->toArray());
            return $settlement;
        });
    }

    public function update(Settlement $settlement, array $data): Settlement
    {
        return DB::transaction(function () use ($settlement, $data) {
            $oldValues = $settlement->toArray();
            $settlement->update($data);
            $this->auditService->log($settlement, 'update', $oldValues, $settlement->fresh()->toArray());
            return $settlement->fresh();
        });
    }

    public function delete(Settlement $settlement): bool
    {
        return DB::transaction(function () use ($settlement) {
            $oldValues = $settlement->toArray();
            $result = $settlement->delete();
            $this->auditService->log($settlement, 'delete', $oldValues, null);
            return $result;
        });
    }

    public function restore(int $id): Settlement
    {
        return DB::transaction(function () use ($id) {
            $settlement = Settlement::withTrashed()->findOrFail($id);
            $settlement->restore();
            $this->auditService->log($settlement, 'restore', null, $settlement->fresh()->toArray());
            return $settlement->fresh();
        });
    }
}
