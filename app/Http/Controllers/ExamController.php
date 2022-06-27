<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function examGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = Animal::with('owner', 'resenhas')->where('user_id', $user->id)->paginate($request->per_page ?? 20);
        if($id) $data = Animal::with('owner', 'resenhas')->where('id',$id)->where('user_id', $user->id)->first();
        return response()->json($data, 200);
    }

    public function examPost(Request $request)
    {
        \DB::beginTransaction();

        try{
            $create_exame = collect($request->all());
            $exame = Exam::create($create_exame->toArray());

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Exam::find($animal->id), 200);
    }

    public function examPut(Request $request)
    {
        \DB::beginTransaction();

        try{
            $create_exame = collect($request->all());
            $exame = Exam::find($create_exame['id'])->update($create_exame->toArray());

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Exam::find($request->id), 200);
    }

    public function examDelete($id)
    {
        $user = user_token();
        if(Exam::find($id)) return response()->json('Registro Inexistente!');
        Exam::find($id)->delete();

        return response()->json('Registro Apagado!');
    }
}
