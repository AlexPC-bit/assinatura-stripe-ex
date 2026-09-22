<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('checkout.index', compact('plans'));
    }

    public function store(Request $request, Plan $plan)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = $request->user();

        if (! $user->stripe_customer_id) {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $session = Session::create([
            'customer' => $user->stripe_customer_id,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.index'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        return view('checkout.success');
    }
}