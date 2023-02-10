@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column justify-content-center">
                <div>
                    <canvas id="c" style="margin-left: 50px;"></canvas>
                </div>
                <div>
                    <button class="btn btn-alt-loci text-white" id="salvar">SALVAR</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 bg-white">
            <div>
                <p>Adicionar imagem</p>
                <div>
                    <button class="btn btn-primary" onclick="addImage()">Imagem 1</button>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="addCircle()">Imagem 2</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var canvas = new fabric.Canvas('c');
        canvas.setWidth(800);
        canvas.setHeight(700);
        canvas.renderAll();
        canvas.setBackgroundImage('{{ asset('adm/assets/img/ld-dir.png') }}', canvas.renderAll.bind(canvas), {
            width: canvas.width,
            height: canvas.height,
            // originX: 'left',
            // originY: 'top',
            scaleX: 1.3,
            scaleY: 1.3
        });
        // Adicione um quadrado
        function addImage() {
            var imgElement = new Image();
            imgElement.src = '{{ asset('adm/assets/img/donkey.png') }}';
            imgElement.onload = function() {
                var image = new fabric.Image(imgElement, {
                    left: 100,
                    top: 100,
                    width: 100,
                    height: 100
                });
                canvas.add(image);
            };
        }

        function addCircle() {
            var circle = new fabric.Circle({
                left: 200,
                top: 100,
                fill: 'green',
                radius: 25
            });
            canvas.add(circle);
        }

        // Salvar
        $('#salvar').click(function() {
            var canvasImage = canvas.toDataURL({
                format: 'png',
                quality: 1
            });
            console.log(canvasImage)
            $.ajax({
                url: '{{ route('teste.draw') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: canvasImage
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
@endsection
