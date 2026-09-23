<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            logger()->error('Webhook inválido: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (WebhookEvent::where('stripe_event_id', $event->id)->exists()) {
            return response()->json(['status' => 'already processed']);
        }

        WebhookEvent::create([
            'stripe_event_id' => $event->id,
            'type' => $event->type,
        ]);

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            default => logger()->info('Evento não tratado: ' . $event->type),
        };

        return response()->json(['status' => 'success']);
    }

    private function handleCheckoutCompleted($session): void
    {
        $user = User::where('stripe_customer_id', $session->customer)->first();

        if (! $user) {
            logger()->error('Usuário não encontrado para customer: ' . $session->customer);
            return;
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripeSubscription = \Stripe\Subscription::retrieve($session->subscription);

        $plan = Plan::where('stripe_price_id', $stripeSubscription->items->data[0]->price->id)->first();

        Subscription::updateOrCreate(
            ['stripe_subscription_id' => $stripeSubscription->id],
            [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => $stripeSubscription->status,
                'current_period_end' => \Carbon\Carbon::createFromTimestamp(
                    $stripeSubscription->items->data[0]->current_period_end
                ),
            ]
        );
    }

    private function handleSubscriptionUpdated($stripeSubscription): void
    {
        Subscription::where('stripe_subscription_id', $stripeSubscription->id)->update([
            'status' => $stripeSubscription->status,
            'current_period_end' => \Carbon\Carbon::createFromTimestamp(
                $stripeSubscription->items->data[0]->current_period_end
            ),
        ]);
    }

    private function handleSubscriptionDeleted($stripeSubscription): void
    {
        Subscription::where('stripe_subscription_id', $stripeSubscription->id)->update([
            'status' => 'canceled',
        ]);
    }
}