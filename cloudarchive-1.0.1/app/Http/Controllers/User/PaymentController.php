<?php

namespace App\Http\Controllers\User;

use App\Traits\InvoiceGeneratorTrait;
use App\Http\Controllers\Controller;
use App\Events\PaymentReferrerBonus;
use App\Services\PaymentPlatformResolverService;
use App\Events\PaymentProcessed;
use Illuminate\Http\Request;
use App\Models\PaymentPlatform;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;


class PaymentController extends Controller
{   
    use InvoiceGeneratorTrait;

    protected $paymentPlatformResolver;

    
    public function __construct(PaymentPlatformResolverService $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request, Plan $id)
    {
        $rules = [
            'payment_platform' => ['required', 'exists:payment_platforms,id'],
        ];

        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);

        session()->put('subscriptionPlatformID', $request->payment_platform);
        session()->put('gatewayID', $request->payment_platform);
        
        return $paymentPlatform->handlePaymentSubscription($request, $id);
    }


    /**
     * Process approved subscription plan requests
     */
    public function approvedSubscription(Request $request)
    {   
        if (session()->has('subscriptionPlatformID')) {
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->get('subscriptionPlatformID'));

            if (session()->has('subscriptionID')) {
                $subscriptionID = session()->get('subscriptionID');
            }

            if ($paymentPlatform->validateSubscriptions($request)) {

                $plan = Plan::where('id', $request->plan_id)->firstOrFail();
                $user = $request->user();

                $gateway_id = session()->get('gatewayID');
                $gateway = PaymentPlatform::where('id', $gateway_id)->firstOrFail();
                $duration = $plan->payment_frequency;
                $days = ($duration == 'monthly') ? 30 : 365;

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'status' => 'Active',
                    'created_at' => now(),
                    'gateway' => $gateway->name,
                    'storage_total' => $plan->storage_total,
                    'subscription_id' => $subscriptionID,
                    'active_until' => Carbon::now()->addDays($days),
                ]);       

                // Only for Paystack
                if ($gateway_id == 4) {
                    $reference = $paymentPlatform->addPaystackFields($request->reference, $subscription->id);
                }

                session()->forget('gatewayID');

                $this->registerSubscriptionPayment($plan, $user, $subscriptionID, $gateway->name);               
                $order_id = $subscriptionID;

                return view('user.balance.plans.success', compact('plan', 'order_id'));
            }
        }

        return redirect()->back()->with('error', 'There was an error while checking your subscription. Please try again');
    }


    /**
     * Process approved razorpay subscription plan requests
     */
    public function approvedRazorpaySubscription(Request $request)
    {   
        if (session()->has('subscriptionPlatformID')) {
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->get('subscriptionPlatformID'));

            if (session()->has('subscriptionID')) {
                $subscriptionID = session()->get('subscriptionID');
            }

            if ($paymentPlatform->validateSubscriptions($request)) {

                $plan = Plan::where('id', $request->plan_id)->firstOrFail();

                $gateway_id = session()->get('gatewayID');
                $gateway = PaymentPlatform::where('id', $gateway_id)->firstOrFail();
                $duration = $plan->payment_frequency;
                $days = ($duration == 'monthly') ? 30 : 365;

                $subscription = Subscription::create([
                    'user_id' => auth()->user()->id,
                    'plan_id' => $plan->id,
                    'status' => 'Active',
                    'created_at' => now(),
                    'gateway' => $gateway->name,
                    'storage_total' => $plan->storage_total,
                    'subscription_id' => $subscriptionID,
                    'active_until' => Carbon::now()->addDays($days),
                ]);       

                session()->forget('gatewayID');

                $this->registerSubscriptionPayment($plan, auth()->user(), $subscriptionID, $gateway->name);               
                $order_id = $subscriptionID;

                return view('user.balance.plans.success', compact('plan', 'order_id'));
            }
        }

        return redirect()->route('user.subscriptions')->with('error', 'There was an error with payment verification. Please try again or contact support.');
    }


    /**
     * Process cancelled subscription plan requests
     */
    public function cancelledSubscription()
    {
        return redirect()->route('user.subscriptions')->with('error', 'You cancelled the payment process. Would like to try again?');
    }


    /**
     * Register subscription payment in DB
     */
    private function registerSubscriptionPayment(Plan $plan, User $user, $subscriptionID, $gateway)
    {
        $tax_value = (config('payment.payment_tax') > 0) ? $plan->price * config('payment.payment_tax') / 100 : 0;
        $total_price = $tax_value + $plan->price;

        if (config('payment.referral.payment.enabled') == 'on') {
            if (config('payment.referral.payment.policy') == 'first') {
                if (Payment::where('user_id', $user->id)->where('status', 'completed')->exists()) {
                    /** User already has at least 1 payment */
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $subscriptionID, $total_price, $gateway));
                }
            } else {
                event(new PaymentReferrerBonus(auth()->user(), $subscriptionID, $total_price, $gateway));
            }
        }

        $record_payment = new Payment();
        $record_payment->user_id = $user->id;
        $record_payment->plan_id = $plan->id;
        $record_payment->order_id = $subscriptionID;
        $record_payment->plan_name = $plan->plan_name;
        $record_payment->price = $total_price;
        $record_payment->currency = $plan->currency;
        $record_payment->gateway = $gateway;
        $record_payment->frequency = $plan->payment_frequency;
        $record_payment->status = 'completed';
        $record_payment->storage_size = $plan->storage_total;
        $record_payment->save();
        
        $group = ($user->hasRole('admin'))? 'admin' : 'subscriber';

        $user = User::where('id', $user->id)->first();
        $user->syncRoles($group);    
        $user->group = $group;
        $user->plan_id = $plan->id;
        $user->storage_total = $plan->storage_total;
        $user->save();       

        event(new PaymentProcessed(auth()->user()));
   
    }   
    
    
    /**
     * Generate Invoice after payment
     */
    public function generatePaymentInvoice($order_id)
    {              
        $this->generateInvoice($order_id);
    }


    /**
     * Bank Transfer Invoice
     */
    public function bankTransferPaymentInvoice($order_id)
    {
        $this->bankTransferInvoice($order_id);
    }


    /**
     * Show invoice for past payments
     */
    public function showPaymentInvoice(Payment $id)
    {   
        if ($id->gateway == 'BankTransfer' && $id->status != 'completed') {
            $this->bankTransferInvoice($id->order_id);
        } else {          
            $this->showInvoice($id);
        }
    }


    /**
     * Cancel active subscription
     */
    public function stopSubscription(Request $request)
    {   
        if ($request->ajax()) {

            $id = Subscription::where('id', request('id'))->firstOrFail();  

            if ($id->status == 'Cancelled') {
                return redirect()->back()->with('success', 'This subscription is already cancelled');
            } elseif ($id->status == 'Suspended') {
                return redirect()->back()->with('error', 'Subscription has been suspended due to failed renewal payment');
            } elseif ($id->status == 'Expired') {
                return redirect()->back()->with('error', 'Subscription has been expired, please create a new one');
            }
            
            switch ($id->gateway) {
                case 'PayPal':
                    $platformID = 1;
                    break;
                case 'Stripe':
                    $platformID = 2;
                    break;
                case 'BankTransfer':
                    $platformID = 3;
                    break;
                case 'Paystack':
                    $platformID = 4;
                    break;
                case 'Razorpay':
                    $platformID = 5;
                    break;
                default:
                    $platformID = 1;
                    break;
            }


            if ($id->gateway == 'PayPal' || $id->gateway == 'Stripe' || $id->gateway == 'Paystack' || $id->gateway == 'Razorpay') {
                $paymentPlatform = $this->paymentPlatformResolver->resolveService($platformID);

                $status = $paymentPlatform->stopSubscription($id->subscription_id);

                if ($platformID == 2) {
                    if ($status->cancel_at) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        $this->updateUserData($id->user_id);
                    }
                } elseif ($platformID == 4) {
                    if ($status->status) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        $this->updateUserData($id->user_id);
                    }
                } elseif ($platformID == 5) {
                    if ($status->status == 'Cancelled') {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        $this->updateUserData($id->user_id);
                    }
                } else {
                    if (is_null($status)) {
                        $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        $this->updateUserData($id->user_id);
                    }
                }
            } else {
                $id->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                $this->updateUserData($id->user_id);
            }


            return response()->json('success');
        }
        
    }


    private function updateUserData($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $group = ($user->hasRole('admin')) ? 'admin' : 'user';
        if ($group == 'user') {
            $user->syncRoles($group);    
            $user->group = $group;
            $user->plan_id = null;
            $user->storage_total = config('settings.default_storage_size');
            $user->save();
        } else {
            $user->syncRoles($group);    
            $user->group = $group;
            $user->plan_id = null;
            $user->storage_tota = $user->storage_total;
            $user->save();
        }
    }

}
