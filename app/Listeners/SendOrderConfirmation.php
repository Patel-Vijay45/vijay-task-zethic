<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\SendOrderEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmation
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::info('SendOrderConfirmation started for order: ' . $event->order->id);
        SendOrderEmailJob::dispatch($event->order->id);
        Log::info('SendOrderConfirmation complted for order: ' . $event->order->id);
    }
}
