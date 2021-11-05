<?php

namespace App\Http\Controllers;

use App\Models\Ebulksms;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'account' => ['required', 'unique:users'],
            'phone' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // $otp = '123456';
        $otp = rand(100000, 999999);

        //Send OTP
        $ebulk = new Ebulksms();

        $from = "SMS Auth";
        $msg = "SMS Auth\n your OTP is " . $otp . " will expire in the next 10-mins, Do not share otp, \n " . date("l jS \of F Y h:i:s A") . ".";
        $ss = strval($msg);
        $new = substr($request->phone, -10);
        $num = '234' . $new;
        $to = $num;

        try {
            $ebulk->useJSON($from, $ss, $to);
        } catch (Exception $e) {
            return back()->with('error', 'Oops Phone cannot be verified, Check your Connection and Phone and then try again.');
        }

        $info = [
            'otp' => $otp,
            'data' => $request->all(),
        ];

        if (Cache::get($otp)) {
            Cache::forget($otp);

            Cache::put($otp, $info, now()->addMinutes(15));
        } else {
            Cache::put($otp, $info, now()->addMinutes(15));
        }

        // return Cache::get($otp);

        return redirect()->route('show_verification');
    }

    public function show()
    {
        // session(['status' => 'Sms has been sent to you phone with otp code.']);
        return view('auth.phone_verify')->with('success', 'Sms has been sent to you phone with otp code.');
    }

    public function verify_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $info = Cache::get($request->otp);

        // return $info;
        if ($info) {

            $user = User::create([
                'name' => $info['data']['name'],
                'email' => $info['data']['email'],
                'account' => $info['data']['account'],
                'phone' => $info['data']['phone'],
                'password' => Hash::make($info['data']['password']),
            ]);

            User::where('id', '=', $user->id)->update([
                'phone' => $info['data']['phone'],
            ]);

            return redirect()->route('login')->with('status', 'You have Successful Registered New Account');
        } else {
            return back()->with('error', 'Oops, Otp is incorrect');
        }

        return 1;
    }
}
