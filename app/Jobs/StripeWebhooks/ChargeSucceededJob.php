<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\Order;
use App\Models\User;
use App\Notifications\SendOrderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class ChargeSucceededJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var WebhookCall */
    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        $charge = $this->webhookCall->payload['data']['object'];
        $user = User::query()->where('stripe_id', $charge['customer'])->first();
        if (!$user) {
            return;
        }

        $order = Order::query()->where('user_id', $user->id)->whereNull('payed_at')->latest()->first();
        if (!$order) {
            return;
        }

        $order->update(['payed_at' => now()]);
        $user->notify(new SendOrderNotification());
        $order->update(['delivered_at' => now()]);
    }
}
