<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utils\SendEmailToNewUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        if (\auth()->id()!=null){
            return redirect('/');
        }else{
            return view('admin.login.index');
        }
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user_google_email = Socialite::driver('google')->user()->getEmail();
            $user = User::where([
                'email'=> $user_google_email,
                'is_active'=>'1'
                ])->first();
            if ($user != null){
                \auth()->loginUsingId($user['uuid'],true);
                return redirect()->intended('/');
//            dd(\auth()->user()->roles['role']);
            }else{
                return redirect('login')->withErrors(["error"=>"Failed to Login"]);
            }
        } catch
        (\Exception $e) {
            return redirect('login')->withErrors(["error"=>$e->getMessage()]);
        }
    }

    public function logout(){
        User::where('uuid', Auth::user()->getAuthIdentifier())->update(['last_logged_in' => now()]);
        \auth()->logout();
        return redirect('login');
    }

    public function verify(Request $request, $token){
        // return response()->json([
        //     'a' => $request->all(),
        //     'token' => $token,
        //     'signature' => $request->get('signature')
        // ]);
        try{
            $signature = SendEmailToNewUsers::tokendecrypt($request->get('signature'));
            $user_signature = json_decode($signature, true);
            $user = User::where('email', $user_signature['email'])->first();
            if($token == $user['remember_token']){
                User::where('email', $user_signature['email'])->update([
                    'is_active' => '1',
                ]);
            }
            return redirect('login')->with('status', 'Successfully verify email');
        }catch(\Exception $e){
            return abort(401, 'Error to verify email token');
        }



    }
}
