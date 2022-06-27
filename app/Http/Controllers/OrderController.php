<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderRequestGet(Request $request, $id = null)
    {
        $user = user_token();
        $data = OrderRequest::with('user')->paginate($request->per_page ?? 20);
        if($id) $data = OrderRequest::with('user')->where('id',$id)->first();
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
