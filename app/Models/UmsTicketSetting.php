<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmsTicketSetting extends Model
{
    use HasFactory;

    protected $table = 'umsticket_settings';

    protected $fillable = [
        'endpoint_url',
        'api_key',
        'username',
        'password',
        'auto_sync',
        'last_synced_at',
        'last_sync_status',
        'last_sync_message',
    ];

    protected function casts(): array
    {
        return [
            'auto_sync' => 'boolean',
            'last_synced_at' => 'datetime',
        ];
    }
}
