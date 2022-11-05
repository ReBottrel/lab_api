<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $prices = [00.05, 500.00, 400.00, 300.00, 200.00];
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', $user)->where('status', 2)->get();
        // dd($orders);
        return view('user.dashboard', get_defined_vars());
    }

    public function paymentMethod(Request $request)
    {
        $prices = [00.05, 500.00, 400.00, 300.00, 200.00];

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
        foreach ($order->orderRequestPayment as $payment) {
            if ($payment->days == 0) {
                $payment->update([
                    'value' => 00.05,
                ]);
            }
            if ($payment->days == 1) {
                $payment->update([
                    'value' => 500.00,
                ]);
            }
            if ($payment->days == 2) {
                $payment->update([
                    'value' => 400.00,
                ]);
            }
            if ($payment->days == 3) {
                $payment->update([
                    'value' => 300.00,
                ]);
            }
            if ($payment->days == 4) {
                $payment->update([
                    'value' => 200.00,
                ]);
            }
        }
        

        return view('user.payment', get_defined_vars());
    }
}
