<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderRequestPayment;
use App\Models\paymentSet;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $prices = [55.00, 500.00, 400.00, 300.00, 200.00];
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', 4)->get();
        // dd($orders);
        return view('user.dashboard', get_defined_vars());
    }

    public function paymentMethod(Request $request)
    {

        $order = OrderRequest::with('orderRequestPayment')->find($request->orderId);
        $order->update([
            'total' => $request->totalprice
        ]);
        foreach ($order->orderRequestPayment as $key => $payment) {
            foreach ($request->days as $key2 => $day) {
                if ($key == $key2) {
                    $payment->update([
                        'days' => $day
                    ]);
                }
            }
        }
        foreach ($order->orderRequestPayment as $key => $payment) {
            $payment->update([
                'paynow' => in_array($payment->id, $request->paynow) ? 1 : 0,
            ]);
        }


        return view('user.payment', get_defined_vars());
    }
}
