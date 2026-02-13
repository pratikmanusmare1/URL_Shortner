<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        $acceptUrl = url('/invitations/' . $this->invitation->token . '/accept');

        return $this->subject('You are invited')
                    ->view('emails.invitation')
                    ->with([
                        'invitation' => $this->invitation,
                        'acceptUrl' => $acceptUrl,
                    ]);
    }
}
