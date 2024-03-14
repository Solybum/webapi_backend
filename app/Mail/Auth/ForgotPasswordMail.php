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

class ForgotPasswordMail extends Mailable
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
        $seconds = intval(config('site.password_reset_expiration_seconds'));
        $temporarySignedUrl = URL::temporarySignedRoute(
            'auth.resetPassword',
            Carbon::now()->addSeconds($seconds),
            [
                'id' => $user->id,
                'hash' => hash('sha256', $user->password),
            ],
        );
        $parsed_url = \parse_url($temporarySignedUrl);
        $query = $parsed_url['query'];

        $frontend_url = config('site.frontend_url') . config('site.frontend_path_password_reset') . "?" . $query;

        $this->appName = config('mail.from.name');
        $this->actionUrl = $frontend_url;
        $this->frontUrl = config('site.frontend_url');
        $this->name = $user->name;
        $this->supportUrl = config('site.frontend_url') . config('site.frontend_path_contact_us');
        $this->time = TimeStringHelper::convertSecondsToTimeString($seconds);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.auth.forgot_password.html',
            text: 'emails.auth.forgot_password.text',
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
