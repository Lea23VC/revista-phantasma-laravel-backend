<?php

namespace App\Jobs;

use App\Mail\PhantasmaContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $name;
    protected $email;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param $name
     * @param $email
     * @param $message
     */
    public function __construct($name,  $email,  $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }



    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(['revistaphantasma@gmail.com'])->send(
            new PhantasmaContact(
                $this->name,
                $this->email,
                $this->message
            ),

        );
    }
}
