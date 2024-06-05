<?php

namespace App\Jobs;

use App\Mail\PhantasmaContact;
use App\Models\EditorialMember;
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

        # Get all emails from EditorialMembers

        $emails = EditorialMember::all()->pluck('email')->toArray();

        # Merge it with config('mail.from.address')
        $emails[] = config('mail.from.address');


        Mail::to($emails)->send(
            new PhantasmaContact(
                $this->name,
                $this->email,
                $this->message
            ),

        );
    }
}
