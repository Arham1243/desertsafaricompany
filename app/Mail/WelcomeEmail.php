<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $name;

    public $course;

    public $whatsapp;

    public function __construct($name, $course, $whatsapp)
    {
        $this->name = $name;
        $this->course = $course;
        $this->whatsapp = $whatsapp;
    }

    public function build()
    {
        $subject = "You're Selected: Join the {$this->course} Bootcamp by Hiba Skills Academy";

        return $this
            ->subject($subject)
            ->view('emails.welcome-email-2');
    }
}
