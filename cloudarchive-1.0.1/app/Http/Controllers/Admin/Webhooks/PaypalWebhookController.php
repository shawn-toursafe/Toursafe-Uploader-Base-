<?php

namespace App\Http\Controllers\Admin\Webhooks;

use App\Traits\ConsumesExternalServiceTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\PaymentProcessed;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use App\Models\Payment;
use Carbon\Carbon;

class PaypalWebhookController extends Controller
{
    use ConsumesExternalServiceTrait;

    protected $baseURI;
    protected $clientID;
    protected $clientSecret;

    /**
     * Paypal Webhook processing, unless you are familiar with 
     * Paypal's REST API, we recommend not to modify it
     */
    public function __construct()
    {
        $this->baseURI = config('services.paypal.base_uri');
        $this->clientID = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }


    public function resolveAccessToken()
    {
        $credentials = base64_encode("{$this->clientID}:{$this->clientSecret}");

        return "Basic {$credentials}";
    }
    

    public function handlePaypal(Request $request)
    {
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        $webhook_body = json_decode(file_get_contents('php://input'));


        $status = $this->makeRequest(
            'POST',
            '/v1/notifications/verify-webhook-signature',
            [],
            [   
                'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'],
                'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'],
                'cert_url' => $headers['PAYPAL-CERT-URL'],
                'auth_algo' => $headers['PAYPAL-AUTH-ALGO'],
                'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'],
                'webhook_id' => config('services.paypal.webhook_id'),
                'webhook_event' => $webhook_body
            ],            
            [],
            $isJSONRequest = true,
        );

        $result = json_decode($status, true);

        if ($result['verification_status'] == "SUCCESS") {

            switch ($webhook_body->event_type) {
                case 'BILLING.SUBSCRIPTION.CANCELLED':
                        $subscription = Subscription::where('subscription_id', $webhook_body->resource->id)->firstOrFail();
                        $subscription->update(['status'=>'Cancelled', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);

                        $this->updateUserData($subscription->user_id);
                        
                        break;
                
                case 'BILLING.SUBSCRIPTION.SUSPENDED':
                        $subscription = Subscription::where('subscription_id', $webhook_body->resource->id)->firstOrFail();
                        $subscription->update(['status'=>'Expired', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        
                        $this->updateUserData($subscription->user_id);

                        break;

                case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED':
                        $subscription = Subscription::where('subscription_id', $webhook_body->resource->id)->firstOrFail();
                        $subscription->update(['status'=>'Expired', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        
                        $this->updateUserData($subscription->user_id);

                        break;

                case 'BILLING.SUBSCRIPTION.EXPIRED':
                        $subscription = Subscription::where('subscription_id', $webhook_body->resource->id)->firstOrFail();
                        $subscription->update(['status'=>'Expired', 'active_until' => Carbon::createFromFormat('Y-m-d H:i:s', now())->endOfMonth()]);
                        
                        $this->updateUserData($subscription->user_id);

                        break;

                case 'BILLING.SUBSCRIPTION.APPROVED':
                    $subscription = Subscription::where('subscription_id', $webhook_body->resource->id)->firstOrFail();
                    $plan = Plan::where('id', $subscription->plan_id)->firstOrFail();
                    $duration = $plan->payment_frequency;
                    $days = ($duration == 'monthly') ? 30 : 365;

                    $subscription->update(['status'=>'Active', 'active_until' => Carbon::now()->addDays($days)]);
                    
                    $user = User::where('id', $subscription->user_id)->firstOrFail();

                    $tax_value = (config('payment.payment_tax') > 0) ? $plan->price * config('payment.payment_tax') / 100 : 0;
                    $total_price = $tax_value + $plan->price;

                    $record_payment = new Payment();
                    $record_payment->user_id = $user->id;
                    $record_payment->plan_id = $plan->id;
                    $record_payment->order_id = $subscription->plan_id;
                    $record_payment->plan_name = $plan->plan_name;
                    $record_payment->price = $total_price;
                    $record_payment->currency = $plan->currency;
                    $record_payment->gateway = 'Paystack';
                    $record_payment->frequency = $plan->payment_frequency;
                    $record_payment->status = 'completed';
                    $record_payment->storage_size = $plan->storage_total;
                    $record_payment->save();
                    
                    $group = ($user->hasRole('admin')) ? 'admin' : 'subscriber';

                    $user->syncRoles($group);    
                    $user->group = $group;
                    $user->plan_id = $subscription->plan_id;
                    $user->storage_total = $subscription->storage_total;
                    $user->save();       

                    event(new PaymentProcessed($user));

                    break;
            }
        }
    }


    private function updateUserData($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $group = ($user->hasRole('admin'))? 'admin' : 'user';

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
            $user->save();
        }
    }

}
