<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\email;
use App\Mail\AddEmailAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Config;

class EmailConfigController extends Controller
{
    function dashboard(){
        $user_id = Session::get('user')->id;
        $emails = email::where(['user_id'=>$user_id])->select('email','sent','next_in','landed_in_spam')->get();
        $sentEmails = 0;
        $spamEmails = 0;
        $totalEmails = $emails->count();
        foreach($emails as $email){
            $sentEmails += $email->sent;
            $spamEmails += $email->landed_in_spam;
        }
        return view('dashboard',compact("emails","sentEmails","spamEmails","totalEmails"));
    }

    function add_email(Request $req){
        $dataArray = [
            'driver' => $req->mailer,
            'host' =>$req->host,
            'port_no'=>$req->port_no,
            'username'=>$req->email,
            'password'=>str_replace(' ', '', $req->password),
            'encryption'=>$req->encryption,
            'email'=>$req->email
        ];

        $em = email::where(['email'=>$req->email])->first();
        if($em){
            return redirect()->back()->with("error","Email already added please add another email");
        }
        Session::put('data',$dataArray);
        $mailConfig=[
            'driver' => $req->mailer,
            'host' =>$req->host,
            'port_no'=>$req->port_no,
            'username'=>$req->email,
            'password'=>str_replace(' ', '', $req->password),
            'encryption'=>$req->encryption,
            'from' => [
                'address' => $req->email,
                'name' => "HillSync",
            ],
        ];
        $otp = Str::random(6);
        Session::put('otp',$otp);
        Session::put("valid_till",Carbon::now()->addMinutes(5));
        Config::set('mail',$mailConfig);
        Mail::to($req->email)->send(new AddEmailAuth(Session::get('user')->name,$otp));

        return redirect('/verify-add-Email-otp');
    }
    function verify_otp(Request $req){
        $storedOtp = Session::get("otp");
        $otp = $req->otp;
        if($storedOtp && $storedOtp==$otp && Carbon::now()->lt(Session::get('valid_till'))){
            $dataArray = Session::get('data');
            email::create([
                'user_id' => Session::get('user')->id,
                'email' => $dataArray['email'],
                'mailer' => $dataArray['driver'],
                'host' =>$dataArray['host'],
                'port_no'=>$dataArray['port_no'],
                'username'=>$dataArray['username'],
                'password'=>$dataArray['password'],
                'encryption'=>$dataArray['encryption'],
            ]);
            Session::forget("otp");
            Session::forget("valid_till");
            Session::forget("data");
            return redirect()->route('dashboard')->with("success","Your Email has been added");
        }
        else{
            return redirect()->back()->with("error","Wrong otp or TimeOut!!");
        }
    }
}
