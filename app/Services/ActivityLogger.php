<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ActivityLogger
{
    public static function log(string $action, string $referenceId, array $metadata = []): void
    {
        try {
            DB::table('activity_logs')->insert([
                'action_type' => $action,
                'reference_id' => $referenceId,
                'ip_address' => request()->ip(),
                'metadata' => json_encode($metadata),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // fail silently to avoid blocking UX
        }
    }
}
