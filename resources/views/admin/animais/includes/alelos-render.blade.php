 @foreach ($marcadores as $key => $item)
     @php
         $alelo1 = '';
         $alelo2 = '';
     @endphp
     @foreach ($animal->alelos as $alelo)
         @if ($alelo->marcador == $item->gene)
             @php
                 $alelo1 = $alelo->alelo1;
                 $alelo2 = $alelo->alelo2;
             @endphp
         @endif
     @endforeach

     <div class="row">
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Marcador</label>
             <input type="text" class="form-control" value="{{ $item->gene }}" name="marcador[]" readonly>
         </div>
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Alelo 1</label>
             <input type="text" class="form-control" name="alelo1[]" value="{{ $alelo1 }}">
         </div>
         <div class="mb-3 col-3">
             <label for="exampleFormControlInput1" class="form-label">Alelo 2</label>
             <input type="text" class="form-control" name="alelo2[]" value="{{ $alelo2 }}">
         </div>
     </div>
 @endforeach
