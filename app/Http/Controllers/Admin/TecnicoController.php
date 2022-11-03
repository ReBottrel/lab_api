<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tecnicos = Tecnico::paginate(10);
        return view('admin.tecnicos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tecnicos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tecnicos = Tecnico::create($request->all());
        return response()->json($tecnicos);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tecnico = Tecnico::find($id);
        return view('admin.tecnicos.edit', get_defined_vars());
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
        $tecnico = Tecnico::find($id);
        $tecnico->update($request->all());
        return redirect()->route('techinicals')->with('success', 'Técnico atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $tecnicos = Tecnico::where('professional_name', 'LIKE', '%' . $request->search . "%")->get();;
            $viewRender = view('admin.tecnicos.search', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }
}
