<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamToAnimal;
use App\Models\PedidoAnimal;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class VetOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'owner_id' => 'required',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Precisa selecionar um proprietário para continuar!');
        }

        // dd($request->prop);

        $owner = Owner::find($request->owner_id);
        $user = User::where('id', $owner->user_id)->first();
        $order = OrderRequest::create([
            'user_id' => $user->id,
            'creator' => $owner->owner_name,
            'technical_manager' => auth()->user()->name,
            'owner_id' => $owner->id,
            'id_tecnico' => auth()->user()->id,
            'status' => 7,
        ]);


        if ($request->prop == 1) {
            return redirect()->route('animal.create', $order->id);
        } else {
            return redirect()->route('vet.animal.select', $order->id);
        }
    }
    public function createOrder($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = Animal::find($pedido->id_animal);
        $exames = Exam::where('category', 'sorologia')->get();
        return view('veterinario.order.create-order', get_defined_vars());
    }

    public function storeOrder(Request $request)
    {

        $pedido = PedidoAnimal::find($request->pedido);
        $order = OrderRequest::find($pedido->id_pedido);

        foreach ($request->exames as $exame) {
            $ex = Exam::find($exame);
            $exam = ExamToAnimal::create([
                'exam_id' => $exame,
                'animal_id' => $pedido->id_animal,
                'order_id' => $pedido->id_pedido,
                'status' => 1,
                'total_price' => $ex->value,
            ]);
        }

        $order->update([
            'status' => 1,
            'total_price' => $request->total,
            'origin' => 'app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado com sucesso!',
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
