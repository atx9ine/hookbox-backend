<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookEndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'webhook_key' => $this->webhook_key,
            'status' => $this->status,
            'hook' => '/hooks/' . $this->webhook_key,
            'destinations' =>  WebhookDestinationResource::collection($this->whenLoaded('webhookDestinations')),
            'total_destinations' => $this->whenCounted('webhookDestinations'),
            'total_requests' => $this->whenCounted('webhookRequests'),
        ];
    }
}
