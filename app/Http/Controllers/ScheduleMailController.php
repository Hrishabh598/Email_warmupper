<?php

namespace App\Http\Controllers;
use App\Jobs\SendScheduledMail;
use App\Mail\WarmupMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\email;
use Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Carbon\Carbon;

class ScheduleMailController extends Controller
{
    function start_jobs(){
        $emails = email::where(['user_id'=>(Session::get('user')->id)])->select('id','email','mailer','host','port_no','username','password','encryption','sent','next_in')->get();
        $i = 1;
        foreach($emails as $email){
            $mailConfig=[
                'transport' => $email->mailer,
                'host' =>$email->host,
                'port'=>$email->port_no,
                'encryption'=>strtolower($email->encryption),
                'username'=>$email->email,
                'password'=>$email->password,
                'from' => [
                    'address' => $email->email,
                    'name' => "HillSync",
                ],
            ];
            // return $mailConfig;
            $next_in = $email->next_in;
            $sub = "subject"; //need to be implemented an 
            $msg = "message";// API Model like GEMINI
            $name = "name"; // to get these details
            $sent = $email->sent;
            // Config::set("mail.mailers.dynamic$i",$mailConfig);
            foreach($emails as $to){
                $job = (new SendScheduledMail($i,$to->email,$sub,$msg,$name,$mailConfig))->delay(now()->addMinutes(1));
                $jobId = Bus::dispatch($job);
                DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id]);
            }
            $i++;
            $next_in = min($next_in+1,5);
            // DB::table('emails')->where('id',$email->id)->update(['next_in'=>1]);
            // $sent = $sent+1;
            // email::where('id',$email->id)->increment('sent');
        }
        return redirect("/dashboard");
    }
}
