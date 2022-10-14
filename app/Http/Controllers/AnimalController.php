<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;

class AnimalController extends Controller
{
    

    public function animalGet(Request $request, $id = null)
    {
        $user = user_token();
        if($id){
            $data = Animal::with('owner', 'resenhas')->where('id',$id)->where(function($query) use($user){
                if($user->permission !== 10) $query = $query->where('user_id', $user->id);
                return $query;
            })->first();
        }else{
            $data = Animal::with('owner', 'resenhas')->where(function($query) use($user){
                if($user->permission !== 10) $query = $query->where('user_id', $user->id);
                return $query;
            })->paginate($request->per_page ?? 20);
            $data->map(function($query){
                $query->resenhas = $query->resenhas->map(function($query){
                    $query->photo_path = asset($query->photo_path);
                    return $query;
                });
                return $query;
            });
        }
        return response()->json(($data ?? 'nenhum registro encontrado'), 200);
    }

    public function animalPost(Request $request)
    {
        \DB::beginTransaction();
        $user = user_token();

        try{
            $create_animal = collect($request->all())->put('user_id', $user->id)->forget(['owner', 'resenha_animals']);
            $animal = Animal::create($create_animal->toArray());
            $create_owner = collect($request->owner)->put('user_id', $user->id)->put('animal_id', $animal->id);
            Owner::create($create_owner->toArray());

            collect($request->resenha_animals)->map(function($query) use($user, $animal){
                $new_query = $query;
                $new_query['user_id'] = $user->id;
                $new_query['animal_id'] = $animal->id;
                if(isset($query['photo']) && strpos($query['photo'], ';base64')){
                    $path = 'app/public/user_'.$user->id.'/animal_'.$animal->id.'/';
                    $originalPath = storage_path($path);
                    if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);
                    $base64 = $query['photo'];
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
                    $new_query['photo_path'] = str_replace('app/public', 'storage', $path).$name;
                }
                unset($new_query['photo']);

                ResenhaAnimal::create($new_query);
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Animal::with('owner', 'resenhas')->find($animal->id), 200);
    }

    public function animalPut(Request $request)
    {
        \DB::beginTransaction();
        $user = user_token();

        try{
            $create_animal = collect($request->all())->put('user_id', $user->id)->forget(['owner', 'resenha_animals']);
            $animal = Animal::find($create_animal['id'])->update($create_animal->toArray());
            $create_owner = collect($request->owner);
            Owner::where('animal_id', $create_animal['id'])->update($create_owner->toArray());

            collect($request->resenha_animals)->map(function($query) use($user, $create_animal){
                $new_query = $query;
                $new_query['user_id'] = $user->id;
                $new_query['animal_id'] = $create_animal['id'];
                if(isset($query['photo']) && strpos($query['photo'], ';base64')){
                    $path = 'app/public/user_'.$user->id.'/animal_'.$create_animal['id'].'/';
                    $originalPath = storage_path($path);
                    if (!file_exists($originalPath)) mkdir($originalPath, 0777, true);
                    $base64 = $query['photo'];
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
                    $new_query['photo_path'] = str_replace('app/public', 'storage', $path).$name;
                    if(isset($query['id'])){
                        $resenha_animal = ResenhaAnimal::find($query['id']);
                        \Storage::delete(str_replace('storage', 'public', $resenha_animal->photo_path));
                    }
                }
                unset($new_query['photo']);

                if(isset($query['id'])){
                    ResenhaAnimal::find($query['id'])->update($new_query);
                }else{
                    ResenhaAnimal::create($new_query);
                }
            });

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json($e->getMessage(),422);
        }

        return response()->json(Animal::with('owner', 'resenhas')->find($request->id), 200);
    }

    public function animalDelete($id)
    {
        $user = user_token();
        if(Animal::where('id',$id)->where('user_id', $user->id)->count() == 0) return response()->json('Registro Inexistente!');
        Animal::where('id',$id)->where('user_id', $user->id)->delete();
        Owner::where('animal_id', $id)->where('user_id', $user->id)->delete();
        $resenha_animal = ResenhaAnimal::where('animal_id', $id)->where('user_id', $user->id)->get()->each(function($query){
            $path = explode('/', $query->photo_path);
            \Storage::deleteDirectory('public/'.$path[1].'/'.$path[2]);
            $query->delete();
        });

        return response()->json('Registro Apagado!');
    }
}
