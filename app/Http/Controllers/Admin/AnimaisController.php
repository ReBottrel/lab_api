<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animal;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $animal->update($request->all());
        return redirect()->route('animais')->with('success', 'Animal editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $animal = Animal::find($id);
        $animal->delete();
        return redirect()->route('animais')->with('success', 'Animal excluído com sucesso!');
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
        $animais = Animal::where('register_number_brand', $request->registro)->first();
        return response()->json($animais);
    }

    public function getRegistros(Request $request)
    {
        $query = $request->get('query');
        $data = Animal::where('animal_name', 'like', "%{$query}%")->take(20)->get();

        return response()->json($data);
    }
}
