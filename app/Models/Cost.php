<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'plan_amount',
        'actual_amount',
        'notes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'plan_amount' => 'decimal:2',
            'actual_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get percentage of budget realization.
     */
    public function getPercentageAttribute(): float
    {
        if ($this->plan_amount <= 0) {
            return 0.0;
        }

        return round(($this->actual_amount / $this->plan_amount) * 100, 1);
    }
}
