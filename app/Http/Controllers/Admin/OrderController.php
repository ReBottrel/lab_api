<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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

    public function animal(Request $request)
    {
        $order = OrderRequest::find($request->id);
        foreach ($order->data_g['data_table'] as $data) {

            Animal::create([
                'user_id' => $request->owner,
                'order_id' => $order->id,
                'animal_name' => $data['produto'],
                'register_number_brand' => $data['id'],
                'sex' => $data['sexo'],
                'birth_date' => $data['nascimento'],
            ]);
        }
        return view('admin.order-detail', get_defined_vars());
    }

    public function orderDetail($id)
    {
        $order = OrderRequest::find($id);
        return view('admin.order-detail', get_defined_vars());
    }

    public function owner($id)
    {
        $order = OrderRequest::find($id);
        $owners = Owner::get();
        return view('admin.owner', get_defined_vars());
    }

    public function orderRequestPost(Request $request)
    {
        // $user = user_token();
        // $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::with('user')->find($request->id);
        // dd($order_request->data_g['data_table']);
        $owner = Owner::find($request->owner);

        $order_request->update([
            'user_id' => $owner->user_id,
        ]);

        foreach ($order_request->data_g['data_table'] as $exam_id) {
            $exam = Exam::find(5);
            $orderPay = OrderRequestPayment::create([
                'order_request_id' => $request->id,
                'owner_name' => $owner->owner_name,
                'email' => $owner->email,
                'location' => $owner->propriety,
                'exam_id' => $exam->id,
                'category' => $exam->category,
                'animal' => $exam->animal,
                'title' => $exam->title,
                'short_description' => $exam->short_description,
                'value' => $exam->value,
                'requests' => $exam->requests,
                'extra_value' => $exam->extra_value,
                'extra_requests' => $request->extra_requests ?? 0,
            ]);
        }
        $ordernew = OrderRequest::with('user')->find($request->id);
        $data = [];
        $email = $owner->email;
        $senha = str_replace(['.', '-', '/'], ['', '', ''], $owner->document);


        Mail::to($email)->send(new \App\Mail\NewOrder($email, $senha));
        return view('admin.success-page', get_defined_vars());
    }
}
