<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarLaudoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pdf;

    public function __construct($pdf)
    {
        $this->pdf = storage_path('app/public/' . $pdf);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Enviar Laudo Mail')
            ->text('mails.laudo')
            ->attach($this->pdf, [
                'as' => 'Laudo.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
