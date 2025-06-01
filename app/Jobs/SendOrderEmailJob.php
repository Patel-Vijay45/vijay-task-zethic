<?php

namespace App\Jobs;


use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SendOrderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = \App\Models\Order::with(['user', 'items', 'address'])->findOrFail($this->orderId);

        Log::info('SendOrderEmailJob started for order: ' . $order->id);


        try {
            // Generate invoice
            $pdf = Pdf::loadView('invoices.order', compact('order'));
            $fileName = 'invoices/order_' . $order->id . '.pdf';
            $order->invoice = Storage::url('public/' . $fileName);
            $order->save();
            Storage::put('public/' . $fileName, $pdf->output());

            // Send email notification
            // Notification::route('mail', $order->user->email)
            //     ->notify(new OrderPlacedNotification($order, $fileName));
            $order->user->notify(new OrderPlacedNotification($order, $fileName));
            Log::info('SendOrderEmailJob complted for order: ' . $order->id);
        } catch (\Exception $e) {
            // Log::error('PDF generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
