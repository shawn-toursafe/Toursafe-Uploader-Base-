<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use Illuminate\Http\Request;
use App\Services\Statistics\UserService;
use App\Events\PaymentReferrerBonus;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;

class StripeService 
{
    use ConsumesExternalServiceTrait;

    protected $baseURI;
    protected $key;
    protected $secret;
    protected $promocode;
    private $api;

    /**
     * Stripe payment processing, unless you are familiar with 
     * Stripe's REST API, we recommend not to modify core payment processing functionalities here.
     * Part that are writing data to the database can be edited as you prefer.
     */
    public function __construct()
    {
        $this->api = new UserService();

        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }

        $this->baseURI = config('services.stripe.base_uri');
        $this->key = config('services.stripe.api_key');
        $this->secret = config('services.stripe.api_secret');
    }


    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }


    public function decodeResponse($response)
    {
        return json_decode($response);
    }


    public function resolveAccessToken()
    {
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return;
        }
        
        return "Bearer {$this->secret}"; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function handlePaymentSubscription(Request $request, Plan $id)
    {
        if (!$id->stripe_gateway_plan_id) {
            return redirect()->back()->with('error', 'Stripe plan id is not set. Please contact the support team.');
        }        

        try {

            $customer = $this->createCustomer($request->user()->name, $request->user()->email, $request->payment_method);
            $subscription = $this->createSubscription($customer->id, $request->payment_method, $id);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Stripe authentication error, verify your stripe settings first. ' . $e->getMessage());
        }        

        if ($subscription->status == 'active') {
            session()->put('subscriptionID', $subscription->id);

            return redirect()->route('user.payments.subscription.approved', ['plan_id' => $id->id, 'subscription_id' => $subscription->id] );
        }
        
        $paymentIntent = $subscription->latest_invoice->payment_intent;


        if ($paymentIntent->status === 'requires_action') {
            $clientSecret = $paymentIntent->client_secret;

            session()->put('subscriptionID', $subscription->id);

            return view('user.balance.subscriptions.3d-secure-subscription')->with([
                'clientSecret' => $clientSecret,
                'plan_id' => $id->id,
                'paymentMethod' => $request->payment_method,
                'subscription_id' => $subscription->id
            ]);
        }

        return redirect()->route('user.subscriptions')->with('error', 'There was an error while activating your subscription. Please try again');
    }


    public function handleApproval()
    {
        if (session()->has('paymentIntentID')) {
            $paymentIntentID = session()->get('paymentIntentID');
            $plan = session()->get('plan_id');

            try {
                $confirmation = $this->confirmPayment($paymentIntentID);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Stipe payment confirmation error. Verify your stripe merchant account settings. ' . $e->getMessage());
            }
            

            if ($confirmation->status === 'requires_action') {
                $clientSecret = $confirmation->client_secret;

                return view('user.balance.subscriptions.3d-secure')->with([
                    'clientSecret' => $clientSecret
                ]);
            }

            if ($confirmation->status === 'succeeded') {
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount / $this->resolveFactor($currency);
            }

            if (config('payment.referral.payment.enabled') == 'on') {
                if (config('payment.referral.payment.policy') == 'first') {
                    if (Payment::where('user_id', auth()->user()->id)->where('status', 'Success')->exists()) {
                        /** User already has at least 1 payment */
                    } else {
                        event(new PaymentReferrerBonus(auth()->user(), $paymentIntentID, $amount, 'Stripe'));
                    }
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $paymentIntentID, $amount, 'Stripe'));
                }
            }

            $record_payment = new Payment();
            $record_payment->user_id = auth()->user()->id;
            $record_payment->order_id = $paymentIntentID;
            $record_payment->plan_id = $plan->id;
            $record_payment->plan_name = $plan->plan_name;
            $record_payment->price = $amount;
            $record_payment->currency = $currency;
            $record_payment->gateway = 'Stripe';
            $record_payment->frequency = $plan->payment_frequency;
            $record_payment->status = 'completed';
            $record_payment->storage_size = $plan->storage_total;
            $record_payment->save();

            $group = (auth()->user()->hasRole('admin'))? 'admin' : 'subscriber';

            $user = User::where('id',auth()->user()->id)->first();
            $user->syncRoles($group);    
            $user->group = $group;
            $user->storage_total = $plan->storage_total;
            $user->save();

            event(new PaymentProcessed(auth()->user()));
            $order_id = $paymentIntentID;

            return view('user.balance.plans.success', compact('plan', 'order_id'));
        }

        return redirect()->back()->with('error', 'Payment was not successful, please try again');
    }


    public function createIntent($value, $currency, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',
            ],
        );
    }


    public function confirmPayment($paymentIntentID)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentID}/confirm",
        );
    }


    public function createCustomer($name, $email, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/customers',
            [],
            [
                'name' => $name,
                'email' => $email,
                'payment_method' => $paymentMethod,
            ],
        );
    }


    public function createSubscription($customerID, $paymentMethod, Plan $id)
    {
        return $this->makeRequest(
            'POST',
            '/v1/subscriptions',
            [],
            [
                'customer' => $customerID,
                'items' => [
                    ['price' => $id->stripe_gateway_plan_id],
                ],
                'default_payment_method' => $paymentMethod,
                'expand' => ['latest_invoice.payment_intent'],
            ],
        );
    }


    public function stopSubscription($subscriptionID)
    {
        return $this->makeRequest(
            'POST',
            '/v1/subscriptions/'. $subscriptionID,
            [],
            [
                'cancel_at_period_end' => 'true',
            ],
        );
    }


    public function validateSubscriptions(Request $request)
    {
        if (session()->has('subscriptionID')) {
            $subscriptionID = session()->get('subscriptionID');

            session()->forget('subscriptionID');

            return $request->subscription_id == $subscriptionID;
        }

        return false;
    }


    public function resolveFactor($currency)
    {
        $zeroDecimanCurrency = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimanCurrency)) {
            return 1;
        }

        return 100;
    }
}