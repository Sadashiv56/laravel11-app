<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Stripe;

class SubscriptionController extends Controller
{
    public function show()
    {
        return view('front.subscriptions.create');
    }

    public function create(Request $request)
    {
        $request->validate([
            'stripeToken' => 'required',
            'email' => 'required|email',
            'plan_id' => 'required' 
        ]);

        Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $customer = Stripe\Customer::create([
                'email' => $request->email,
                'source' => $request->stripeToken,
            ]);
            $subscription = Stripe\Subscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $request->plan_id]], 
            ]);

            Subscription::create([
                'user_id' => auth()->id(),
                'stripe_customer_id' => $customer->id,
                'stripe_subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ]);

            return redirect()->route('home')->with('success', 'Subscription created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function handleWebhook(Request $request)
    {
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }
        switch ($event['type']) {
            case 'customer.subscription.updated':
                $subscription = $event['data']['object']; // Contains a Stripe subscription object
                // Update your local subscription in the database
                Subscription::where('stripe_subscription_id', $subscription['id'])
                    ->update(['status' => $subscription['status']]);
                break;

            // Handle other event types if needed
        }

        return response('Webhook handled', 200);
    }

}
