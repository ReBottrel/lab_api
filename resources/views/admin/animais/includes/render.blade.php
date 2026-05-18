@include('admin.animais.includes.table-rows', ['animais' => $animais, 'statusOptions' => \App\Http\Controllers\Admin\AnimaisController::animalStatusOptions()])
