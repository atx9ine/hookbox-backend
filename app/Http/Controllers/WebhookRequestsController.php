<?php

namespace App\Http\Controllers;

use App\Events\ForwardToDestination;
use App\Events\WebhookReceived;
use App\Http\Requests\StoreWebhookRequestsRequest;
use App\Http\Resources\WebhookRequestsResource;
use App\Models\WebhookDestination;
use App\Models\WebhookEndpoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookRequestsController extends Controller
{
    public function receive(Request $request, string $webhook_key)
    {
        $endpoint = WebhookEndpoints::where('webhook_key', $webhook_key)->firstOrFail();
        $destination = WebhookDestination::where('webhook_endpoint_id', $endpoint->id)->firstOrFail();

        if ($endpoint->status !== 'alive') {
            abort(410, 'Endpoint has been disabled');
        }

        try {

            $data = [
                'user_id' => $endpoint->user_id,
                'method' =>  $request->method(),
                'path' => (string) $request->path(),
                'query' => json_encode($request->query()),
                'headers' => json_encode($request->headers->all()),
                'body' => $request->getContent(),
                'ip_address' => $request->ip(),
                'received_at' => now()
            ];

            WebhookReceived::dispatch($endpoint, $data);
           $externalResponse = ForwardToDestination::dispatch($destination, $data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'webhook received',
            'data' => $externalResponse
        ], 201);
    }


    public function getAllRequests(Request $request)
    {
        $query = $request->user()
            ->webhookRequests()
            ->with('webhookEndpoint');


        $webhookRequests = $query->paginate(10);

        return response()->json([
            'status' => true,
            'data' => WebhookRequestsResource::collection($webhookRequests),
            'pagination' => [
                'current_page' => $webhookRequests->currentPage(),
                'next_page' => $webhookRequests->nextPageUrl(),
                'previous_page' => $webhookRequests->previousPageUrl(),
                'total' => $webhookRequests->total()
            ]
        ]);
    }


    public function getRequest(Request $request)
    {
        $webhookRequest = $request->user()->webhookRequests()
            ->findOrFail($request->id)
            ->with('webhookEndpoint');

        return response()->json([
            'status' => true,
            'data' => new WebhookRequestsResource($webhookRequest)
        ]);
    }
}
