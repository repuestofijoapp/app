<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BlacklistIp extends Model
{
    protected $fillable = [
        'ip_address',
        'reason',
        'expires_at',
        'blocked_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /** Only currently active blocks */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /** Is this block still active? */
    public function isActive(): bool
    {
        return is_null($this->expires_at) || $this->expires_at->isFuture();
    }

    /** Relation: admin who added the block */
    public function blockedBy()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
