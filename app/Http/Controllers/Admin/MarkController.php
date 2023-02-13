<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = [
            1 => 'Linha superior',
            2 => 'Focinho',
            3 => 'Lado esquerdo',
            4 => 'Lado direito',
            5 => 'Linha inferior',
            6 => 'Membros posteriores visto posterior',
            7 => 'Membros anteriores visto posterior',
        ];

        $marcas = Marking::all();
        return view('admin.animais.marks', get_defined_vars());
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
        $path = $request->file('image')->store('image', 'public');;

        $mark = new Marking();
        $mark->mark_name = $request->mark_name;
        $mark->categorie = $request->categorie;
        $mark->mark_path = $path;
        $mark->save();
        return redirect()->route('marks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
