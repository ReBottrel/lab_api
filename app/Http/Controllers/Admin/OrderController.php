<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function recivedOrder(Request $request, $id)
    {

        $order = OrderRequest::find($id);
        $order->update([
            'status' => $request->value,
        ]);

        return response()->json($order);
    }
}
