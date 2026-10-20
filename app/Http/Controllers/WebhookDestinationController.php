<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebhookDestinationRequest;
use App\Http\Resources\WebhookDestinationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->webhookDestinations()->with(['webhookEndpoint']);

        if ($request->filled('is_active')) {
            $is_active = $request->is_active;

            $query->where('is_active', $is_active);
        }

        // if($request->filled('webhook_key')) {
        //     $webhook_key = $request->webhook_key;

        //     $query->where('webhook_endpoints.webhook_key', $webhook_key);
        // }


        $destinations = $query->latest()->paginate(10);


        return response()->json([
            'status' => true,
            'data' => WebhookDestinationResource::collection($destinations),
            'pagination' => [
                'current_page' => $destinations->currentPage(),
                'next_page' => $destinations->nextPageUrl(),
                'previous_page' => $destinations->previousPageUrl(),
                'total' => $destinations->total()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebhookDestinationRequest $request)
    {

        try {
            $webhookDestination = $request->user()->webhookDestinations()->create([
                'target_url' => $request->target_url,
                'secret_key' => $request->secret_key ?? Str::random(32),
                'webhook_endpoint_id' => $request->webhook_endpoint_id
            ]);

            $webhookDestination->load('webhookEndpoint');

            return response()->json([
                'status' => true,
                'message' => 'Webhook Destination created successfully',
                'destination' => new WebhookDestinationResource($webhookDestination)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to create new webhook destination ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        try {
            $webhookDestination = $request->user()->webhookDestinations()->findOrFail($id);

            $webhookDestination->load('webhookEndpoint');

            return response()->json([
                'status' => true,
                'message' => 'Webhook Destination created successfully',
                'destination' => new WebhookDestinationResource($webhookDestination)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'target_url' => 'nullable|string|url',
                'secret_key' => 'nullable|string|min:8',
                'is_active' => 'nullable|boolean'
            ]);

            $webhookDestination = $request->user()->webhookDestinations()->findOrFail($id);

            // if(empty($validated['target_url']) && empty($validated['string']) && empty($validated['is_active'])) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'You must provide a new target_url or secret_key to perform an update'
            //     ]);
            // }

            $webhookDestination->update($validated);

            $webhookDestination->load('webhookEndpoint');

            return response()->json([
                'status' => true,
                'message' => 'Destination updated successfully',
                'destination' => new WebhookDestinationResource($webhookDestination)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to update destination ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {

        try {
            $webhookDestination = $request->user()->webhookDestinations()->findOrFail($id);

            if ($webhookDestination->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please make sure the target url is not active.'
                ]);
            }

            $webhookDestination->delete();

            return response()->json([
                'status' => true,
                'message' => 'Destination deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to delete destination ' . $e->getMessage()
            ]);
        }
    }
}
