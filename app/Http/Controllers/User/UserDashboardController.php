<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $prices = [00.02, 500.00, 400.00, 300.00, 200.00];
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', $user)->where('status', 2)->get();
        // dd($orders);
        return view('user.dashboard', get_defined_vars());
    }

    public function paymentMethod(Request $request)
    {
        $prices = [00.02, 500.00, 400.00, 300.00, 200.00];

        $order = OrderRequest::with('orderRequestPayment')->find($request->orderId);





        foreach ($request->days as $key2 => $day) {
            $dayexplodido = explode('-', $day);
            switch ($dayexplodido[0]) {
                case 0:
                    $value = 00.02;
                    break;
                case 1:
                    $value = 500.00;
                    break;
                case 2:
                    $value = 400.00;
                    break;
                case 3:
                    $value = 300.00;
                    break;
                case 4:
                    $value = 200.00;
                    break;
                default:
                    00.02;
            }
            $orderRequest = OrderRequestPayment::find($dayexplodido[1])->update([
                'days' => $dayexplodido[0],
                'value' => $value,
            ]);
        }



        foreach ($order->orderRequestPayment as $key => $payment) {
            $payment->update([
                'paynow' => in_array($payment->id, $request->paynow) ? 1 : 0,
            ]);
        }


        $value = 0;

        foreach ($order->orderRequestPayment as $pay) {
            if ($pay->paynow == 1) {
                $value += $pay->value;
            }
        }
        $order->update([
            'total' => $value,
        ]);

        return view('user.payment', get_defined_vars());
    }
}
