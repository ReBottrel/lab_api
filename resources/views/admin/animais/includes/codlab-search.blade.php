@include('admin.animais.includes.table-rows', ['animais' => $animais ?? $animals, 'statusOptions' => \App\Http\Controllers\Admin\AnimaisController::animalStatusOptions()])
