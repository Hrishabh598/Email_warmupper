<?php
namespace App\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\email;
use App\Jobs\SendScheduledMail;
use Gemini\Laravel\Facades\Gemini;
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
        $next_in = $email->next_in;
        $sent = $email->sent;
        $result = Gemini::geminiPro()->generateContent('Generate a professional and engaging business email subject line. The subject line should be relevant to a typical business scenario and should include a clear and compelling message.only give one subject line with place text.Feel free to vary the style, tone, and format of the subject line. Use realistic and engaging phrases that would capture attention in a business context. For example, you might include: - A direct statement or announcement.
        - An invitation or call to action.
        - A question or query relevant to business communication.

        Ensure the subject line is appropriate for professional email correspondence and designed to catch the recipients interest.

        Thank you!
        ');
        $sub = Str::markdown($result->text());
        $result = Gemini::geminiPro()->generateContent('Generate a professional business email body. The email should include:
        1. A recipients name.
        2. A brief introduction.
        3. Main content relevant to a typical business scenario.
        4. A polite closing with a call to action.

        Feel free to vary the structure and order of these components. Use realistic names and details, and ensure the content is professional and suitable for business communication.

        Here is an example of what to include, but you are free to arrange the elements as you see fit:

        - Start with a greeting or introduction.
        - Provide the main message or information.
        - End with a closing statement and a call to action.

        For instance, the email could start with a direct message, followed by an introduction, then the main content, and conclude with a closing statement. Ensure the email is coherent and contextually appropriate.
        Do not give any placeholder in your response.
        also make sure the email should be relevant to the subject you just provided.
        and do not include the subject in it.
        Thank you!
        ');
        $msg = Str::markdown($result->text());
        $to = email::where('id',$jobRecord->receiver_email_id)->first();
        Log::info("Found to email $to->email");
        $job = (new SendScheduledMail($email->id,$to->email,$sub,$msg,$mailConfig))->delay(now()->addMinutes($next_in));
        $jobId = Bus::dhrishabhtiwari598ispatch($job);
        DB::table('jobs')->where('id',$jobId)->update(['sender_email_id'=>$email->id,'receiver_email_id'=>$to->id]);
        $next_in = min($next_in+3,120);
        DB::table('emails')->where('id',$email->id)->update(['next_in'=>$next_in,'sent'=>$sent+1]);
        Log::channel('console')->info("Mail sent Successfully");
    }
}