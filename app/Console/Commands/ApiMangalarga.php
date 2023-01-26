<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ApiMangalargaController;
use Illuminate\Console\Command;

class ApiMangalarga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca Pedidos na API';

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
        $controller = new ApiMangalargaController();
        $controller->getResenha();
        $controller->getApi();

        return true;
    }
}
