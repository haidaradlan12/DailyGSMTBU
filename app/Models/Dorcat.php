<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dorcat extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'issue_title',
        'description',
        'percentage',
        'status',
        'priority',
        'pic_name',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'percentage' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
