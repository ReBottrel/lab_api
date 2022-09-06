<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function order()
    {
        $orders = OrderRequest::where('status', '!=', 0)->get();
        return view('admin.order', get_defined_vars());
    }

    public function recivedOrder(Request $request, $id)
    {

        $order = OrderRequest::find($id);
        $order->update([
            'status' => $request->value,
        ]);

        return response()->json($order);
    }


    public function owner($id)
    {
        $order = OrderRequest::find($id);
        return view('admin.owner', get_defined_vars());
    }
}
