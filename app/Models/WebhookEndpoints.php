<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebhookEndpoints extends Model
{
    /** @use HasFactory<\Database\Factories\WebhookEndpointsFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'name',
        'webhook_key',
        'status'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function webhookRequests(): HasMany
    {
        return $this->hasMany(WebhookRequest::class, 'webhook_endpoint_id');
    }


    public function webhookDestinations(): HasMany
    {
        return $this->hasMany(WebhookDestination::class, 'webhook_endpoint_id');
    }

    public function kill(): bool
    {
        return $this->update(['status' => 'dead']);
    }

    public function revive(): bool
    {
        return $this->update(['status' => 'alive']);
    }

    public function isAlive(): bool
    {
        return $this->status === 'alive';
    }
}
