<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'pa_lv',
        'atr_lv',
        'pa_bus',
        'coverage_pengantaran',
        'fuel_ratio_bus',
        'fuel_ratio_lv',
        'unit_ready_lv',
        'unit_breakdown_lv',
        'unit_ready_bus',
        'unit_breakdown_bus',
        'notes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'pa_lv' => 'decimal:2',
            'atr_lv' => 'decimal:2',
            'pa_bus' => 'decimal:2',
            'coverage_pengantaran' => 'decimal:2',
            'fuel_ratio_bus' => 'decimal:2',
            'fuel_ratio_lv' => 'decimal:2',
            'unit_ready_lv' => 'integer',
            'unit_breakdown_lv' => 'integer',
            'unit_ready_bus' => 'integer',
            'unit_breakdown_bus' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
