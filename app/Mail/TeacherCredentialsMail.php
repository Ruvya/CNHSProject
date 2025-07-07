<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teacher;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($teacher, $password, $loginUrl = null)
    {
        $this->teacher = $teacher;
        $this->password = $password;
        $this->loginUrl = $loginUrl ?: route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to CNHS - Your Teacher Account Credentials',
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'),
                'Calingcaging National High School'
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.teacher-credentials',
            with: [
                'teacherName' => $this->teacher->name,
                'teacherEmail' => $this->teacher->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
                'appName' => 'Calingcaging National High School',
                'supportEmail' => config('mail.from.address'),
            ],
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
