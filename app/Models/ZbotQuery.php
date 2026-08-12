<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZbotQuery extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'pedido_id',
        'provider_id',
        'chat_id',
        'message_id',
        'status',
        'reminders_sent',
        'current_step',
        'items_json',
        'price',
        'response_text',
        'expires_at',
        'confirmation_token',
        'items_confirmed_json',
    ];

    protected $casts = [
        'items_json' => 'array',
        'items_confirmed_json' => 'array',
        'expires_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function pedido()
    {
        return $this->belongsTo(\App\Models\Pedido::class);
    }
}
