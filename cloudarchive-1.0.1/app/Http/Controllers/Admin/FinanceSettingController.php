<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Http\Request;
use App\Models\PaymentPlatform;
use App\Models\Setting;


class FinanceSettingController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        }

        $bank_information = ['bank_instructions', 'bank_requisites', 'balance_instructions'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }
        
        return view('admin.finance.settings.index', compact('bank'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return redirect()->back();
        }
        
        request()->validate([
            'tax' => 'required',
            'currency' => 'required',

            'enable-paypal-subscription' => 'sometimes|required',
            'paypal_client_id' => 'required_if:enable-paypal-subscription,on',
            'paypal_client_secret' => 'required_if:enable-paypal-subscription,on',
            'paypal_base_uri' => 'required_if:enable-paypal-subscription,on',
            'paypal_webhook_uri' => 'required_if:enable-paypal-subscription,on',
            'paypal_webhook_id' => 'required_if:enable-paypal-subscription,on',

            'enable-stripe-subscription' => 'sometimes|required',
            'stripe_key' => 'required_if:enable-stripe-subscription,on',
            'stripe_secret_key' => 'required_if:enable-stripe-subscription,on',
            'stripe_base_uri' => 'required_if:enable-stripe-subscription,on',
            'stripe_webhook_uri' => 'required_if:enable-stripe-subscription,on',
            'stripe_webhook_secret' => 'required_if:enable-stripe-subscription,on',

            'enable-bank-subscription' => 'sometimes|required',
            'bank_instructions' => 'required_if:enable-bank-subscription,on',
            'bank_requisites' => 'required_if:enable-bank-subscription,on',

            'enable-paystack-subscription' => 'sometimes|required',
            'paystack_secret_key' => 'required_if:enable-paystack-subscription,on',
            'paystack_public_key' => 'required_if:enable-paystack-subscription,on',
            'paystack_base_uri' => 'required_if:enable-paystack-subscription,on',
            'paystack_secret_key' => 'required_if:enable-paystack-subscription,on',
            'paystack_webhook_uri' => 'required_if:enable-paystack-subscription,on',

            'enable-razorpay-subscription' => 'sometimes|required',
            'razorpay_key_id' => 'required_if:enable-razorpay-subscription,on',
            'razorpay_key_secret' => 'required_if:enable-razorpay-subscription,on',
            'razorpay_base_uri' => 'required_if:enable-razorpay-subscription,on',
            'razorpay_webhook_secret' => 'required_if:enable-razorpay-subscription,on',
            'razorpay_webhook_uri' => 'required_if:enable-razorpay-subscription,on',

        ]);
       

        $this->storeConfiguration('PAYMENT_TAX', request('tax'));       
        $this->storeConfiguration('DEFAULT_SYSTEM_CURRENCY', request('currency'));     
     
        if (request('currency')) {
            $newName = "'" . config('currencies.all.' . request('currency') . '.symbol') . "'";
            $this->storeWithQuotes('DEFAULT_SYSTEM_CURRENCY_SYMBOL', $newName);
        }  
        
        $this->storeConfiguration('STRIPE_SUBSCRIPTION_ENABLED', request('enable-stripe-subscription'));
        $this->storeConfiguration('STRIPE_KEY', request('stripe_key'));
        $this->storeConfiguration('STRIPE_SECRET', request('stripe_secret_key'));  
        $this->storeConfiguration('STRIPE_BASE_URI', request('stripe_base_uri'));  
        $this->storeConfiguration('STRIPE_WEBHOOK_URI', request('stripe_webhook_uri'));  
        $this->storeConfiguration('STRIPE_WEBHOOK_SECRET', request('stripe_webhook_secret'));  

        $this->storeConfiguration('PAYPAL_SUBSCRIPTION_ENABLED', request('enable-paypal-subscription'));
        $this->storeConfiguration('PAYPAL_CLIENT_ID', request('paypal_client_id'));      
        $this->storeConfiguration('PAYPAL_CLIENT_SECRET', request('paypal_client_secret'));  
        $this->storeConfiguration('PAYPAL_BASE_URI', request('paypal_base_uri'));  
        $this->storeConfiguration('PAYPAL_WEBHOOK_URI', request('paypal_webhook_uri'));  
        $this->storeConfiguration('PAYPAL_WEBHOOK_ID', request('paypal_webhook_id'));  

        $this->storeConfiguration('PAYSTACK_SUBSCRIPTION_ENABLED', request('enable-paystack-subscription'));
        $this->storeConfiguration('PAYSTACK_SECRET_KEY', request('paystack_secret_key'));
        $this->storeConfiguration('PAYSTACK_PUBLIC_KEY', request('paystack_public_key'));  
        $this->storeConfiguration('PAYSTACK_BASE_URI', request('paystack_base_uri'));  
        $this->storeConfiguration('PAYSTACK_WEBHOOK_URI', request('paystack_webhook_uri'));   

        $this->storeConfiguration('RAZORPAY_SUBSCRIPTION_ENABLED', request('enable-razorpay-subscription'));
        $this->storeConfiguration('RAZORPAY_KEY_ID', request('razorpay_key_id'));
        $this->storeConfiguration('RAZORPAY_KEY_SECRET', request('razorpay_key_secret'));  
        $this->storeConfiguration('RAZORPAY_BASE_URI', request('razorpay_base_uri'));  
        $this->storeConfiguration('RAZORPAY_WEBHOOK_URI', request('razorpay_webhook_uri'));  
        $this->storeConfiguration('RAZORPAY_WEBHOOK_SECRET', request('razorpay_webhook_secret'));  

        $this->storeConfiguration('BANK_TRANSFER_SUBSCRIPTION', request('enable-bank-subscription'));   
        

        $rows = ['bank_instructions', 'bank_requisites'];
        
        foreach ($rows as $row) {
            Setting::where('name', $row)->update(['value' => $request->input($row)]);
        }
        

        # Enable/Disable Payment Gateways Subscription
        if (request('enable-paypal-subscription') == 'on') {
            $paypal = PaymentPlatform::where('name', 'PayPal')->first();
            $paypal->enabled = true;
            $paypal->save();

        } else {
            $paypal = PaymentPlatform::where('name', 'PayPal')->first();
            $paypal->enabled = false;
            $paypal->save();
        }

        if (request('enable-stripe-subscription') == 'on') {
            $stripe = PaymentPlatform::where('name', 'Stripe')->first();
            $stripe->enabled = true;
            $stripe->save();

        } else {
            $stripe = PaymentPlatform::where('name', 'Stripe')->first();
            $stripe->enabled = false;
            $stripe->save();
        }

        if (request('enable-paystack-subscription') == 'on') {
            $stripe = PaymentPlatform::where('name', 'Paystack')->first();
            $stripe->enabled = 1;
            $stripe->save();

        } else {
            $stripe = PaymentPlatform::where('name', 'Paystack')->first();
            $stripe->enabled = 0;
            $stripe->save();
        }

        if (request('enable-razorpay-subscription') == 'on') {
            $stripe = PaymentPlatform::where('name', 'Razorpay')->first();
            $stripe->enabled = 1;
            $stripe->save();

        } else {
            $stripe = PaymentPlatform::where('name', 'Razorpay')->first();
            $stripe->enabled = 0;
            $stripe->save();
        }

        if (request('enable-bank-subscription') == 'on') {
            $bank_transfer = PaymentPlatform::where('name', 'BankTransfer')->first();
            $bank_transfer->enabled = 1;
            $bank_transfer->save();

        } else {
            $bank_transfer = PaymentPlatform::where('name', 'BankTransfer')->first();
            $bank_transfer->enabled = 0;
            $bank_transfer->save();
        }

        return redirect()->back()->with('success', 'Payment settings successfully updated');
    }

    /**
     * Record in .env file
     */
    private function storeConfiguration($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));         

        }
    }

    private function storeWithQuotes($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . '\'' . env($key) . '\'', $key . '=' . $value, file_get_contents($path)
            ));

        }
    }

}
