<?php

namespace App\Http\Controllers;
use App\Jobs\SendScheduledMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\email;
use Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleMailController extends Controller
{
    function start_jobs(){
        $emails = email::where(['user_id'=>(Session::get('user')->id)])->select('id','email','mailer','host','port_no','username','password','encryption','sent','next_in')->get();
        foreach($emails as $email){
            $mailConfig=[
                'driver' => $email->mailer,
                'host' =>$email->host,
                'port_no'=>$email->port_no,
                'username'=>$email->email,
                'password'=>$email->password,
                'encryption'=>$email->encryption,
                'from' => [
                    'address' => $email->email,
                    'name' => "HillSync",
                ],
            ];
            $next_in = $email->next_in;
            $sub = "subject"; //need to be implemented an 
            $msg = "message";// API Model like GEMINI
            $name = "name"; // to get these details
            $sent = $email->sent;
            $i = 0;
            foreach($emails as $to){
                $job = (new SendScheduledMail($to->email,$sub,$msg,$name,$mailConfig))->delay(Carbon::now()->addMinutes(1));
                $jobId = Queue::push($job);
                DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id]);
            }
            $next_in = min($next_in+1,5);
            DB::table('emails')->where('id',$email->id)->update(['next_in'=>1]);
            $sent = $sent+1;
            email::where('id',$email->id)->increment('sent');
        }
        return redirect("/dashboard");
    }
}
