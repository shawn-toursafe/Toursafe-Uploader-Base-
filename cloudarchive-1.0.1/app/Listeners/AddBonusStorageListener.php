<?php

namespace App\Listeners;

use App\Events\RegistrationReferrerBonus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Referral;


class AddBonusStorageListener
{

    /**
     * Handle the event.
     *
     * @param  ReferrerBonus  $event
     * @return void
     */
    public function handle(RegistrationReferrerBonus $event)
    {
        if ($event->user->referred_by !== '') {
            $referrer = User::where('id', $event->user->referred_by)->firstOrFail();
            $total = $referrer->storage_referral + config('payment.referral.registration.storage');
            $referrer->storage_referral = $total;
            $referrer->save();

            Referral::create([
                'referrer_id' => $referrer->id,
                'referrer_email' => $referrer->email,
                'referred_id' => $event->user->id,
                'referred_email' => $event->user->email,
                'storage' => config('payment.referral.registration.storage'),
            ]);  

        }  
    }
}
