<?php

namespace App\Listeners;

use App\Events\ForwardToDestination;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ForwardToDestinationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }



    /**
     * Handle the event.
     */
    public function handle(ForwardToDestination $event)
    {
        $destination = $event->webhook_destination;
        $body = $event->data['body'];
        // $webhookDelivery = $event->webhook_delivery;
        $signature = hash_hmac('sha256', $body, $destination->secret_key);

        // dd(Str::isJson(json_decode($body)));

        try {
            $response = Http::withHeaders([
                'X-HookBox-Signature' => $signature,
            ])->withBody($body, 'application/json')
            ->post($destination->target_url, $body);


            // dd(is_int($response->status()));

            // typeOf($response->status());

            switch ($response->status()) {
                case 404:
                    Log::alert('Not found');

                    case 201:
                        // return response()->json([
                        //     'status' => true,
                        //     'message' => 'Data received',
                        //     'data' => $response->json()
                        // ]);

                        Log::info('Created');

                default:
                    return response()->json([
                        'message' => 'default message'
                    ]);
                    // break;
            }




        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }





        // Log::alert('something happened', [
        //     'data' => $response->body()
        // ]);



        // if ($response->status() === 201) {
        //     // abort(200);
        //     return;
        // }
    }
}
