<?php

namespace App\Console\Commands;

use App\Mail\ORPayment;
use App\Models\OrderRequest;
use App\Models\HistoryStatus;
use Illuminate\Console\Command;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ResendCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:resend_charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenvia link de cobrança';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        OrderRequest::with('orderRequestPayment.historyStatus')->where('status', 4)->each(function($query){
            $total_exams = collect();
            $or_payment = $query->orderRequestPayment->whereNotIn('payment_status', [0,2,5])->whereNull('payment_id')->each(function($query) use($total_exams){
                if(date('Y-m-d H:i:s', strtotime('-3 Days')) >= date('Y-m-d H:i:s', strtotime($query->historyStatus->where('type', 'PAGAMENTO')->sortByDesc('created_at')->first()->created_at))){
                    $total_exams->add($query->id);
                    HistoryStatus::create([
                        'reference_type' => 'OrderRequestPayment',
                        'reference_id' => $query->id,
                        'type' => 'PAGAMENTO',
                        'reason' => 'Reenvio de link de pagamento',
                        'description' => 'Reenvio de link de pagamento',
                    ]);
                }
            });

            if($total_exams->count() > 0){
                $userClient = $this->userClient($query, ($request->or_payment_id ?? []));
                Mail::to($userClient[1])->send(new ORPayment($query, $userClient, $total_exams, 'resend_charge'));
            }
        });
        return true;
    }

    // Verificação do usuario
    public function userClient($order_request, $or_payment_id)
    {
        $userEmail = $order_request->orderRequestPayment->first()->email;
        $token_generate = collect([
            'file' => (string)\Str::uuid(),
            'token_unique' => Crypt::encryptString(base64_encode($userEmail.':')),
            'order_request_id' => $order_request->id,
            'or_payment_id' => $or_payment_id
        ]);
        $encrypt_token = Crypt::encryptString($token_generate->toJson());
        Storage::disk('local')->put('user_tokens/'.$token_generate['file'].'.txt', $encrypt_token);
        ############################################################################################################
                        ######################AINDA PRECISA ACERTAR A URL##########################
        return ['https://lab-ecommerce.simetriastudio.dev.br/url-teste?token='.$token_generate['file'], $userEmail];
                        ######################AINDA PRECISA ACERTAR A URL##########################
        ############################################################################################################
    }
}
