<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fur;
use App\Models\Log;
use App\Models\Laudo;
use App\Models\Animal;
use App\Models\Owner;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\AnimalToParent;
use App\Http\Controllers\Controller;
use App\Services\CodlabGenerator;
use App\Models\Breed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log as LogFacade;

class AnimaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');
    }

    public function index(Request $request)
    {
        $filters = $this->extractAnimalFilters($request);
        $animais = $this->filterAnimaisQuery($request)->paginate(20)->withQueryString();
        $especiesList = $this->distinctAnimalValues('especies');
        $breedsList = $this->distinctAnimalValues('breed');
        $statusOptions = self::animalStatusOptions();

        if ($request->ajax()) {
            return response()->json([
                'viewRender' => view('admin.animais.includes.table-rows', compact('animais', 'statusOptions'))->render(),
                'pagination' => $animais->links()->toHtml(),
                'total' => $animais->total(),
            ]);
        }

        return view('admin.animais.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pelagens = Fur::all();
        return view('admin.animais.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $sigla = substr($request->especies, 0, 3);

        $data = [
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'fur' => $request->fur,
            'chip_number' => $request->chip_number,
            'registro_pai' => $request->registro_pai,
            'pai' => $request->pai,
            'registro_mae' => $request->registro_mae,
            'mae' => $request->mae,


        ];

        $codlab = CodlabGenerator::generate($sigla);
        $data['codlab'] = $request->codlab ? $request->codlab : $codlab;
        // dd($data);
        $animal = Animal::create($data);
        AnimalToParent::updateOrCreate(
            ['animal_id' => $animal->id],
            [
                'mae_id' => $request->mae_id,
                'pai_id' => $request->pai_id,
                'register_pai' => $request->register_pai,
                'register_mae' => $request->register_mae,
            ]
        );
        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Criou o animal ' . $animal->animal_name,
            'animal' => $animal->animal_name,
            'order_id' => $animal->order_id ?? null,
        ]);
        return response()->json(['success' => 'Animal cadastrado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animal = Animal::find($id);
        $laudo = Laudo::where('animal_id', $id)->first();

        $breeds = Breed::all();

        return view('admin.animais.edit', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json(['error' => 'Animal não encontrado'], 404);
        }

        $pai = null;
        $mae = null;
        $relation = AnimalToParent::where('animal_id', $animal->id)->first();

        if ($relation) {
            // Buscar pelo pai
            if ($relation->register_pai) {
                $pai = Animal::with('alelos')->where('number_definitive', $relation->register_pai)->first();
            }
            if (!$pai && $relation->pai_id) {
                $pai = Animal::with('alelos')->find($relation->pai_id);
            }

            // Buscar pela mãe
            if ($relation->register_mae) {
                $mae = Animal::with('alelos')->where('number_definitive', $relation->register_mae)->first();
            }
            if (!$mae && $relation->mae_id) {
                $mae = Animal::with('alelos')->find($relation->mae_id);
            }
        }


        return response()->json(['animal' => $animal, 'pai' => $pai, 'mae' => $mae]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $animal = Animal::find($id);

        $rules = [];
        $messages = [];

        if ($request->extra != 1) {
            if (!empty($request->codlab)) {
                $rules['codlab'] = 'unique:animals,codlab,' . $id;
                $messages['codlab.unique'] = 'O codlab já está em uso por outro animal.';
            }
        }

        $request->validate($rules, $messages);

        $codlabAnterior = $animal->codlab;
        $codlabNovo = $request->input('codlab');
        $codlabAlterado = trim((string) ($codlabAnterior ?? '')) !== trim((string) ($codlabNovo ?? ''));

        $animal->update([

            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'registro_pai' => $request->registro_pai,
            'pai' => $request->pai,
            'registro_mae' => $request->registro_mae,
            'mae' => $request->mae,
            'codlab' => $request->codlab,
            'identificador' => $request->identificador,
            'number_definitive' => $request->number_definitive,
        ]);
        AnimalToParent::updateOrCreate(
            ['animal_id' => $id],
            [
                'mae_id' => $request->mae_id,
                'pai_id' => $request->pai_id,
                'register_pai' => $request->register_pai,
                'register_mae' => $request->register_mae,
            ]
        );
        $order = OrderRequest::find($animal->order_id);
        $ordem = OrdemServico::where('animal_id', $id)->first();

        if ($ordem) {
            $ordem->update([
                'codlab' => $request->codlab,
            ]);
        }

        $action = 'Editou o animal ' . $animal->animal_name;
        if ($codlabAlterado) {
            $action .= sprintf(
                ' | Codlab alterado manualmente: "%s" → "%s"',
                $codlabAnterior !== null && $codlabAnterior !== '' ? $codlabAnterior : '(vazio)',
                $codlabNovo !== null && $codlabNovo !== '' ? $codlabNovo : '(vazio)'
            );

            LogFacade::channel('animais_edit')->info('Troca manual de codlab (edição de animal)', [
                'animal_id' => (int) $id,
                'animal_name' => $animal->animal_name,
                'codlab_anterior' => $codlabAnterior,
                'codlab_novo' => $codlabNovo,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'order_id' => $animal->order_id,
                'ordem_servico_id' => $ordem?->id,
            ]);
        }

        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => $action,
            'animal' => $animal->animal_name,
            'order_id' => $animal->order_id ?? null,
            'ordem_id' => $ordem?->id,
        ]);




        return redirect()->route('animais')->with('success', 'Animal editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $animal = Animal::find($request->id);
        $animal->delete();
        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Deletou o animal ' . $animal->animal_name,
            'animal' => $animal->animal_name,
            'order_id' => $animal->order_id ?? null,
        ]);
        return response()->json(['success' => 'Animal deletado com sucesso!']);
    }
    public function search(Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->route('animais', $this->extractAnimalFilters($request));
        }

        $filters = $this->extractAnimalFilters($request);
        if ($request->filled('search') && ! $request->filled('nome')) {
            $request->merge(['nome' => $request->search]);
            $filters['nome'] = $request->search;
        }

        $animais = $this->filterAnimaisQuery($request)->paginate(20)->withQueryString();
        $statusOptions = self::animalStatusOptions();

        return response()->json([
            'viewRender' => view('admin.animais.includes.table-rows', compact('animais', 'statusOptions'))->render(),
            'pagination' => $animais->links()->toHtml(),
            'total' => $animais->total(),
        ]);
    }
    public function showStatus($id)
    {
        $animal = Animal::find($id);
        return view('admin.animais.status-edit', get_defined_vars());
    }

    public function getStatus($id)
    {
        $animal = Animal::find($id);
        return response()->json($animal);
    }
    public function statusUpdate(Request $request, $id)
    {
        $animal = Animal::find($id);
        $animal->update($request->all());
        return redirect()->route('animais')->with('success', 'Status editado com sucesso!');
    }

    public function getAnimal(Request $request)
    {
        $animais = Animal::where('animal_name', $request->q);
        return response()->json($animais);
    }

    public function getPai(Request $request)
    {
        $animais = Animal::where('registro_pai', $request->registro)->first();
        return response()->json($animais);
    }

    public function getMae(Request $request)
    {
        $animais = Animal::where('registro_mae', $request->registro)->first();
        return response()->json($animais);
    }

    public function getRegistros(Request $request)
    {
        $query = $request->get('query');
        $data = Animal::where('animal_name', 'like', "%{$query}%")->take(20)->get();

        return response()->json($data);
    }

    public function buscarAnimal(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            $animais = Animal::where('animal_name', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }
        return response()->json($animais);
    }


    public function searchCodLab(Request $request)
    {
        if (! $request->ajax()) {
            return redirect()->route('animais', array_merge(
                $this->extractAnimalFilters($request),
                ['codlab' => $request->codlab]
            ));
        }

        $animais = $this->filterAnimaisQuery($request)->paginate(20)->withQueryString();
        $statusOptions = self::animalStatusOptions();

        if ($animais->isEmpty()) {
            return response()->json(['error' => 'Nenhum animal encontrado com os filtros informados.']);
        }

        return response()->json([
            'viewRender' => view('admin.animais.includes.table-rows', compact('animais', 'statusOptions'))->render(),
            'pagination' => $animais->links()->toHtml(),
            'total' => $animais->total(),
        ]);
    }

    private function extractAnimalFilters(Request $request): array
    {
        return $request->only([
            'nome',
            'codlab',
            'especies',
            'breed',
            'status',
            'sex',
            'sem_codlab',
            'registro',
            'identificador',
        ]);
    }

    private function filterAnimaisQuery(Request $request)
    {
        $query = Animal::query()->orderByDesc('id');

        if ($request->filled('nome')) {
            $query->where('animal_name', 'LIKE', '%' . trim($request->nome) . '%');
        }

        if ($request->filled('codlab')) {
            $query->where('codlab', 'LIKE', '%' . trim($request->codlab) . '%');
        }

        if ($request->filled('especies')) {
            $query->where('especies', $request->especies);
        }

        if ($request->filled('breed')) {
            $query->where('breed', trim($request->breed));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        if ($request->filled('registro')) {
            $term = trim($request->registro);
            $query->where(function ($q) use ($term) {
                $q->where('number_definitive', 'LIKE', "%{$term}%")
                    ->orWhere('register_number_brand', 'LIKE', "%{$term}%");
            });
        }

        if ($request->filled('identificador')) {
            $query->where('identificador', 'LIKE', '%' . trim($request->identificador) . '%');
        }

        if ($request->boolean('sem_codlab')) {
            $query->where(function ($q) {
                $q->whereNull('codlab')->orWhere('codlab', '');
            });
        }

        return $query;
    }

    private function distinctAnimalValues(string $column)
    {
        return Animal::query()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }

    public static function animalStatusOptions(): array
    {
        return [
            1 => ['label' => 'Aguardando amostra', 'class' => 'secondary'],
            2 => ['label' => 'Amostra recebida', 'class' => 'info'],
            3 => ['label' => 'Em análise', 'class' => 'warning'],
            4 => ['label' => 'Análise concluída', 'class' => 'primary'],
            5 => ['label' => 'Resultado disponível', 'class' => 'success'],
            6 => ['label' => 'Análise reprovada', 'class' => 'danger'],
            7 => ['label' => 'Análise aprovada', 'class' => 'success'],
            8 => ['label' => 'Recoleta solicitada', 'class' => 'warning'],
            9 => ['label' => 'Amostra paga', 'class' => 'info'],
            10 => ['label' => 'Pedido concluído', 'class' => 'dark'],
        ];
    }

    /**
     * Show the form for transferring an animal to a new owner.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTransfer($id)
    {
        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()->route('animais')->with('error', 'Animal não encontrado!');
        }

        $owners = Owner::all();
        $currentOwner = Owner::find($animal->owner_id);

        return view('admin.animais.transfer', compact('animal', 'owners', 'currentOwner'));
    }

    /**
     * Transfer the animal to a new owner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request, $id)
    {
        $request->validate([
            'new_owner_id' => 'required|exists:owners,id'
        ]);

        $animal = Animal::find($id);
        if (!$animal) {
            return redirect()->route('animais')->with('error', 'Animal não encontrado!');
        }

        $oldOwner = Owner::find($animal->owner_id);
        $newOwner = Owner::find($request->new_owner_id);

        if ($animal->owner_id == $request->new_owner_id) {
            return redirect()->route('animais')->with('error', 'O animal já pertence a este proprietário!');
        }

        // Atualizar o owner_id do animal
        $animal->update(['owner_id' => $request->new_owner_id]);

        // Atualizar registros relacionados
        $this->updateRelatedRecords($animal, $request->new_owner_id);

                // Criar log da transferência
        $logAction = 'Transferiu o animal ' . $animal->animal_name . ' de ' .
                     ($oldOwner ? $oldOwner->owner_name : 'Proprietário desconhecido') .
                     ' para ' . $newOwner->owner_name;

        if ($request->observacoes) {
            $logAction .= ' - Observações: ' . $request->observacoes;
        }

        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => $logAction,
            'animal' => $animal->animal_name,
            'order_id' => $animal->order_id ?? null,
        ]);

        return redirect()->route('animais')->with('success', 'Animal transferido com sucesso para ' . $newOwner->owner_name . '!');
    }

    /**
     * Update related records when transferring an animal.
     *
     * @param  Animal  $animal
     * @param  int  $newOwnerId
     * @return void
     */
    private function updateRelatedRecords(Animal $animal, $newOwnerId)
    {
        // Atualizar Ordem de Serviço
        $ordemServico = OrdemServico::where('animal_id', $animal->id)->first();
        if ($ordemServico) {
            $ordemServico->update(['owner_id' => $newOwnerId]);
        }

        // Atualizar Laudos
        $laudos = Laudo::where('animal_id', $animal->id)->get();
        foreach ($laudos as $laudo) {
            $laudo->update(['owner_id' => $newOwnerId]);
        }

        // Atualizar Order Request se necessário
        if ($animal->order_id) {
            $orderRequest = OrderRequest::find($animal->order_id);
            if ($orderRequest && $orderRequest->owner_id != $newOwnerId) {
                // Só atualizar se for o único animal do pedido
                $animalsInOrder = Animal::where('order_id', $animal->order_id)->count();
                if ($animalsInOrder == 1) {
                    $orderRequest->update(['owner_id' => $newOwnerId]);
                }
            }
        }
    }

    /**
     * Search owners for transfer modal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchOwners(Request $request)
    {
        $query = $request->get('q');
        $owners = Owner::where('owner_name', 'like', "%{$query}%")
                      ->orWhere('document', 'like', "%{$query}%")
                      ->limit(10)
                      ->get();

        return response()->json($owners);
    }
}
