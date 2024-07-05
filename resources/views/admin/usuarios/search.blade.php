@foreach ($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ strtolower($user->email) }}</td>
 
        <td>
            <div class="dropdown">
                <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </a>

                <ul class="dropdown-menu">
                    {{-- <a href="{{ route('owner.edit', $user->id) }}" class="dropdown-item">Editar</a>         --}}
                    <a data-id="{{ $user->id }}" class="dropdown-item delete">Excluir</a>
                </ul>
            </div>
        </td>
    </tr>
@endforeach

