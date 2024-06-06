<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Rules\ValidateUserPasswordRule;
use App\Models\User;

class UserPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the registration data
        $google_data = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        session()->put('google_data', $google_data);

        $qr_code = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            $google_data
        );

        return view('user.profile.security.index', compact('qr_code'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new ValidateUserPasswordRule],
            'new_password' => ['required', Rules\Password::min(8)],
            'new_confirm_password' => ['required','same:new_password', Rules\Password::min(8)],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->back()->with('success','Password Successfully Updated');
    }


    /**
     * Google 2FA Security 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function security(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        $request->validate([
            'enable_2fa' => 'sometimes|required',
            'key' => 'required_if:enable_2fa,on',
        ]);

        $window = 4; 

        if ($request->enable_2fa == 'on') {

            $valid = $google2fa->verifyKey(session()->get('google_data'), $request->key, $window);

            if ($valid) {
                $user = User::find(auth()->user()->id)->first();
                $user->google2fa_secret = session()->get('google_data');
                $user->google2fa_enabled = true;
                $user->save();
                return redirect()->back()->with('success','Google 2FA Login feature is successfully activated');
            } else {
                return redirect()->back()->with('error','Provided OTP key do not match');
            }

        } else {
            $user = User::find(auth()->user()->id)->first();
            $user->google2fa_enabled = false;
            $user->save();
            return redirect()->back()->with('success','Google 2FA Login feature is disabled');
        }

        session()->forget('google_data');
   
        
    }
}


