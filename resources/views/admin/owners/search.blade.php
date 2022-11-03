@foreach ($owners as $owner)
    <tr>
        <td>{{ $owner->owner_name }}</td>
        <td>{{ strtolower($owner->email) }}</td>
        <td>
            <div class="dropdown">
                <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </a>

                <ul class="dropdown-menu">
                    <a href="{{ route('owner.edit', $owner->id) }}" class="dropdown-item">Editar</a>
                    <a href="{{ route('get.owners.details', $owner->id) }}" class="dropdown-item">Detalhes</a>
                    @if ($owner->user_id)
                        <a href="{{ route('owner.user', $owner->user_id) }}"><button type="button"
                                class="dropdown-item">Ver
                                Usuário</button></a>
                    @else
                        <button type="button" data-id="{{ $owner->id }}" class="dropdown-item create-access">Criar
                            Acesso</button>
                    @endif

                    @if ($owner->old_id)
                        <a href="{{ route('get.animals', $owner->old_id) }}"><button type="button"
                                class="dropdown-item">Ver
                                animais</button></a>
                    @else
                        <a class="dropdown-item" href="{{ route('get.animals', $owner->id) }}">
                            Ver animais</a>
                    @endif
                    <a href="#" class="dropdown-item">Propriedades</a>
                </ul>
            </div>
        </td>
    </tr>
@endforeach

