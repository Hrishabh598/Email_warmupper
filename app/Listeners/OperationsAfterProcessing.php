<?php
namespace App\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
    public function handle(JobProcessing $event): void
    {
        Log::info('Listener triggered: HandleCustomJobProcessing');
        $jobId = $event->job->getJobId();
        $jobRecord = DB::table('jobs')->where('id', $jobId)->first();
        Log::info("Job found with Id $jobId");
        $email = email::where('id',$jobRecord->sender_email_id)->first();
        Log::info("email is $email->email");
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
        // $next_in = $email->next_in;
        // $sent = $email->sent;
        // $sub = "subject"; //need to be implemented an
        // $msg = "message";// API Model like GEMINI
        // $name = "name"; // to get these details
        // $to = email::where('id',$jobRecord->receiver_email_id)->first();
        // Log::info("Found to email $to->email");
        // $job = (new SendScheduledMail($email->id,$to->email,$sub,$msg,$name,$mailConfig))->delay(now()->addMinutes($next_in));
        // $jobId = Bus::dispatch($job);
        // DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id,'receiver_email_id'=>$to->id]);
        // $next_in = min($next_in+1,5);
        // DB::table('emails')->where('id',$email->id)->update(['next_in'=>$next_in,'sent'=>$sent+1]);
        // Log::info("Mail sent Successfully");
    }
}