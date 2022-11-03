@foreach ($owners as $owner)
    <tr>
        <td>{{ $owner->owner_name }}</td>
        <td>{{ strtolower($owner->email) }}</td>
        <td>
            <a href="{{ route('owner.edit', $owner->id) }}" class="btn btn-sm btn-primary">Editar</a>
            <a href="{{ route('get.owners.details', $owner->id) }}" class="btn btn-sm btn-success">Detalhes</a>
            @if ($owner->user_id)
                <a href="{{ route('owner.user', $owner->user_id) }}"><button type="button"
                        class="btn btn-sm btn-alt-loci text-white">Ver Usuário</button></a>
            @else
                <button type="button" data-id="{{ $owner->id }}" class="btn btn-sm btn-info create-access">Criar
                    Acesso</button>
            @endif

            @if ($owner->old_id)
                <a href="{{ route('get.animals', $owner->old_id) }}"><button type="button"
                        class="btn btn-sm btn-warning my-2">Ver animais</button></a>
            @else
                <a href="{{ route('get.animals', $owner->id) }}"><button type="button"
                        class="btn btn-sm btn-warning my-2">Ver animais</button></a>
            @endif
        </td>
    </tr>
@endforeach
