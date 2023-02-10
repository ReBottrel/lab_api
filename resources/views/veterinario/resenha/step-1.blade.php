@extends('layouts.veterinario')

@section('content')
    <canvas id="c"></canvas>

    <div class="bg-white">
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
@endsection

@section('js')
    <script>
        var canvas = new fabric.Canvas('c');
        canvas.selection = false;
        canvas.setWidth(window.innerWidth);
        canvas.setHeight(600);

        // Adicionar imagem de fundo
        var imgElement = new Image();
        imgElement.src = '{{ asset('vet/img/step-1.png') }}';
        imgElement.onload = function() {
            var imgInstance = new fabric.Image(imgElement, {
                width: canvas.getWidth(),
                height: canvas.getHeight(),
                selectable: false,
                evented: false
            });
            canvas.add(imgInstance);
            canvas.sendToBack(imgInstance);
        };


        window.addEventListener('resize', function() {
            imgInstance.set({
                width: canvas.getWidth(),
                height: canvas.getHeight()
            });
            canvas.renderAll();
        });

        canvas.on('mouse:down', function (options) {
        var pointer = canvas.getPointer(options.e);
        var points = [pointer.x, pointer.y, pointer.x, pointer.y];

        var line = new fabric.Line(points, {
          strokeWidth: 5,
          stroke: 'black',
          originX: 'center',
          originY: 'center',
          selectable: false,
        });

        canvas.add(line);

        canvas.on('mouse:move', function (options) {
          var pointer = canvas.getPointer(options.e);
          line.set({ x2: pointer.x, y2: pointer.y });
          canvas.renderAll();
        });
      });

      canvas.on('mouse:up', function (options) {
        canvas.off('mouse:move');
      });

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
