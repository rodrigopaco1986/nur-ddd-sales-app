<?php

namespace Src\Sales\Payment\Infraestructure\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Payment extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  string  $pdfContent  The raw string content of the generated PDF.
     * @param  string  $invoiceNumber  The invoice number for the email subject and attachment filename.
     */
    public function __construct(
        public readonly string $pdfContent,
        public readonly string $paymentNumber
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payment #' . $this->paymentNumber,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'payment::Emails.payment_email_body',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, "payment-{$this->paymentNumber}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
