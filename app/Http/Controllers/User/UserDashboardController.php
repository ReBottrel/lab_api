<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Cupom;
use App\Models\Exam;
use App\Models\OrderRequestPayment;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', $user)->where('status', 2)->get();

        return view('user.dashboard', get_defined_vars());
    }
    public function orders($id)
    {
        $order = OrderRequest::with('user', 'orderRequestPayment')->find($id);
        return view('user.order', get_defined_vars());
    }

    public function ordersDone()
    {
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->whereHas('orderRequestPayment', function ($query) {
            $query->where('payment_status', 2);
        })->where('user_id', $user)->where('status', 2)->get();

        return view('user.orders-done', get_defined_vars());
    }
    public function ordersDoneDetail($id)
    {
        $order = OrderRequest::with('user', 'orderRequestPayment', 'animals')->find($id);

        return view('user.orders-done-detail', get_defined_vars());
    }

    public function updateValue(Request $request, $id)
    {

        $order = OrderRequest::find($id);
        $order->update([
            'total' => $request->value,
        ]);

        $product = OrderRequestPayment::find($request->product);
        $exame = Exam::find($request->exame);
        $product->update([
            'value' => $request->productValue,
            'title' => $exame->title,
            'short_description' => $exame->short_description,
            'exam_id' => $exame->id
        ]);

        return response()->json($product);
    }

    public function paymentMethod(Request $request)
    {

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
            'total' => $request->totalprice,
        ]);

        return view('user.payment', get_defined_vars());
    }

    public function discount(Request $request)
    {
        $order = OrderRequest::find($request->order_id);

        $cupom = Cupom::where('code', $request->cupom)->first();
        if ($cupom) {
            $total = $order->total - ($order->total * $cupom->value / 100);
         
            $order->update([
                'total' => $total,
            ]);
        }

        return response()->json($order);
    }

    public function maintrance()
    {
        return view('user.maintrance');
    }
}
