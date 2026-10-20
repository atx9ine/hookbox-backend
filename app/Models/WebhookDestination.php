<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class WebhookDestination extends Model
{
    /** @use HasFactory<\Database\Factories\WebhookDestinationFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'webhook_endpoint_id',
        'target_url',
        'secret_key',
        'is_active',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    public function webhookEndpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoints::class);
    }


    protected function casts()
    {
        return [
            'secret_key' => 'encrypted',
            'is_active' => 'boolean',
        ];
    }
}
