<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exames = Exam::paginate(6);
        return view('admin.exames', get_defined_vars());
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
        // dd($request->all());
        $dados = Exam::create([
            'category' => $request->category,
            'animal' => $request->animal,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'value' => str_replace(['.', ','], ['', '.'], $request->value),
            'extra_value' => str_replace(['.', ','], ['', '.'], $request->extra_value),

        ]);
        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Criou um novo exame',
        ]);

        return redirect()->back()->with('success', 'Exame criado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exames = Exam::find($id);

        return response()->json($exames);
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
    public function update(Request $request)
    {
        $exame = Exam::find($request->id);
        $exame->update([
            'category' => $request->category,
            'animal' => $request->animal,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'value' => str_replace(['.', ','], ['', '.'], $request->value),
            'extra_value' => str_replace(['.', ','], ['', '.'], $request->extra_value),

        ]);

        return redirect()->back()->with('success', 'Exame alterado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dado = Exam::find($id);
        $dado->delete();
        return redirect()->back()->with('success', 'Exame deletado com sucesso!');
    }
}
