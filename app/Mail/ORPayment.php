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
    public $userClient;
    public $total_exams;
    public $charge;
    public function __construct($order_request, $userClient, $total_exams, $charge)
    {
        $this->order_request = $order_request;
        $this->userClient = $userClient;
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
        return $this->with([
            'msg' => 'Você está recebendo por email um link de pagamento com auto-login.',
            'link' => $this->userClient[0],
            ])->markdown('mails.orpayment')->subject(($this->charge == 'generate_charge' ? 'Liberado para pagar' : 'Exames não pago'))->from('pedidos@locilab.app.br', 'Lab Loci');
    }
}
