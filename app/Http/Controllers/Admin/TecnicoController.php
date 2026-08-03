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
        if (auth()->user()->permission == 10) {
            return response()->json($tecnicos);
        } else {
            return redirect()->route('order.create.painel');
        }
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
        $tecnico = Tecnico::find($id);
        $tecnico->delete();
        return response()->json($tecnico);
    }
    public function search(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Requisição inválida'], 400);
        }

        $search = trim((string) $request->search);

        if ($search === '') {
            $tecnicos = Tecnico::orderBy('professional_name')->limit(10)->get();
            $viewRender = view('admin.tecnicos.search', get_defined_vars())->render();

            return response()->json([get_defined_vars()]);
        }

        $digits = preg_replace('/\D+/', '', $search);

        $tecnicos = Tecnico::query()
            ->where(function ($query) use ($search, $digits) {
                $query->where('professional_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('document', 'LIKE', '%' . $search . '%')
                    ->orWhere('cell', 'LIKE', '%' . $search . '%')
                    ->orWhere('fone', 'LIKE', '%' . $search . '%')
                    ->orWhere('registro_profissional', 'LIKE', '%' . $search . '%');

                if ($digits !== '' && $digits !== $search) {
                    $query->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(COALESCE(cell, ''), '(', ''), ')', ''), '-', ''), ' ', ''), '.', '') LIKE ?",
                        ['%' . $digits . '%']
                    )->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(COALESCE(fone, ''), '(', ''), ')', ''), '-', ''), ' ', ''), '.', '') LIKE ?",
                        ['%' . $digits . '%']
                    )->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(COALESCE(document, ''), '.', ''), '-', ''), '/', '') LIKE ?",
                        ['%' . $digits . '%']
                    );
                }
            })
            ->orderBy('professional_name')
            ->limit(50)
            ->get();

        $viewRender = view('admin.tecnicos.search', get_defined_vars())->render();

        return response()->json([get_defined_vars()]);
    }
}
