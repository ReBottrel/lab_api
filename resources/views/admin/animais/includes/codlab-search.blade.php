
<tr>
    <td>{{ $animal->animal_name }}</td>
    <td> {{ $animal->breed }} </td>
    <td>
        {{ $animal->especies ?? 'Sem especie' }}
    </td>
    <td>
        {{ $animal->codlab ?? 'Sem codlab' }}
    </td>
    <td>
        <div class="dropdown">
            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ações
            </a>

            <ul class="dropdown-menu">
                <a href="{{ route('animais.show', $animal->id) }}" class="dropdown-item">Editar</a>
                <a href="{{ route('animais.status', $animal->id) }}" class="dropdown-item">Editar Status</a>
            </ul>
        </div>

    </td>
</tr>

