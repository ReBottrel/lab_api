@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Transferir Animal</h3>
                </div>
                <div class="card-body">
                    <!-- Informações do Animal -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Informações do Animal</h5>
                                    <p><strong>Nome:</strong> {{ $animal->animal_name }}</p>
                                    <p><strong>Codlab:</strong> {{ $animal->codlab ?? 'Sem codlab' }}</p>
                                    <p><strong>Espécie:</strong> {{ $animal->especies ?? 'Não informado' }}</p>
                                    <p><strong>Raça:</strong> {{ $animal->breed ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Proprietário Atual</h5>
                                    @if($currentOwner)
                                        <p><strong>Nome:</strong> {{ $currentOwner->owner_name }}</p>
                                        <p><strong>Documento:</strong> {{ $currentOwner->document }}</p>
                                        <p><strong>Email:</strong> {{ $currentOwner->email ?? 'Não informado' }}</p>
                                        <p><strong>Telefone:</strong> {{ $currentOwner->fone ?? 'Não informado' }}</p>
                                    @else
                                        <p class="text-muted">Sem proprietário definido</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Transferência -->
                    <form action="{{ route('animais.transfer.store', $animal->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="new_owner_id" class="form-label">Novo Proprietário</label>
                            <select class="form-select" id="new_owner_id" name="new_owner_id" required>
                                <option value="">Selecione um proprietário</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $owner->id == $animal->owner_id ? 'disabled' : '' }}>
                                        {{ $owner->owner_name }} - {{ $owner->document }}
                                        {{ $owner->id == $animal->owner_id ? ' (Proprietário Atual)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações (opcional)</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3"
                                      placeholder="Adicione observações sobre a transferência..."></textarea>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <strong>Atenção:</strong> Esta ação irá transferir o animal para o novo proprietário e atualizar todos os registros relacionados (laudos, ordens de serviço, etc.).
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('animais') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Transferir Animal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Inicializar Select2 para melhor UX
        if ($.fn.select2) {
            $('#new_owner_id').select2({
                placeholder: 'Buscar proprietário...',
                allowClear: true,
                ajax: {
                    url: '{{ route('search.owners') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(owner) {
                                return {
                                    id: owner.id,
                                    text: owner.owner_name + ' - ' + owner.document
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });
        }
    });
</script>
@endsection
