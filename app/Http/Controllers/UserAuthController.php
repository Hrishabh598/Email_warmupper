<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class UserAuthController extends Controller
{

    function check(Request $req){
        $user = User::where(['email'=>$req->input('email')])->first();
        if($user && Hash::check($req->input('password'),$user->password)){
            Session::put('user',$user);
            return redirect('/dashboard');
        }
        return redirect()->back()->with("error","Wrong emailID or Password.");
    }
    function send_otp(Request $req){
        $to = $req->input('email');
        $user = User::where(['email'=>$to])->first();
        if($user){
            return redirect()->back()->with('error','Email already Exists !! Please Log In or Change password!!');
        }
        $dataArray['email'] = $to;
        $dataArray['name'] = $req->input('name');
        $dataArray['password'] = Hash::make($req->input("password1"));
        $otp = Str::random(6);
        Session::put("otp_$to",$otp);
        Session::put('data',$dataArray);
        Session::put('valid_till',Carbon::now()->addMinutes(5));
        Mail::to($to)->send(new \App\Mail\AuthMail($req->input('name'),$otp));
        return redirect("/verify-otp");
    }
    function verify_otp(Request $req){
        $dataArray = Session::get('data');
        $email = $dataArray['email'];
        $otp = $req->input('otp');
        $storedotp = Session::get("otp_$email");
        if($storedotp && $storedotp==$otp && Carbon::now()->lt(Session::get('valid_till'))){
            Session::forget("otp_$email");
            Session::forget('data');
            Session::forget('valid_till');
            User::create([
                'name'=>$dataArray['name'],
                'email'=>$dataArray['email'],
                'password'=>$dataArray['password']
            ]);
            return redirect()->route('login.form')->with('success','Account has been created please log in.');
        }
        else{
            return redirect()->back()->with('error','Invalid OTP or Timeout! please try again.');
        }
    }
    function forget(Request $req){
        $email = $req->email;
        $user = User::where(['email'=>$email])->first();
        if(!$user){
            return redirect()->back()->with("error","No email found please create an account");
        }
        $name = $user->name;
        $otp = Str::random(6);
        Session::put("otp_$email",$otp);
        Session::put("valid_till",Carbon::now()->addMinutes(5));
        Session::put("email",$email);
        Session::put("id",$user->id);
        Mail::to($email)->send(new \App\Mail\AuthMail($name,$otp));
        return redirect("/verify_forget");
    }
    function verify_forget(Request $req){
        $otp = $req->otp;
        $email = Session::get("email");
        $storedotp = Session::get("otp_$email");
        if($storedotp && $otp==$storedotp && Carbon::now()->lt(Session::get("valid_till"))){
            Session::forget('otp');
            Session::forget('valid_till');
            Session::forget("email");
            return redirect("/change_password");
        }
        return redirect()->back()->with("error","Wrong OTP Code or Timout!!");
    }
    function change_password(Request $req){
        $id = Session::get("id");
        $password = Hash::make($req->password1);
        DB::table('users')->where('id',$id)->update(['password'=>$password]);
        Session::forget("id");
        return redirect()->route("login.form")->with("success","Password updated. Please Log In");
    }

    function logout(){
        Session::forget('user');
        return redirect('/login');
    }
}
