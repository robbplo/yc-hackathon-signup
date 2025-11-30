<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription checkout page.
     */
    public function showCheckout(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        // If user already has an active subscription, redirect to dashboard
        if ($user->subscribed('default')) {
            return redirect()->route('dashboard');
        }

        // Get the selected plan from user
        $selectedPlan = $user->selected_plan;

        if (! $selectedPlan) {
            return redirect()->route('register');
        }

        return Inertia::render('subscription/Checkout', [
            'plan' => $selectedPlan,
        ]);
    }

    /**
     * Create a new subscription and redirect to Mollie checkout.
     */
    public function checkout(Request $request)
    {
        $user = $request->user();

        // If user already has an active subscription, redirect to dashboard
        if ($user->subscribed('default')) {
            return redirect()->route('dashboard');
        }

        $plan = $user->selected_plan;

        if (! $plan) {
            return redirect()->route('register')->withErrors([
                'plan' => 'Please select a subscription plan.',
            ]);
        }

        // Create subscription with first payment redirect
        $subscription = $user->newSubscriptionViaMollieCheckout('default', $plan);

        // Get the Mollie payment from the subscription builder
        $payment = $subscription->create();

        // Redirect to Mollie checkout page
        return redirect($payment->getCheckoutUrl());
    }

    /**
     * Handle the return from Mollie after payment.
     */
    public function afterPayment(Request $request)
    {
        $user = $request->user();

        // Check if subscription is now active
        if ($user->subscribed('default')) {
            return redirect()->route('dashboard')->with('success', 'Subscription activated successfully!');
        }

        // If payment failed or was cancelled
        return redirect()->route('subscription.checkout')->withErrors([
            'payment' => 'Payment was not completed. Please try again.',
        ]);
    }
}
