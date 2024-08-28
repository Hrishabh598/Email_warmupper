<?php

namespace App\Jobs;
use App\Mail\WarmupMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SendScheduledMail implements ShouldQueue
{
    use Queueable;
    public $sub;
    public $msg;
    public $name;
    public $to;
    public $mailConfig;
    public $id;
    /**
     * Create a new job instance.
     */
    public function __construct($id,$to,$sub,$msg,$name,$mailConfig)
    {
        $this->id = $id;
        $this->sub = $sub;
        $this->msg = $msg;
        $this->name = $name;
        $this->to = $to;
        $this->mailConfig = $mailConfig;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Config::set("mail.mailers.dynamic$this->id",$this->mailConfig);
        Log::info("configured but i guess mailer issue");
        Mail::mailer("dynamic$this->id")->to($this->to)->send(new WarmupMail($this->sub,$this->msg,$this->name));
    }
}
