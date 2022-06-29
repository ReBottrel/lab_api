<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.padmin', ['except' => ['orderRequestGet', 'orderRequestPost']]);
    }

    public function orderRequestGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = OrderRequest::with('user')->where(function($query) use($user, $request){
            if(isset($request->filter)){
                foreach($request->filter as $filter){
                    $query = $query->where($filter['column'], ($filter['condition'] ?? '='), $filter['value']);
                }
            }
            if($user->permission !== 10) $query = $query->where('user_id', $user->id);
            return $query;
        });

        if($id) {
            $data = $data->where('id',$id)->first();
            if(isset($data->data_g['animal_id'])) $data->animal = Animal::with('owner', 'resenhas')->find($data->data_g['animal_id']);
        }else{
            $data = $data->paginate($request->per_page ?? 20);
            $data->map(function($query){
                if(isset($query->data_g['animal_id'])) $query->animal = Animal::with('owner', 'resenhas')->find($query->data_g['animal_id']);
                return $query;
            });
        }
        
        return response()->json($data, 200);
    }

    public function orderRequestPost(Request $request)
    {
        $user = user_token();
        $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id)->map(function($query){
            if(isset($query['exam_id'])) $query['exam'] = collect(Exam::find($query['exam_id']))->forget(['created_at', 'updated_at'])->toArray();
            return $query;
        });
        $order_request = OrderRequest::create($order_request->toArray());

        return response()->json(OrderRequest::with('user')->find($order_request->id));
    }

    public function labOrderPut(Request $request)
    {
        if(isset($request->id)){
            if(isset($request->update_status)) OrderRequest::find($request->id)->update(['status' => $request->update_status]);

            return response()->json(OrderRequest::find($request->id));
        }
    }
}
