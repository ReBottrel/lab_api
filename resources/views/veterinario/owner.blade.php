@extends('layouts.veterinario')
@section('content')
    @include('layouts.partials.vet-top')
    <div class="lt-2 mt-5">
        <div class="container">
            <div class="my-4">
                <h3>Selecione o proprietário</h3>
                <select class="js-example-basic-single" name="state">
                </select>
                <div class="my-3">
                    <button class="btn btn-primary btn-create">CONTINUAR</button>
                </div>
            </div>
            <div class="text-center">
                <p>Não encontrou o proprietário <a href="#">Clique aqui!</a></p>
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
            $('.btn-create').click(function() {
                window.location.href = "{{ route('animal.create') }}";
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
