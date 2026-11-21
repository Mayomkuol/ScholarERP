<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Fields that should never be stored in audit logs.
     */
    protected static array $sensitiveFields = [
        'password',
        'password_confirmation',
        'remember_token',
        'api_token',
        'access_token',
        'refresh_token',
        'token',
        'secret',

        // Student / parent sensitive information
        'phone',
        'email',
        'address',
        'birth_certificate',
        'photo',

        'parent_name',
        'parent_phone',
        'parent_email',

        'father_name',
        'father_phone',

        'mother_name',
        'mother_phone',

        'guardian_name',
        'guardian_phone',
        'guardian_relationship',

        'medical_conditions',
        'allergies',
    ];

    /**
     * Record an audit activity.
     */
    public static function log(
        string $action,
        string $module,
        ?string $description = null,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),

            'action' => $action,

            'module' => $module,

            'auditable_type' => $auditable
                ? get_class($auditable)
                : null,

            'auditable_id' => $auditable?->getKey(),

            'description' => $description,

            'old_values' => self::cleanValues($oldValues),

            'new_values' => self::cleanValues($newValues),

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Record a created model.
     */
    public static function created(
        string $module,
        Model $model,
        ?string $description = null
    ): AuditLog {
        return self::log(
            'created',
            $module,
            $description,
            $model,
            null,
            $model->getAttributes()
        );
    }

    /**
     * Record an updated model.
     */
    public static function updated(
        string $module,
        Model $model,
        array $oldValues,
        ?string $description = null
    ): AuditLog {
        return self::log(
            'updated',
            $module,
            $description,
            $model,
            $oldValues,
            $model->getChanges()
        );
    }

    /**
     * Record a deleted model.
     */
    public static function deleted(
        string $module,
        Model $model,
        ?string $description = null
    ): AuditLog {
        return self::log(
            'deleted',
            $module,
            $description,
            $model,
            $model->getAttributes(),
            null
        );
    }

    /**
     * Record a custom system action.
     */
    public static function action(
        string $action,
        string $module,
        ?string $description = null,
        ?Model $model = null
    ): AuditLog {
        return self::log(
            $action,
            $module,
            $description,
            $model
        );
    }

    /**
     * Remove sensitive information before saving.
     */
    protected static function cleanValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach (self::$sensitiveFields as $field) {
            unset($values[$field]);
        }

        return $values;
    }
}