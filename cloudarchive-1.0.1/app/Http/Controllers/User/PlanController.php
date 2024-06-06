<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Support\Str;
use App\Models\PaymentPlatform;
use App\Models\Setting;
use App\Models\Plan;

class PlanController extends Controller
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
        $plan_monthly = Plan::where('status', 'active')->where('payment_frequency', 'monthly')->count();
        $plan_yearly = Plan::where('status', 'active')->where('payment_frequency', 'yearly')->count();

        $monthly_plans = Plan::where('status', 'active')->where('payment_frequency', 'monthly')->get();
        $yearly_plans = Plan::where('status', 'active')->where('payment_frequency', 'yearly')->get();

        return view('user.balance.plans.index', compact('plan_monthly', 'monthly_plans', 'plan_yearly', 'yearly_plans'));
    }


    /**
     * Checkout for Subscription plans only.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Plan $id)
    {   
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return redirect()->back();
        }
        
        $payment_platforms = PaymentPlatform::where('enabled', 1)->get();

        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;

        $total_value = $tax_value + $id->price;
        $currency = $id->currency;
        $gateway_plan_id = $id->gateway_plan_id;

        $bank_information = ['bank_instructions', 'bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $bank_order_id = 'BT-' . strtoupper(Str::random(15));
        session()->put('bank_order_id', $bank_order_id);

        return view('user.balance.plans.subscribe-checkout', compact('id', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'gateway_plan_id', 'bank', 'bank_order_id'));
    } 
}
