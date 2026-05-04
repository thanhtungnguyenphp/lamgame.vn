<?php

namespace App\Mail;

use App\Models\HireRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewHireRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public HireRequest $hireRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[LamGame] Yêu cầu báo giá mới - ' . $this->hireRequest->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hire-request',
            with: ['request' => $this->hireRequest],
        );
    }
}
