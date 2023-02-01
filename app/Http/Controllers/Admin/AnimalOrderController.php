<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fur;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Specie;
use App\Models\FurParent;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\AnimalToParent;
use App\Http\Controllers\Controller;

class AnimalOrderController extends Controller
{

    public function storeAnimalParentesco(Request $request)
    {

        $order = OrderRequest::findOrFail($request->order);
        $owner = Owner::findOrFail($order->owner_id);
        $data = [
            'user_id' => $owner->user_id,
            'order_id' => $request->order,
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'chip_number' => $request->chip_number,
            'owner_id' => $owner->id,

        ];

        $animal = $request->id ? Animal::findOrFail($request->id) : new Animal();
        $animal->fill($data)->save();

        if ($request->mae) {
            $maes = [];

            foreach ($request->input('mae') as $index => $mae) {
                $maes[] = [
                    'animal_id' => $animal->id,
                    'animal_register' => $request->input('registro_mae')[$index],
                    'especies' => $request->input('especie_mae')[$index],
                    'animal_name' => $mae
                ];
            }

            foreach ($maes as $mae) {
                AnimalToParent::create($mae);
            }
        }
        if ($request->pai) {
            $pais = [];
            foreach ($request->input('pai') as $index => $pai) {
                $pais[] = [
                    'animal_id' => $animal->id,
                    'animal_register' => $request->input('registro_pai')[$index],
                    'especies' => $request->input('especie_pai')[$index],
                    'animal_name' => $pai
                ];
            }

            foreach ($pais as $pai) {
                AnimalToParent::create($pai);
            }
        }

        $route = '';
        switch ($order->tipo) {
            case 1:
                $route = 'admin.order-dna-animal';
                break;
            case 2:
                $route = 'admin.order-homozigose-animal';
                break;
            case 3:
                $route = 'admin.order-beta-caseina-animal';
                break;
            case 4:
                $route = 'admin.order-sorologia-animal';
                break;
            case 5:
                $route = 'admin.order-parentesco-animal';
                break;
            default:
                $route = 'admin.order-dna-animal';
                break;
        }

        return redirect()->route($route, $order->id)->with('success', 'Produto atualizado com sucesso');
    }

    public function storeAnimalHomozigose(Request $request)
    {
        $order = OrderRequest::findOrFail($request->order);
        $owner = Owner::findOrFail($order->owner_id);
        $data = [
            'user_id' => $owner->user_id,
            'order_id' => $request->order,
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'chip_number' => $request->chip_number,
            'owner_id' => $owner->id,
            'fur' => $request->fur,
            'pai' => $request->father_name,
            'mae' => $request->mother_name,
        ];

        $animal = $request->id ? Animal::findOrFail($request->id) : new Animal();
        $animal->fill($data)->save();

        $parents = FurParent::updateOrCreate(['animal_id' => $animal->id,], [
            'animal_id' => $animal->id,
            'father_fur' => $request->father_fur,
            'mother_fur' => $request->mother_fur,
            'mother_name' => $request->mother_name,
            'father_name' => $request->father_name,
        ]);


        $route = '';
        switch ($order->tipo) {
            case 1:
                $route = 'admin.order-dna-animal';
                break;
            case 2:
                $route = 'admin.order-homozigose-animal';
                break;
            case 3:
                $route = 'admin.order-beta-caseina-animal';
                break;
            case 4:
                $route = 'admin.order-sorologia-animal';
                break;
            case 5:
                $route = 'admin.order-parentesco-animal';
                break;
            default:
                $route = 'admin.order-dna-animal';
                break;
        }

        return redirect()->route($route, $order->id)->with('success', 'Produto atualizado com sucesso');
    }

    public function showHomozigose($id)
    {
        $animal = Animal::findOrFail($id);
        $order = OrderRequest::findOrFail($animal->order_id);
        $owner = Owner::findOrFail($order->owner_id);
        $species = Specie::get();
        $furs = Fur::get();
        $parents = FurParent::where('animal_id', $animal->id)->first();
        return view('admin.orders.edit-homozigose', get_defined_vars());
    }
    public function showDna($id)
    {
        $animal = Animal::findOrFail($id);
        $order = OrderRequest::findOrFail($animal->order_id);
        $owner = Owner::findOrFail($order->owner_id);
        $species = Specie::get();
        return view('admin.orders.edit-dna', get_defined_vars());
    }
    public function editHomozigose($id)
    {
        $animal = Animal::findOrFail($id);
        $order = OrderRequest::findOrFail($animal->order_id);
        $owner = Owner::findOrFail($order->owner_id);
        $species = Specie::get();
        $furs = Fur::get();
        $parents = FurParent::where('animal_id', $animal->id)->first();
        return response()->json(get_defined_vars());
    }
}
