<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\OTPVerificationController;
use App\Mail\SecondEmailVerifyMailManager;
use App\Notifications\EmailVerificationNotification;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function forgetRequest(Request $request)
    {
        if ($request->send_code_by == 'email') {
            $user = User::where('email', $request->email_or_phone)->first();
        } else {
            $user = User::where('phone', "+880" . substr($request->email_or_phone, -10))->first();
        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => 'User is not found'], 404);
        }

        if ($user) {
            $user->verification_code = rand(100000, 999999);
            $user->save();
            if ($request->send_code_by == 'phone') {

                $otpController = new OTPVerificationController();
                $otpController->send_code($user);
            } else {


                $array['view'] = 'emails.verification';
                $array['from'] = env('MAIL_USERNAME');
                $array['subject'] = translate('Password Reset');
                $array['content'] = translate('Verification Code is ') . $user->verification_code;

                Mail::to($user->email)->queue(new SecondEmailVerifyMailManager($array));

                //$user->notify(new EmailVerificationNotification());
            }
        }

        return response()->json([
            'result' => true,
            'message' => 'A code is sent'
        ], 200);
    }

    public function confirmReset(Request $request)
    {
        $user = User::where('verification_code', $request->verification_code)->first();

        if ($user != null) {
            $user->verification_code = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'result' => true,
                'message' => 'Your password is reset.Please login',
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => 'No user is found',
            ], 200);
        }
    }

    public function resendCode(Request $request)
    {

        if ($request->verify_by == 'email') {
            $user = User::where('email', $request->email_or_phone)->first();
        } else {
            $user = User::where('phone', "+880" . substr($request->email_or_phone, -10))->first();
        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => 'User is not found'], 404);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if ($request->verify_by == 'email') {
            $user->notify(new EmailVerificationNotification());
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }


        return response()->json([
            'result' => true,
            'message' => 'A code is sent again',
        ], 200);
    }
}
