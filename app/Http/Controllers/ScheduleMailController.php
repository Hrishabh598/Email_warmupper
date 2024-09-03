<?php

namespace App\Http\Controllers;
use App\Jobs\SendScheduledMail;
use App\Models\email;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Gemini\Laravel\Facades\Gemini;
class ScheduleMailController extends Controller
{
    function start_jobs()
    {
        $user = Session::get('user');
        if($user->is_running){
            return redirect()->back()->with("error","Queue Already Running");
        }
        $emails = email::where(['user_id' => (Session::get('user')->id)])->select('id', 'email', 'mailer', 'host', 'port_no', 'username', 'password', 'encryption', 'sent', 'next_in')->get();
        $i = 1;
        foreach ($emails as $email) {
            $mailConfig = [
                'transport' => $email->mailer,
                'host' => $email->host,
                'port' => $email->port_no,
                'encryption' => strtolower($email->encryption),
                'username' => $email->email,
                'password' => $email->password,
                'from' => [
                    'address' => $email->email,
                    'name' => "HillSync",
                ],
            ];
            // return $mailConfig;
            $next_in = $email->next_in;
            $sent = $email->sent;
            foreach ($emails as $to) {
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
                // Log::channel('console')->info("$msg");
                // return redirect('/dashboard');
                $job = new SendScheduledMail($i, $to->email, $sub, $msg, $mailConfig);
                $jobId = Bus::dispatch($job);
                DB::table('jobs')->where('id', $jobId)->update(['sender_email_id' => $email->id, 'receiver_email_id' => $to->id]);
            }
            $i++;
            DB::table('emails')->where('id', $email->id)->update(['next_in' => $next_in, 'sent' => $sent + 1]);
        }
        Session::forget("stopped");
        Session::put("running", "yes");
        DB::table('users')->where('id', $user->id)->update(['is_running' => true]);
        Session::put('user',DB::table('users')->where('id', $user->id)->first());
        return redirect("/dashboard");
    }

    function stop_jobs()
    {
        $user = Session::get('user');
        if(!$user->is_running){
            return redirect()->back()->with("error","Queue Already Running");
        }
        DB::table('users')->where('id', $user->id)->update(['is_running' => false]);
        DB::table('emails')->where('user_id', $user->id)->update(['next_in' => 0]);
        Session::forget("running");
        Session::put("stopped", "yes");
        Session::put('user',DB::table('users')->where('id', $user->id)->first());
        return redirect("/dashboard");
    }
}