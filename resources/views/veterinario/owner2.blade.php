@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="lt-2 mt-5">
        <div class="container">
            <form action="{{ route('vet.order.store') }}" method="post">
                <input type="hidden" name="prop" value="2">
                @csrf
                <div class="my-4">
                    <h3>Selecione o proprietário</h3>
                    <select class="js-example-basic-single" name="owner_id">
                        @foreach ($owners as $owner)
                            <option id="select-name" value="{{ $owner->id }}">{{ $owner->owner_name }}</option>
                        @endforeach
                    </select>
                    <div class="my-3">
                        <button type="submit" class="btn btn-primary btn-create">CONTINUAR</button>
                    </div>
                </div>
            </form>
            <div class="text-center">
                <p>Não encontrou o proprietário <a href="{{ route('vet.owner.create') }}">Clique aqui!</a></p>
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


            $('.btnNext').click(function() {
                var $this = $(this);
                var $current = $this.parents('fieldset');
                var $next = $current.next('fieldset');

                $current.animate({
                    opacity: 0
                }, 500, function() {
                    $current.addClass('hidden');
                    $next.removeClass('hidden');
                    $next.animate({
                        opacity: 1
                    }, 500);
                });
            });

            $('.btnPrev').click(function() {
                var $this = $(this);
                var $current = $this.parents('fieldset');
                var $prev = $current.prev('fieldset');

                $current.animate({
                    opacity: 0
                }, 500, function() {
                    $current.addClass('hidden');
                    $prev.removeClass('hidden');
                    $prev.animate({
                        opacity: 1
                    }, 500);
                });
            });


        });
    </script>
@endsection
