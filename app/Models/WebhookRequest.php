<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class WebhookRequest extends Model
{
    /** @use HasFactory<\Database\Factories\WebhookRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method',
        'path',
        'query',
        'headers',
        'body',
        'ip_address',
        'content_type',
        'received_at',
    ];


    // protected function casts() :array
    // {
    //     // return [
    //     //     'query' => 'array',
    //     //     'headers' => 'array',
    //     //     'body' => 'array',
    //     //     'received_at' => 'datetime'
    //     // ];
    // }


    public function webhookEndpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoints::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function delivery(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }

    // public function destination
}
