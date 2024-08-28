<?php
namespace App\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Bus;
use App\Models\email;
use App\Jobs\SendScheduledMail;
class OperationsAfterProcessing
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(JobProcessed $event): void
    {
        $email = email::where(['id'=>$event->job->sender_email_id])->first();
        $mailConfig=[
            'transport' => $email->mailer,
            'host' =>$email->host,
            'port'=>$email->port_no,
            'username'=>$email->email,
            'password'=>$email->password,
            'encryption'=>$email->encryption,
            'from' => [
                'address' => $email->email,
                'name' => "HillSync",
            ],
        ];
        $next_in = $email->next_in;
        $sent = $email->sent;
        $sub = "subject"; //need to be implemented an
        $msg = "message";// API Model like GEMINI
        $name = "name"; // to get these details
        $emails = email::where(['user_id'=>(Session::get('user')->id)])->select('email')->get();
        foreach($emails as $to){
            $job = (new SendScheduledMail($to,$sub,$msg,$name,$mailConfig))->delay(now()->addMinutes($next_in));
            $jobId = Bus::dispatch($job);
            DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id]);
        }
        $next_in = min($next_in+1,5);
        $sent = $sent+1;
        DB::table('emails')->where('id',$email->id)->update(['not_in'=>$not_in,'sent'=>$sent]);
    }
}