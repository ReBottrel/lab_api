<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Exam;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $prices = [55.00, 500.00, 400.00, 300.00, 200.00];

        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', $user)->where('status', 2)->get();

        return view('user.dashboard', get_defined_vars());
    }

    public function ordersDone()
    {
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->whereHas('orderRequestPayment', function ($query) {
            $query->where('payment_status', 1);
        })->where('user_id', $user)->where('status', 2)->get();

        return view('user.orders-done', get_defined_vars());
    }
    public function ordersDoneDetail($id)
    {
        $order = OrderRequest::with('user', 'orderRequestPayment')->whereHas('orderRequestPayment', function ($query) {
            $query->where('payment_status', 1);
        })->find($id);

    
       

        return view('user.orders-done-detail', get_defined_vars());
    }

    public function paymentMethod(Request $request)
    {
        $prices = [55.00, 500.00, 400.00, 300.00, 200.00];

        $order = OrderRequest::with('orderRequestPayment')->find($request->orderId);




        foreach ($request->days as $key2 => $day) {
            $dayexplodido = explode('-', $day);
            $exame = Exam::find($dayexplodido[2]);
            $orderRequest = OrderRequestPayment::find($dayexplodido[1])->update([
                'days' => $dayexplodido[0],
                'value' => $exame->value,
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
    public function maintrance()
    {
        return view('user.maintrance');
    }
}
