<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalToParent;
use App\Models\Fur;
use App\Models\OrdemServico;
use Illuminate\Http\Request;

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
        return redirect()->route('animais')->with('success', 'Animal cadastrado com sucesso!');
    }
    private function generateUniqueCodlab($sigla)
    {
        $startValue = 100000;
        $maxNumber = Animal::selectRaw('MAX(CAST(SUBSTRING(codlab, 4) AS UNSIGNED)) as max_num')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->first();

        if ($maxNumber !== null && $maxNumber->max_num !== null) {
            $startValue = $maxNumber->max_num + 1;
        }

        while (Animal::where('codlab', '=', $sigla . strval($startValue))->exists()) {
            $startValue += 1;
        }

        $codlab = $sigla . strval($startValue);

        return $codlab;
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
        return response()->json($animal);
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
        $animal = Animal::find($id);

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
        ]);

        $parent = AnimalToParent::where('animal_id', $id)->first();

        $parent->update([
            'pai' => $request->pai,
            'mae' => $request->mae,
        ]);



        $ordem = OrdemServico::where('animal_id', $id)->first();

        if ($ordem) {
            $ordem->update([
                'codlab' => $request->codlab,
            ]);
        }

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

            $animal = Animal::where('codlab', 'LIKE', '%' . $codlab . '%')
                ->first();

            if ($animal) {
                $viewRender = view('admin.animais.includes.codlab-search', compact('animal'))->render();

                return response()->json(['viewRender' => $viewRender]);
            } else {
                return response()->json(['error' => 'Animal não encontrado.']);
            }
        }
    }
}
