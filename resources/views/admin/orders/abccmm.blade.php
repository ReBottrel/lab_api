@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>BUSCAR PEDIDO NA ABCCMM</h3>
                <p>BUSCAR POR PEDIDO DE RESENHA</p>
                <form id="resenhaForm" action="{{ route('api.resenha.request') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="rowidcoleta" class="form-control" placeholder="Digite o número do pedido">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                    <p id="resenhaLoading" class="loading-message" style="display: none;">Buscando, por favor aguarde...</p>
                </form>
            </div>
            <div class="col-md-6">
                <h3>BUSCAR PEDIDO NA ABCCMM</h3>
                <p>BUSCAR POR PEDIDO DE COLETA</p>
                <form id="coletaForm" action="{{ route('api.coleta.request') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="rowidcoleta" class="form-control"
                            placeholder="Digite o número do pedido">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                    <p id="coletaLoading" class="loading-message" style="display: none;">Buscando, por favor aguarde...</p>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.getElementById('resenhaForm').addEventListener('submit', function() {
            document.getElementById('resenhaLoading').style.display = 'block';
        });

        document.getElementById('coletaForm').addEventListener('submit', function() {
            document.getElementById('coletaLoading').style.display = 'block';
        });
    </script>
@endsection
