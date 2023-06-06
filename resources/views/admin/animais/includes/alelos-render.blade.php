 @foreach ($marcadores as $item)
     <div class="row">
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Marcador</label>
             <input type="text" class="form-control" value="{{ $item->gene }}" name="marcador[]">
         </div>
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Alelo 1</label>
             <input type="text" class="form-control" name="alelo1[]">
         </div>
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Alelo 2</label>
             <input type="text" class="form-control" name="alelo2[]">
         </div>
     </div>
 @endforeach
