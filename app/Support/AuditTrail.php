<?php
namespace App\Support;
use App\Models\AuditLog;
class AuditTrail
{
    public static function log(string $action, string $module, $subject=null, array $old=[], array $new=[], ?string $note=null): void
    {
        try {
            AuditLog::create([
                'user_id'=>auth()->id(),
                'action'=>$action,
                'module'=>$module,
                'subject_type'=>$subject ? get_class($subject) : null,
                'subject_id'=>$subject->id ?? null,
                'old_values'=>$old ?: null,
                'new_values'=>$new ?: null,
                'ip_address'=>request()?->ip(),
                'note'=>$note,
            ]);
        } catch (\Throwable $e) { }
    }
}
