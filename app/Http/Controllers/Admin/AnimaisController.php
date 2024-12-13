<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fur;
use App\Models\Log;
use App\Models\Laudo;
use App\Models\Animal;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\AnimalToParent;
use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

    public function index()
    {
        $animais = Animal::paginate();

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

        $codlab = $this->generateUniqueCodlab($sigla);
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

    private function generateUniqueCodlab($sigla)
    {
        // Buscar o último animal criado com esta sigla, ordenado pela data de criação
        $lastAnimal = Animal::latest('created_at')
            ->first();

        if ($lastAnimal) {
            // Extrair o número do codlab do último animal
            $lastNumber = (int) substr($lastAnimal->codlab, 3);
            $nextNumber = $lastNumber + 1;
            
     
        } else {
            // Se não existir nenhum animal com esta sigla, começar do 200000
            $nextNumber = 200000;
            
        }

        // Verificar se o próximo número já existe (por segurança)
        while (Animal::where('codlab', $sigla . strval($nextNumber))->exists()) {
            $nextNumber++;
        }

        return $sigla . strval($nextNumber);
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

        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Editou o animal ' . $animal->animal_name,
            'animal' => $animal->animal_name,
            'order_id' => $animal->order_id ?? null,
            'ordem_id' => $ordem->id ?? null,
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
        if ($request->ajax()) {
            $animais = Animal::where('animal_name', 'LIKE', '%' . $request->search . "%")->get();
            $viewRender = view('admin.animais.includes.render', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
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
        if ($request->ajax()) {
            $codlab = $request->codlab;

            $animals = Animal::where('codlab', 'LIKE', '%' . $codlab . '%')
                ->get();

            if ($animals) {
                $viewRender = view('admin.animais.includes.codlab-search', compact('animals'))->render();

                return response()->json(['viewRender' => $viewRender]);
            } else {
                return response()->json(['error' => 'Animal não encontrado.']);
            }
        }
    }
}
