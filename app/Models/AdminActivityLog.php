<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
        'performed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'performed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected (polymorphic).
     */
    public function model()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Get the action display name.
     */
    public function getActionDisplayAttribute(): string
    {
        return match($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'view' => 'Viewed',
            'export' => 'Exported',
            'import' => 'Imported',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get the model display name.
     */
    public function getModelDisplayAttribute(): string
    {
        if (!$this->model_type) {
            return '';
        }

        $modelName = class_basename($this->model_type);
        return ucfirst(str_replace('_', ' ', \Illuminate\Support\Str::snake($modelName)));
    }

    /**
     * Get the formatted changes for display.
     */
    public function getFormattedChangesAttribute(): array
    {
        if (!$this->changes) {
            return [];
        }

        $formatted = [];
        foreach ($this->changes as $field => $change) {
            if (is_array($change) && isset($change['old'], $change['new'])) {
                $formatted[] = [
                    'field' => ucfirst(str_replace('_', ' ', $field)),
                    'old' => $change['old'],
                    'new' => $change['new'],
                ];
            }
        }

        return $formatted;
    }

    /**
     * Log an admin activity.
     */
    public static function logActivity(
        User $user,
        string $action,
        ?Model $model = null,
        ?array $changes = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'changes' => $changes,
            'ip_address' => $ipAddress ?: request()->ip(),
            'user_agent' => $userAgent ?: request()->userAgent(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Scope a query by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query by action.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query by model type.
     */
    public function scopeByModelType($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope a query for recent activities.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('performed_at', '>=', now()->subDays($days));
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->performed_at)) {
                $log->performed_at = now();
            }
        });
    }
}