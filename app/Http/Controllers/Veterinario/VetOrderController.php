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
use Illuminate\Support\Facades\Mail;

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
        $pedido = PedidoAnimal::create([
            'owner_id' => $request->owner_id,
            'user_id' => auth()->user()->id,
            'status' => 20,
        ]);

        if ($request->prop == 1) {
            return redirect()->route('animal.create', $pedido->id);
        } else {
            return redirect()->route('vet.animal.select', $pedido->id);
        }
    }

    public function ownerSelect()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.order.select-owner', get_defined_vars());
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


        $ex = Exam::find($request->exame);
        $exam = ExamToAnimal::create([
            'exam_id' => $request->exame,
            'animal_id' => $pedido->id_animal,
            'order_id' => $pedido->id_pedido,
            'status' => 1,
            'total_price' => $ex->value,
        ]);


        // Envio do e-mail
        Mail::send('mails.nova_resenha', ['data' => $request->pedido], function ($message) {
            $message->to(auth()->user()->email,  auth()->user()->name)
                ->subject('Nova resenha cadastrada');
        });

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado com sucesso!',
        ]);
    }

    public function listItens(Request $request)
    {
        $pedidos = PedidoAnimal::where('owner_id', $request->owner)->where('id_animal', '!=', 0)->where('status', 20)->get();
        $owner = Owner::find($request->owner);
       
        return view('veterinario.order.order-new', get_defined_vars());
    }

    public function createNewOrder(Request $request)
    {
        $owner = Owner::find($request->owner);
        $order = OrderRequest::create([
            'owner_id' => $request->owner,
            'creator' => $owner->owner_name,
            'technical_manager' => auth()->user()->name,
            'user_id' => $owner->user_id,
            'id_tecnico' => auth()->user()->id,
            'status' => 1,
            'origin' => "app",
        ]);

        foreach ($request->pedidos as $pedido) {
            $pedido = PedidoAnimal::find($pedido);
            $pedido->update([
                'status' => 1,
                'id_pedido' => $order->id,
            ]);
            $animal = Animal::find($pedido->id_animal);
            $animal->update([
                'user_id' => $owner->user_id,
            ]);
        }

        return redirect()->route('vet.index')->with('success', 'Pedido criado com sucesso!');
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
