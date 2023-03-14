@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="lt-2 mt-5">
        <div class="container">
            <form action="{{ route('vet.animal.select.store') }}" method="post">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                @csrf
                <div class="my-4">
                    <h3>Selecione o Animal</h3>
                    <select class="js-example-basic-single" name="animal_id">
                        @foreach ($animals as $animal)
                            <option value="{{ $animal->id }}">{{ $animal->animal_name }}</option>
                        @endforeach
                    </select>
                    <div class="my-3">
                        <button type="submit" class="btn btn-primary btn-create">CONTINUAR</button>
                    </div>
                </div>
            </form>
            <div class="text-center">
                <p>Não encontrou o animal <a href="{{ route('vet.owner.create') }}">Clique aqui!</a></p>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $('.btn-select').click(function() {
                window.location.href = "{{ route('vet.select') }}";
            });
    


        });
    </script>
@endsection
