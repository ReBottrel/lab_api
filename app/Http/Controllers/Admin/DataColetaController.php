<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\DataColeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataColetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function updateData(Request $request)
    {
 
        $data = [];
        if ($request->has('data_recebimento')) {
            $data['data_recebimento'] = $request->data_recebimento;
        }
        if ($request->has('data_coleta')) {
            $data['data_coleta'] = $request->data_coleta;
        }
        if ($request->has('data_laboratorio')) {
            $data['hora_coleta'] = $request->data_laboratorio;
        }

        $datas = DataColeta::updateOrCreate(
            ['id_animal' => $request->id_animal],
            $data
        );
        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Atualizou as datas de coleta',
        ]);
        return response()->json($datas);
    }

    public function updateTipo(Request $request)
    {
        $data = [];
        if ($request->has('tipo')) {
            $data['tipo'] = $request->tipo;
        }

        $datas = DataColeta::updateOrCreate(
            ['id_animal' => $request->id_animal],
            $data
        );
        $log = Log::create([
            'user' => Auth::user()->name,
            'action' => 'Atualizou o tipo de coleta',
        ]);
        return response()->json($datas);
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
