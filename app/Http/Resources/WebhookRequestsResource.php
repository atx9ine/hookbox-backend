<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookRequestsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'method' => $this->method,
            'path' => $this->path,
            'query' => json_decode($this->query),
            'headers' => json_decode($this->headers),
            'body' => json_decode($this->body),
            'ip_address' => $this->ip_address,
            'received_at' => $this->received_at,
            'endpoint' => new WebhookEndpointResource($this->whenLoaded('webhookEndpoint'))
        ];
    }
}
