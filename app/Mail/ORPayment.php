<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ORPayment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order_request;
    public $total_exams;
    public $charge;
    public function __construct($order_request, $total_exams, $charge)
    {
        $this->order_request = $order_request;
        $this->total_exams = $total_exams;
        $this->charge = $charge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->with(['msg' => 'A exames liberados para serem pagos'])->markdown('mails.orpayment')->subject(($this->charge == 'generate_charge' ? 'Liberado para pagar' : 'Exames não pago'))->from('naoresponder@labloci.com.br', 'Lab Loci');
    }
}
