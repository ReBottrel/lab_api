@extends('layouts.veterinario')

@section('content')
    @include('layouts.partials.vet-top')
    <canvas id="c"></canvas>

    <div class="images">
        @foreach ($marcas as $marca)
            <div id="add-image">
                <img src="{{ asset('storage/' . $marca->mark_path) }}" alt="">
            </div>
        @endforeach
    </div>
    <div class="bg-white">
        <div class="buttons justify-content-between p-2">

            <div>
                <button class="btn btn-primary" onclick="draw()">Desenhar</button>
            </div>
            <div>
                <button class="confirm-draw" onclick="cancelDraw()"><i class="fa-solid fa-circle-check"></i></button>
            </div>
            <div>
                <button class="undo-draw" onclick="undo()"><i class="fa-solid fa-eraser"></i></button>
            </div>
            <div>
                <button data-id="{{ $pedido->id_animal }}" data-pedido="{{ $pedido->id }}" class="btn btn-success" id="salvar">Próximo</button>
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
        canvas.on('object:added', function() {
            if (!isRedoing) {
                h = [];
            }
            isRedoing = false;
        });



        var isRedoing = false;
        var h = [];

        var deleteIcon =
            "data:image/svg+xml,%3C%3Fxml version='1.0' encoding='utf-8'%3F%3E%3C!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN' 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'%3E%3Csvg version='1.1' id='Ebene_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='595.275px' height='595.275px' viewBox='200 215 230 470' xml:space='preserve'%3E%3Ccircle style='fill:%23F44336;' cx='299.76' cy='439.067' r='218.516'/%3E%3Cg%3E%3Crect x='267.162' y='307.978' transform='matrix(0.7071 -0.7071 0.7071 0.7071 -222.6202 340.6915)' style='fill:white;' width='65.545' height='262.18'/%3E%3Crect x='266.988' y='308.153' transform='matrix(0.7071 0.7071 -0.7071 0.7071 398.3889 -83.3116)' style='fill:white;' width='65.544' height='262.179'/%3E%3C/g%3E%3C/svg%3E";
        var img = document.createElement('img');
        img.src = deleteIcon;

        fabric.Object.prototype.controls.deleteControl = new fabric.Control({
            x: 0.5,
            y: -0.5,
            offsetY: 16,
            cursorStyle: 'pointer',
            mouseUpHandler: deleteObject,
            render: renderIcon,
            cornerSize: 24
        });

        // Add();

        function deleteObject(eventData, transform) {
            var target = transform.target;
            var canvas = target.canvas;
            canvas.remove(target);
            canvas.requestRenderAll();
        }

        function renderIcon(ctx, left, top, styleOverride, fabricObject) {
            var size = this.cornerSize;
            ctx.save();
            ctx.translate(left, top);
            ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
            ctx.drawImage(img, -size / 2, -size / 2, size, size);
            ctx.restore();
        }
        canvas.backgroundColor = "rgba(0, 0, 0, 0)";
        canvas.setBackgroundImage('{{ asset('vet/img/step-6.png') }}', function() {
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
            $('.confirm-draw').show();
            $('.undo-draw').show();
        }

        function cancelDraw() {
            canvas.isDrawingMode = false;
            $('.confirm-draw').hide();
            $('.undo-draw').hide();
        }

        function undo() {
            if (canvas._objects.length > 0) {
                h.push(canvas._objects.pop());
                canvas.renderAll();
            }
        }



        function addImage(imgPath) {
            var imgElement = new Image();
            imgElement.src = imgPath;
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

        const addImageDivs = document.querySelectorAll("#add-image");

        addImageDivs.forEach(function(div) {
            div.addEventListener("click", function() {
                var imgSrc = this.querySelector("img").src;
                addImage(imgSrc);
            });
        });

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
            var animal = $(this).data('id');
            var pedido = $(this).data('pedido');
            var canvasImage = canvas.toDataURL({
                format: 'png',
                quality: 1
            });
            console.log(canvasImage)
            $.ajax({
                url: '{{ route('resenha.store.step1') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: canvasImage,
                    animal_id: animal,
                    pedido_id: pedido,
                    side: 6,
                },
                success: function(data) {
                    window.location.href = '{{ url('/vet/resenha-step-7') }}' + '/' + pedido;
                }
            });
        });
    </script>
@endsection
