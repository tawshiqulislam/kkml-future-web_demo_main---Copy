<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Hash;
use App\Customer;
use App\BusinessSetting;
use App\OtpConfiguration;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Auth\Events\Registered;

class MembershipController extends Controller
{
    public function dashboard(){
        return view('frontend.member.home');
    }

    public function join()
    {
        // $policies = PoliciesList::where('name', 'membership_policy');
        return view('frontend.member.join');
    }

    public function store(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->whereNotIn('id', [$request->user_id])->first() != null) {
                flash(translate('Email or Phone already exists.'));
                return back();
            }
        }
        if (User::where('phone', '+88' . $request->country_code . $request->phone)->whereNotIn('id', [$request->user_id])->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }
        if ($request->has('referral_code')) {
            $referred_by_user = User::where('referral_code', $request->referral_code)->first();
            if ($referred_by_user != null) {
                $user->referred_by = $referred_by_user->id;
            }
            else{
                Session::flash('error','Referral code does not match.');
                return back();
            }
        }
        if (Hash::check($request->pass, $user->password)) {
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->mother_name = $request->mother_name;
            $user->birth_date = $request->birth_date;
            $user->id_card_type = $request->card;
            $user->id_card_num = $request->id_card_number;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->postal_code = $request->postal_code;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->phone = '+88'.$request->phone;
            $user->emergency_contact = $request->emergency_contact;
            $user->membership = "waiting";
            $user->save();
            Session::flash('success','Request Send');
            return view('frontend.member.home');
        }
        else{
            Session::flash('error','Oops. Password not matched.');
            return view('frontend.member.join');
        }
    }

    public function pending(Request $request){
        $sort_search = null;
        $users = User::where('membership', 'waiting')->orderBy('created_at', 'desc');
        if ($request->search != null) {
            $users = $users
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $users = $users->paginate(15);
        return view('backend.membership.pending', compact('users'));
    }

    public function index(Request $request){
        $sort_search = null;
        $users = User::where('membership', 'approved')->orderBy('created_at', 'desc');
        if ($request->search != null) {
            $users = $users
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        $users = $users->paginate(15);
        return view('backend.membership.index', compact('users'));
    }

    public function reject(Request $request)
    {
        $sort_search = null;
        $users = User::where('membership', 'rejected')->orderBy('created_at', 'desc');
        if ($request->search != null) {
            $users = $users
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $users = $users->paginate(15);
        return view('backend.membership.reject', compact('users'));
    }

    public function reg()
    {
        $redirectPath = 'customer_membership_request';
        return redirect('/users/registration')->with(['redirectPath' => $redirectPath ]);
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (User::where('referral_code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

    public function membership_cancel($id)
    {
        $user = User::where('id', $id)->first();
        $user->membership = Null;
        $user->referral_code = '';
        $user->save();
        Session::flash('error','You successfully withdraw your membership request.');
        return redirect()->route('customer_membership_request');
    }


    public function member_op(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if($request->parameter == 'accept' && $user != '')
        {
            $user->membership = 'approved';
            $user->referral_code = $this->generateUniqueCode();
            $user->save();
            Session::flash('success','Member Added');
            return redirect()->route('member.all');
        }
        elseif($request->parameter == 'reject' && $user != '')
        {
            $user->membership = 'rejected';
            $user->referral_code = Null;
            $user->save();
            Session::flash('success','Member Rejected');
            return redirect()->route('member.all');
        }
        elseif($request->parameter == 'pending' && $user != '')
        {
            $user->membership = 'waiting';
            $user->referral_code = Null;
            $user->save();
            Session::flash('success','Member drop to pending');
            return redirect()->route('member.all');
        }
        elseif($request->parameter == 'delete' && $user != '')
        {
            $user->membership = Null;
            $user->referral_code = Null;
            $user->save();
            Session::flash('success','Member Deleted');
            return redirect()->route('member.all');
        }
        else{
            abort(404);
        }
    }
}
