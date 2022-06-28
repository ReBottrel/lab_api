<?php

namespace App\Http\Controllers;

use App\Models\Marking;
use Illuminate\Http\Request;

class MarkingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.padmin', ['except' => ['markGet']]);
    }

    public function markGet(Request $request, $id = null)
    {
        if($id){
            $data = Marking::where('id',$id)->first();
        }else{
            $data = Marking::paginate($request->per_page ?? 20);
            $data->map(function($query){
                $query->mark_path = asset($query->mark_path);
                return $query;
            });
        }
        return response()->json($data, 200);
    }

    public function markPost(Request $request)
    {
        \DB::beginTransaction();

        try{
            throw_unless(
                isset($request->marks),
                \Exception::class, 'a chave "marks" não está presente!'
            );

            $path = 'app/public/marks/';
            $originalPath = storage_path($path);
            if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);

            $markings = collect();

            collect($request->marks)->map(function($query) use($path, $markings) {
                $new_query = $query;
                if(isset($query['mark_image']) && strpos($query['mark_image'], ';base64')){
                    $base64 = $query['mark_image'];
                    //obtem a extensão
                    $extension = explode('/', $base64);
                    $extension = explode(';', $extension[1]);
                    $extension = '.'.$extension[0];
                    //gera o nome
                    $name = \Str::random(20).$extension;
                    //obtem o arquivo
                    $separatorFile = explode(',', $base64);
                    $file = $separatorFile[1];
                    //envia o arquivo
                    \Storage::put(str_replace('app/', '', $path).$name, base64_decode($file));
                    $new_query['mark_path'] = str_replace('app/public', 'storage', $path).$name;
                }
                unset($new_query['mark_image']);

                $mark = Marking::create($new_query);
                $markings->add($mark);
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json($markings, 200);
    }

    public function markPut(Request $request)
    {
        \DB::beginTransaction();

        try{
            throw_unless(
                isset($request->marks),
                \Exception::class, 'a chave "marks" não está presente!'
            );

            $path = 'app/public/marks/';
            $originalPath = storage_path($path);
            if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);

            $markings = collect();

            collect($request->marks)->map(function($query) use($path, $markings) {
                $new_query = $query;
                if(isset($query['id'])){
                    if(isset($query['mark_image']) && strpos($query['mark_image'], ';base64')){
                        $base64 = $query['mark_image'];
                        //obtem a extensão
                        $extension = explode('/', $base64);
                        $extension = explode(';', $extension[1]);
                        $extension = '.'.$extension[0];
                        //gera o nome
                        $name = \Str::random(20).$extension;
                        //obtem o arquivo
                        $separatorFile = explode(',', $base64);
                        $file = $separatorFile[1];
                        //envia o arquivo
                        \Storage::put(str_replace('app/', '', $path).$name, base64_decode($file));
                        $new_query['mark_path'] = str_replace('app/public', 'storage', $path).$name;
                        
                    }
                    unset($new_query['mark_image']);

                    $mark_find = Marking::find($query['id']);
                    \Storage::delete(str_replace('storage', 'public', $mark_find->mark_path));
                    $mark_find->update($new_query);
                    $markings->add(Marking::find($query['id']));
                }
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json($markings, 200);
    }

    public function markDelete($id)
    {
        if(Marking::where('id',$id)->get()->count() == 0) return response()->json('Registro Inexistente!');
        $marking = Marking::where('id', $id)->get()->each(function($query){
            \Storage::delete(str_replace('storage','public',$query->mark_path));
            $query->delete();
        });

        return response()->json('Registro Apagado!');
    }
}
