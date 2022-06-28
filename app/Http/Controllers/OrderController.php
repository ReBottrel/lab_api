<?php

namespace App\Http\Controllers;

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
        }else{
            $data = $data->paginate($request->per_page ?? 20);
        }
        
        return response()->json($data, 200);
    }

    public function orderRequestPost(Request $request)
    {
        $user = user_token();
        $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::create($order_request->toArray());

        return response()->json(OrderRequest::with('user')->find($order_request->id));
    }
}
