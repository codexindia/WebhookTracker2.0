<?php

namespace App\Http\Controllers;

use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function receive(Request $request, $sessionId = null)
    {
        try {
            // If no session ID in URL, try to get from headers or generate one
            if (!$sessionId) {
                $sessionId = $request->header('X-Session-ID') ?? 
                           $request->input('session_id') ?? 
                           'session_' . uniqid();
            }

            // Get event type from various sources
            $eventType = $request->header('X-Event-Type') ?? 
                        $request->input('event_type') ?? 
                        $request->input('type') ?? 
                        'webhook';

            $webhookEvent = WebhookEvent::create([
                'session_id' => $sessionId,
                'event_type' => $eventType,
                'http_method' => $request->method(),
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
                'source_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'received_at' => now(),
            ]);

            Log::info('Webhook received', [
                'session_id' => $sessionId,
                'event_id' => $webhookEvent->id,
                'method' => $request->method(),
                'event_type' => $eventType,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook received successfully',
                'session_id' => $sessionId,
                'event_id' => $webhookEvent->id,
                'method' => $request->method(),
                'timestamp' => now()->toISOString(),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'method' => $request->method(),
                'session_id' => $sessionId,
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Handle different HTTP methods for testing
     */
    public function handleAny(Request $request, $sessionId = null)
    {
        return $this->receive($request, $sessionId);
    }
}
