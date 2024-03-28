<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Lunar\Models\Order;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
        $this->pdf = Pdf::loadView('adminhub::pdf.order', ['order' => $this->order]);;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Placed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.orders.confirmed',
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
            Attachment::fromData(function () {
                return $this->pdf->output();
            })->as("Order-{$this->order->reference}.pdf")
                ->withMime('application/pdf')
        ];
    }
}
