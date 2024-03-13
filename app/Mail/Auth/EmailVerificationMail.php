<?php

namespace App\Mail\Auth;

use App\Helpers\TimeStringHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appName;
    public $actionUrl;
    public $frontUrl;
    public $supportUrl;
    public $name;
    public $time;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $seconds = intval(config('site.email_verification_expiration_seconds'));
        $temporarySignedUrl = URL::temporarySignedRoute(
            'auth.verifyEmail',
            Carbon::now()->addSeconds($seconds),
            [
                'id' => $user->id,
            ],
        );
        $parsed_url = \parse_url($temporarySignedUrl);
        $query = $parsed_url['query'];

        $frontend_url = config('app.frontend_url') . config('app.frontend_path_email_verification') . "?" . $query;

        $this->appName = config('mail.from.name');
        $this->actionUrl = $frontend_url;
        $this->frontUrl = config('app.frontend_url');
        $this->name = $user->name;
        $this->supportUrl = config('app.frontend_url') . config('app.frontend_path_contact_us');
        $this->time = TimeStringHelper::convertSecondsToTimeString($seconds);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.email_verification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
