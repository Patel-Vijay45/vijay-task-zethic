<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\SendOrderEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        SendOrderEmailJob::dispatch($event->order->id);
    }
}
