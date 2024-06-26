<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable; 
    use HasRoles; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'job_role',
        'company',
        'website',
        'email',
        'password',
        'phone_number',
        'address',
        'city',
        'plan_id',
        'postal_code',
        'country',
        'profile_photo_path',
        'oauth_id',
        'oauth_type',
        'referral_id',
        'referred_by',
        'referral_payment_method',
        'referral_paypal',
        'referral_bank_requisites',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'balance',
        'storage_total',
        'storage_referral',
        'group',
        'status',
        'google2fa_enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function path()
    {
        return route('admin.users.show', $this);
    }

    /**
     * User can have many support tickets
     */
    public function support()
    {
        return $this->hasMany(Support::class);
    }

    /**
     * User can have many TTS results
     */
    public function result()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * User can have many payments
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }


    public function hasActiveSubscription()
    {
        return optional($this->subscription)->isActive() ?? false;
    }

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  decrypt($value),
            set: fn ($value) =>  encrypt($value),
        );
    }

}
