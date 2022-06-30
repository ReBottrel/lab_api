<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;

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
            if(isset($data->data_g['animal_id'])) $data->animal = Animal::with('owner', 'resenhas')->find($data->data_g['animal_id']);
        }else{
            $data = $data->paginate($request->per_page ?? 20);
            $data->map(function($query){
                if(isset($query->data_g['animal_id'])) $query->animal = Animal::with('owner', 'resenhas')->find($query->data_g['animal_id']);
                return $query;
            });
        }
        
        return response()->json($data, 200);
    }

    public function orderRequestPost(Request $request)
    {
        $user = user_token();
        $order_request = collect($request->all())->put('origin', 'site')->put('user_id', $user->id);
        $order_request = OrderRequest::create($order_request->toArray());
        foreach(($request->data_g['exam_id'] ?? []) as $exam_id){
            $animal = Animal::find($request->data_g['animal_id'] ?? 0);

            $exam = Exam::find($exam_id);
            $create_orp['order_request_id'] = $order_request->id;
            $create_orp['owner_name'] = $animal->owner->owner_name;
            $create_orp['email'] = $animal->owner->email;
            $create_orp['location'] = $animal->animal_location;
            $create_orp['exam_id'] = $exam_id;
            $create_orp['category'] = $exam->category;
            $create_orp['animal'] = $exam->animal;
            $create_orp['title'] = $exam->title;
            $create_orp['short_description'] = $exam->short_description;
            $create_orp['value'] = $exam->value;
            $create_orp['requests'] = $exam->requests;
            $create_orp['extra_value'] = $exam->extra_value;
            $create_orp['extra_requests'] = $request->extra_requests ?? 0;
            OrderRequestPayment::create($create_orp);
        }

        return response()->json(OrderRequest::with('user', 'orderRequestPayment')->find($order_request->id));
    }

    public function labOrderPut(Request $request)
    {
        if(isset($request->id)){
            if(isset($request->update_status)) OrderRequest::find($request->id)->update(['status' => $request->update_status]);

            return response()->json(OrderRequest::find($request->id));
        }
    }
}
