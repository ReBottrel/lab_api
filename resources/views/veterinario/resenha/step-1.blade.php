@extends('layouts.veterinario')

@section('content')
    <canvas id="c"></canvas>

    <div class="bg-white">
        <div class="buttons justify-content-between p-2">
            <div>
                <button class="btn btn-primary" onclick="addImage()">Imagem 1</button>
            </div>
            <div>
                <button class="btn btn-primary" onclick="addCircle()">Imagem 2</button>
            </div>
            <div>
                <button class="btn btn-primary" onclick="draw()">Desenhar</button>
            </div>
            <div>
                <button class="btn btn-success" onclick="cancelDraw()">sair do desenho</button>
            </div>
            <div>
                <button class="btn btn-success" id="salvar">SALVAR</button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var canvas = new fabric.Canvas('c');
        canvas.selection = false;
        canvas.setWidth(window.innerWidth);
        canvas.setHeight(600);


        canvas.setBackgroundImage('{{ asset('vet/img/step-1.png') }}', function() {
            let img = canvas.backgroundImage;
            img.originX = 'left';
            img.originY = 'top';
            img.scaleX = canvas.getWidth() / img.width;
            img.scaleY = canvas.getHeight() / img.height;
            canvas.renderAll();
        });

        window.addEventListener('resize', function() {
            imgInstance.set({
                width: canvas.getWidth(),
                height: canvas.getHeight()
            });

            canvas.renderAll();
        });

        function draw() {
            canvas.isDrawingMode = true;
            canvas.freeDrawingBrush.width = 5;
            canvas.freeDrawingBrush.color = '#000';
            canvas.on('path:created', function(e) {

                e.path.set();
                canvas.renderAll();


            });
        }

        function cancelDraw() {
            canvas.isDrawingMode = false;
        }




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
