<?php

namespace App\Console\Commands;

use Webklex\PHPIMAP\Folder;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Support\FolderCollection;
use Illuminate\Console\Command;

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
        foreach ($folders as $folder) {
            $messages = $folder->query()->text('Coleta Material DNA')->get();

            // $messages = $folder->query()->text('Coleta Material DNA')->since(\Carbon\Carbon::now()->subDays(1))->get();

            foreach($messages as $message){
                $get_data_message = collect();
                // $get_data_messages->add(['UID' => $message->getUid(), 'HTML' => $message->getHTMLBody()]);
                $dom = new \DOMDocument(); // abrindo DOMDoumento para ler os dados
                $dom->loadHTML($message->getHTMLBody()); // lendo em html

                $xPath = new \DOMXPath($dom); // setando DOMXPath para que possamos acessar com o domdocuemnt

                $table = $xPath->query('.//table')[0];
                $table = $xPath->query('.//tbody/tr', $table);
                for($i=1; count($table) > $i; $i++){
                    $get_data_table = collect([]);
                    $table_td = $xPath->query('.//td', $table[$i]);
                    if(count($table_td) > 0){
                        for($itd = 0; count($table_td) > $itd; $itd++){
                            $get_data_table->put($this->collumns[$itd],trim(utf8_decode($table_td[$itd]->textContent)));
                        }
                    }

                    if($get_data_table->count() > 0) $get_data_message->add($get_data_table->all());
                }

                $dados_g = $xPath->query('.//font');
                $dados_gerais = collect();
                for($i = 0; count($dados_g) > $i; $i++){
                    $dados_content = utf8_decode($dados_g[$i]->textContent);
                    if(str_contains($dados_content, 'Número do atendimento:') || str_contains($dados_content, 'Responsável Técnico:') || str_contains($dados_content, 'Data:')){
                        $dados_search = explode(':', $dados_content);
                        $dados_gerais->put(\Str::slug(trim($dados_search[0]),'_'), trim($dados_search[1]));
                    }
                    if(str_contains($dados_content, 'Criador:')){
                        $dados_search = explode(':', $dados_content);
                        $dados_ss = explode('-', $dados_search[1]);
                        $dados_gerais->put(\Str::slug(trim($dados_search[0]),'_'), [trim($dados_ss[0]).'-'.trim($dados_ss[1]),trim($dados_ss[2])]);
                    }
                }

                if($get_data_message->count() > 0) $get_data_messages->add(['uid' => $message->getUid(), 'data_table' => $get_data_message->all(), 'data_g' => $dados_gerais->toArray()]);
            }
        }

        // \Log::info($get_data_messages->toArray());
        return true;
    }
}
