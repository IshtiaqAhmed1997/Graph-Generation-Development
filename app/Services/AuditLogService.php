<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(string $action, ?int $goalResultId = null, ?string $details = null, ?string $versionId = null): void
    {
        AuditLog::create([
            'goal_result_id' => $goalResultId,
            'user_id'        => Auth::id(),
            'action'         => $action,
            'details'        => $details,
            'version_id'     => $versionId,
        ]);
    }
}
