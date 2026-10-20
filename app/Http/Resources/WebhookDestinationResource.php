<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookDestinationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'target_url' => $this->target_url,
            'secret_key' => $this->secret_key,
            'is_active' => $this->is_active,
            'webhook_endpoints' => $this->whenLoaded('webhookEndpoint')

        ];
    }
}
