<?php

namespace App\Console\Commands;

use Webklex\PHPIMAP\Folder;
use App\Models\OrderRequest;
use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Support\FolderCollection;

class SearchOrderMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:searchmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca Pedidos nos emails';

    // Posicionamentos
    public $collumns = ['id', 'produto', 'sexo', 'nascimento', 'pai', 'registro_pai', 'mae', 'registro_mae'];
    public $collumns2 = ['n_coleta', 'id', 'produto', 'sexo', 'nascimento', 'registro_pai', 'pai', 'vivo_pai', 'dna_pai', 'registro_mae', 'mae', 'vivo_mae', 'dna_mae',  'obs'];

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
        /** @var \Webklex\PHPIMAP\Client $client */
        $client = Client::account('default');
        //Connect to the IMAP Server
        $client->connect();
        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders();
        $get_data_messages = collect();
        libxml_use_internal_errors(true);
        foreach ($folders as $folder) {
            // $messages = $folder->query()->text('Coleta Material DNA')->get();
            // \Log::info($messages);
            $messages = $folder->query()->since(\Carbon\Carbon::now()->subDays(1))->get();
            // \Log::info($messages);
            foreach ($messages as $message) {
                $get_data_message = collect();

                $get_data_message->add(['UID' => $message->getUid(), 'HTML' => $message->getHTMLBody()]);
                $dom = new \DOMDocument(); // abrindo DOMDoumento para ler os dados
                $file = @$dom->loadHTML($message->getHTMLBody()); // lendo em html

                $xPath = new \DOMXPath($dom); // setando DOMXPath para que possamos acessar com o domdocuemnt

                $table = $xPath->query('.//table')[0];
                $table = $xPath->query('.//tbody/tr', $table);
                for ($i = 1; count($table) > $i; $i++) {
                    $get_data_table = collect([]);
                    $table_td = $xPath->query('.//td', $table[$i]);
                    // \Log::info($table_td);
                    if (count($table_td) > 0) {
                        if (count($table_td) > 8) {
                            $collumns = $this->collumns2;
                        } else {
                            $collumns = $this->collumns;
                        }
                        for ($itd = 0; count($table_td) > $itd; $itd++) {
                            $get_data_table->put($collumns[$itd], trim(utf8_decode($table_td[$itd]->textContent)));
                            // $get_data_table->put($itd, trim(utf8_decode($table_td[$itd]->textContent)));
                        }
                    }

                    if ($get_data_table->count() > 0) $get_data_message->add($get_data_table->all());
                }

                $dados_g = $xPath->query('.//font');
                $dados_gerais = collect();
                for ($i = 0; count($dados_g) > $i; $i++) {
                    $dados_content = utf8_decode($dados_g[$i]->textContent);
                    if (str_contains($dados_content, 'Número do atendimento:') || str_contains($dados_content, 'Responsável Técnico:') || str_contains($dados_content, 'Data:')) {
                        $dados_search = explode(':', $dados_content);
                        $dados_gerais->put(\Str::slug(trim($dados_search[0]), '_'), trim($dados_search[1]));
                    }
                    if (str_contains($dados_content, 'Criador:')) {
                        \Log::info($dados_content);
                        $dados_search = explode(':', $dados_content);
                        $dados_ss = explode('-', $dados_search[1]);
                        if (count($dados_ss) > 0) {
                            $dados_gerais->put(\Str::slug(trim($dados_search[0]), '_'), [0, trim($dados_search[1])]);
                        } else {
                            $dados_gerais->put(\Str::slug(trim($dados_search[0]), '_'), [trim($dados_ss[0]) . '-' . trim($dados_ss[1]), trim($dados_ss[2])]);
                        }
                    }
                }

                $get_data_messages->add([
                    'uid' => $message->getUid(),
                    'data_table' => $get_data_message->all(),
                    'data_g' => $dados_gerais->toArray(),
                    'email' => [
                        'subject' => (string)$message->getSubject(),
                        'html_body' => $message->getHTMLBody(),
                    ]
                ]);
            }
        }

        $get_data_messages->map(function ($query) {
            \Log::info($query);
            if (OrderRequest::where('uid', $query['uid'])->get()->count() == 0) {
                $order_request['uid'] = $query['uid'];
                $order_request['origin'] = 'email';
                $order_request['creator'] = $query['data_g']['criador'][1] ?? null;
                $order_request['creator_number'] = $query['data_g']['criador'][0] ?? null;
                $order_request['technical_manager'] = $query['data_g']['responsavel_tecnico'] ?? null;
                $order_request['collection_date'] = date('Y-m-d', strtotime($query['data_g']['data'] ?? date('Y-m-d'))) ?? null;
                $order_request['collection_number'] = $query['data_g']['numero_do_atendimento'] ?? null;
                $order_request['data_g'] = $query ?? null;
                OrderRequest::create($order_request);
            }
        });

        return true;
    }
}
