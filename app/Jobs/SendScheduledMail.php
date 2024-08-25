<?php

namespace App\Jobs;
use App\Mail\WarmupMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Config;

class SendScheduledMail implements ShouldQueue
{
    use Queueable;
    public $sub;
    public $msg;
    public $name;
    public $to;
    public $mailConfig;
    /**
     * Create a new job instance.
     */
    public function __construct($to,$sub,$msg,$name,$mailConfig)
    {
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
        Config::set('mail',$this->mailConfig);
        Mail::to($this->to)->send(new WarmupMail($this->sub,$this->msg,$this->name));
    }
}
