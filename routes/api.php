<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebhookDestinationController;
use App\Http\Controllers\WebhookEndpointController;
use App\Http\Controllers\WebhookRequestsController;
use App\Models\WebhookDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('hooks/{webhook_key}', [WebhookRequestsController::class, 'receive']);

Route::post('/sample-destination', function (Request $request) {
    // Log::info('request', [
    //     'data' => $request
    // ]);
    return 201;
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('current', function (Request $request) {
        return $request->user();
    });


    Route::post('logout', [AuthController::class, 'logout']);


    Route::prefix('webhooks')->group(function () {
        Route::post('/create', [WebhookEndpointController::class, 'createEndpoint']);
        Route::get('/', [WebhookEndpointController::class, 'getAllEndpoints']);
        Route::get('/{id}', [WebhookEndpointController::class, 'getEndpoint']);
        Route::patch('/{id}', [WebhookEndpointController::class, 'updateEndpoint']);
        Route::delete('/{id}', [WebhookEndpointController::class, 'deleteEndpoint']);
        Route::post('/kill/{webhook_key}', [WebhookEndpointController::class, 'killEndpoint']);
        Route::post('/revive/{webhook_key}', [WebhookEndpointController::class, 'reviveEndpoint']);
    });

    Route::get('requests', [WebhookRequestsController::class, 'getAllRequests']);
    Route::get('requests/{id}', [WebhookRequestsController::class, 'getRequest']);

    Route::apiResource('destinations', WebhookDestinationController::class);
});
