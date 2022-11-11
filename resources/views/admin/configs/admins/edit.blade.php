@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Configurações</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        Criar usuário adm
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('config.update.admin', $admin->id) }}" method="post">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="name">Nome</label>
                                                <input type="text" name="name" value="{{ $admin->name }}" id="name" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">E-mail</label>
                                                <input type="email" name="email" value="{{ $admin->email }}" id="email" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Senha</label>
                                                <input type="password" name="password" id="password" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Senha</label>
                                                <input type="password" name="password-confirm" id="password-confirm"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name">Selecionar permissão</label>
                                                <select class="form-select" aria-label="Default select example">

                                                    <option value="1">Administrador</option>
                                                    <option value="2">Financeiro</option>
                                                    <option value="3">Técnico</option>
                                                </select>
                                            </div>
                                            <div class="my-3 text-center">
                                                <button type="submit" class="btn btn-success">Editar
                                                    usuário</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
  
@endsection
