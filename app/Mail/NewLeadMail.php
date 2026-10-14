<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewLeadMail extends Mailable
{
    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Lead baru: '.$this->lead->name.' ('.(Lead::SERVICE_TYPES[$this->lead->service_type] ?? $this->lead->service_type).')');
    }

    public function content(): Content
    {
        return new Content(text: 'mail.new-lead');
    }
}
