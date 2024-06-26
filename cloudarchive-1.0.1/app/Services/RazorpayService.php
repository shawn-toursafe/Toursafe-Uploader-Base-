<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use App\Events\PaymentReferrerBonus;
use Illuminate\Http\Request;
use App\Services\Statistics\UserService;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;


class RazorpayService 
{
    use ConsumesExternalServiceTrait;

    protected $baseURI;
    protected $keyID;
    protected $keySecret;
    private $api;

    /**
     * Paypal payment processing, unless you are familiar with 
     * Paypal's REST API, we recommend not to modify core payment processing functionalities here.
     * Part that are writing data to the database can be edited as you prefer.
     */
    public function __construct()
    {
        $this->api = new UserService();

        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }

        $this->baseURI = config('services.razorpay.base_uri');
        $this->keyID = config('services.razorpay.key_id');
        $this->keySecret = config('services.razorpay.key_secret');
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

        $credentials = base64_encode("{$this->keyID}:{$this->keySecret}");

        return "Basic {$credentials}";
    }


    public function handlePaymentSubscription(Request $request, Plan $id)
    {   
        if (!$id->razorpay_gateway_plan_id) {
            return redirect()->back()->with('error', 'Razorpay plan id is not set. Please contact the support team.');
        } 

        try {

            $price = intval($id->price * 100);
            $order = $this->createSubscription($id, $request->razorpay_email, $price);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Razorpay authentication error, verify your razorpay settings first. ' . $e->getMessage());
        }
        

        if ($order->status == 'created') {        

            $name = $request->razorpay_name;
            $email = $request->razorpay_email;
            $amount = '';
            $currency = '';
            session()->put('subscriptionID', $order->id);

            return view('user.balance.plans.razorpay-checkout', compact('order', 'id', 'name', 'email', 'amount', 'currency'));
        } else {
            return redirect()->back()->with('error', 'There was an error with Razorpay connection, please try again.');
        }

    }


    public function handleApproval(Request $request)
    {
        if (session()->has('order_id')) {
            $order_id = session()->get('order_id');
            $plan = session()->get('plan_id');        

            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_signature = $request->razorpay_signature;

            $generated_signature = hash_hmac('sha256', $order_id . "|" . $razorpay_payment_id, $this->keySecret);

            if ($generated_signature == $razorpay_signature) {
                $amount = session()->get('razorpay_price') / 100;
                $currency = session()->get('razorpay_currency');

                if (config('payment.referral.payment.enabled') == 'on') {
                    if (config('payment.referral.payment.policy') == 'first') {
                        if (Payment::where('user_id', auth()->user()->id)->where('status', 'Success')->exists()) {
                            /** User already has at least 1 payment and referrer already received credit for it */
                        } else {
                            event(new PaymentReferrerBonus(auth()->user(), $order_id, $amount, 'Razorpay'));
                        }
                    } else {
                        event(new PaymentReferrerBonus(auth()->user(), $order_id, $amount, 'Razorpay'));
                    }
                }

                $record_payment = new Payment();
                $record_payment->user_id = auth()->user()->id;
                $record_payment->order_id = $order_id;
                $record_payment->plan_id = $plan->id;
                $record_payment->plan_name = $plan->plan_name;
                $record_payment->price = $amount;
                $record_payment->currency = $currency;
                $record_payment->gateway = 'Razorpay';
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

                return view('user.balance.plans.success', compact('plan', 'order_id'));

            } else {
                return redirect()->back()->with('error', 'Your payment failed the authorization, please try again or contact your bank.');
            }
            
        }

        return redirect()->back()->with('error', 'Payment was not successful, please try again');
    }


    public function createOrder($value, $currency)
    {
        return $this->makeRequest(
            'POST',
            '/v1/orders',
            [],
            [           
                'amount' => $value, 
                'currency' => $currency,
            ],            
            [],
            $isJSONRequest = true,
        );
    }



    public function createSubscription(Plan $id, $user_email, $value)
    {
        return $this->makeRequest(
            'POST',
            '/v1/subscriptions',
            [],
            [           
                'plan_id' => $id->razorpay_gateway_plan_id,
                'total_count' => 24,
                'quantity' => 1,
                'customer_notify' => 1,
            ],            
            [],
            $isJSONRequest = true,
        );

    }


    public function stopSubscription($subscriptionID)
    {
        return $this->makeRequest(
            'POST',
            '/v1/subscriptions/'. $subscriptionID . '/cancel',
            [],
            [   
                'cancel_at_cycle_end' => 0,
            ],            
            [],
            $isJSONRequest = true,
        );
    }


    public function validateSubscriptions(Request $request)
    {
        $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;
        $order_id = session()->get('subscriptionID');

        $generated_signature = hash_hmac('sha256', $razorpay_payment_id . "|" . $order_id, $this->keySecret);

        if ($generated_signature == $razorpay_signature) {
            
            session()->forget('subscriptionID');

            return true;            
        }

        return false;
    }

}