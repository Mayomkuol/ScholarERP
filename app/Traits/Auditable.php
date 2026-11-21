<?php

namespace App\Traits;

use App\Services\AuditLogger;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLogger::created(
                $model->getAuditModule(),
                $model,
                $model->getAuditDescription('created')
            );
        });

        static::updating(function ($model) {
            $model->auditOldValues = $model->getOriginal();
        });

        static::updated(function ($model) {
            AuditLogger::updated(
                $model->getAuditModule(),
                $model,
                $model->auditOldValues ?? [],
                $model->getAuditDescription('updated')
            );
        });

        static::deleting(function ($model) {
            AuditLogger::deleted(
                $model->getAuditModule(),
                $model,
                $model->getAuditDescription('deleted')
            );
        });
    }

    public function getAuditModule(): string
    {
        return class_basename($this);
    }

    public function getAuditDescription(string $action): string
    {
        $name = $this->getAuditName();

        return ucfirst($action) . ' ' .
            strtolower($this->getAuditModule()) .
            ($name ? ': ' . $name : '');
    }

    public function getAuditName(): ?string
    {
        return $this->name
            ?? $this->full_name
            ?? $this->title
            ?? $this->code
            ?? null;
    }
}