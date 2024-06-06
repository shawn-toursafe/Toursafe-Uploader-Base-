<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use Illuminate\Http\Request;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;

class BankTransferService 
{
    use ConsumesExternalServiceTrait;


    protected $promocode;


    public function handlePaymentSubscription(Request $request, Plan $id)
    {   
        if (session()->has('bank_order_id')) {
            $orderID = session()->get('bank_order_id');
            session()->forget('bank_order_id');
        }

        $subscription = Subscription::create([
            'active_until' => now()->addDays($id->payment_frequency),
            'user_id' => auth()->user()->id,
            'plan_id' => $id->id,
            'status' => 'pending',
            'created_at' => now(),
            'gateway' => 'BankTransfer',
            'storage_total' => $id->storage_total,
            'subscription_id' => $orderID,
        ]);

        $tax_value = (config('payment.payment_tax') > 0) ? $id->price * config('payment.payment_tax') / 100 : 0;
        $total_price = $tax_value + $id->price;

        $record_payment = new Payment();
        $record_payment->user_id = auth()->user()->id;
        $record_payment->plan_id = $id->id;
        $record_payment->order_id = $orderID;
        $record_payment->plan_name = $id->plan_name;
        $record_payment->price = $total_price;
        $record_payment->currency = $id->currency;
        $record_payment->gateway = 'BankTransfer';
        $record_payment->frequency = $id->payment_frequency;
        $record_payment->status = 'pending';
        $record_payment->storage_size = $id->storage_total;
        $record_payment->save();      

        event(new PaymentProcessed(auth()->user()));

        $bank_information = ['bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        return view('user.balance.plans.banktransfer-success', compact('id', 'orderID', 'bank', 'total_price'));
    }

}