<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'department',
        'category',
        'waiting_schedule',
        'in_progress',
        'completed',
        'source',
        'last_synced_at',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'waiting_schedule' => 'integer',
            'in_progress' => 'integer',
            'completed' => 'integer',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Total tickets for this row.
     */
    public function getTotalAttribute(): int
    {
        return $this->waiting_schedule + $this->in_progress + $this->completed;
    }
}
