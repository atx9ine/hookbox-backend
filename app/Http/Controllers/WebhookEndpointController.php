<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebhookEndpointRequest;
use App\Http\Resources\WebhookEndpointResource;
use App\Models\WebhookEndpoints;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookEndpointController extends Controller
{


    public function getAllEndpoints(Request $request)
    {
        $query = $request->user()
            ->webhookEndpoints()
            ->with(['webhookDestinations'])
            ->withCount('webhookDestinations');

        // ** search functionality

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;

            $query->where('status', $status);
        }

        if ($request->filled('webhook_key')) {
            $webhook_key = $request->webhook_key;
            $query->where('webhook_key', (string) $webhook_key);
        }

        $endpoints = $query->latest()->paginate(10);


        return response()->json([
            'status' => true,
            'data' => WebhookEndpointResource::collection($endpoints),
            'pagination' => [
                'current_page' => $endpoints->currentPage(),
                'next_page' => $endpoints->nextPageUrl(),
                'previous_page' => $endpoints->previousPageUrl(),
                'total' => $endpoints->total()
            ]
        ]);
    }


    public function createEndpoint(StoreWebhookEndpointRequest $request)
    {

      $webhook_endpoint =  DB::transaction(function () use ($request) {
            $webhook_endpoint = WebhookEndpoints::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'webhook_key' => Str::uuid(),
                'status' => 'alive'
            ]);

            if ($request->filled('target_url')) {
                $webhook_endpoint->webhookDestinations()->create([
                    'user_id' => $request->user()->id,
                    'target_url' => $request->target_url,
                    'secret_key' => $request->secret_key ?? Str::random(32),
                    'is_active' => true
                ]);
            }

            return $webhook_endpoint->load('webhookDestinations');
        });
        return response()->json([
            'status' => true,
            'message' => 'New Webhook endpoint created',
            'webhook' => new WebhookEndpointResource($webhook_endpoint)
        ], 201);
    }


    public function getEndpoint(Request $request)
    {
        try {
            $endpoint = $request->user()->webhookEndpoints()->findOrFail($request->id);

            $endpoint->load('webhookDestinations');

            return response()->json([
                'status' => true,
                'message' => 'endpoint fetched successfully',
                'endpoint' => new WebhookEndpointResource($endpoint)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'unable to fetch endpoint' . $e->getMessage()
            ]);
        }
    }


    public function updateEndpoint(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'status' => 'nullable|in:alive,dead'
            ]);

            $endpoint = $request->user()->webhookEndpoints()->findOrFail($request->id);

            $endpoint->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'webhook updated successfully',
                'endpoint' => new WebhookEndpointResource($endpoint)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'unable tp update webhook endpoint' . $e->getMessage(),
            ]);
        }
    }

    public function deleteEndpoint(Request $request)
    {

        try {
            $endpoint = $request->user()->webhookEndpoints()->findOrFail($request->id);

            $endpoint->delete();
            return response()->json([
                'status' => true,
                'message' => 'endpoint deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'unable to delete endpoint ' . $e->getMessage()
            ]);
        }
    }

    public function killEndpoint(Request $request)
    {

        try {
            $endpoint = $request->user()->webhookEndpoints()->where('webhook_key', $request->webhook_key)->firstOrFail();

            if ($endpoint->status === 'dead') {
                return response()->json([
                    'status' => false,
                    'message' => 'Webhook endpoint is already dead'
                ], 501);
            }

            $endpoint->kill();

            return response()->json([
                'status' => true,
                'message' => 'Webhook endpoint has been killed',

            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reviveEndpoint(Request $request)
    {
        try {
            $endpoint = $request->user()->webhookEndpoints()->where('webhook_key', $request->webhook_key)->firstOrFail();

            if ($endpoint->status === 'alive') {
                return response()->json([
                    'status' => false,
                    'message' => 'Webhook endpoint is already alive'
                ], 501);
            }

            $endpoint->revive();


            return response()->json([
                'status' => true,
                'message' => 'Successfully revived webhook endpoint'
            ], 203);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
