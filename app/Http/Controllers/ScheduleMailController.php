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
use Illuminate\Support\Facades\Artisan;

class ScheduleMailController extends Controller
{
    function start_jobs(){
        $user = Session::get('user');
        if($user->is_running){
            return redirect()->back()->with("error","Queue Already Running");
        }
        exec('php artisan queue:work --queue=jobs --tries=3 --timeout=60 > /dev/null 2>&1 &');
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
            foreach($emails as $to){
                $job = (new SendScheduledMail($i,$to->email,$sub,$msg,$name,$mailConfig))->delay(now()->addMinutes(0.2));
                $jobId = Bus::dispatch($job);
                DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id,'receiver_email_id'=>$to->id]);
            }
            $i++;
            DB::table('emails')->where('id',$email->id)->update(['next_in'=>$next_in,'sent'=>$sent+1]);
        }
        Session::forget("stopped");
        Session::put("running","yes");
        DB::table('users')->where('id',$user->id)->update(['is_running'=>true]);
        return redirect("/dashboard");
    }

    function stop_jobs(){
        $user = Session::get('user');
        // if(!$user->is_running){
        //     return redirect()->back()->with("error","Queue Already Running");
        // }
        Artisan::call('queue:restart');
        DB::table('users')->where('id',$user->id)->update(['is_running'=>false]);
        DB::table('emails')->where('user_id',$user->id)->update(['next_in'=>0]);
        Session::forget("running");
        Session::put("stopped","yes");
        return redirect("/dashboard");
    }
}
