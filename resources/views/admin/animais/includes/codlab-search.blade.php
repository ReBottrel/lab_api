@foreach ($animals as $animal)
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
                <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </a>

                <ul class="dropdown-menu">

                    <a href="{{ route('animais.show', $animal->id) }}" class="dropdown-item"><i
                            class="fa-solid fa-pen-to-square"></i> Editar</a>
                    <a data-id="{{ $animal->id }}" class="dropdown-item transferir-animal text-primary" role="button"><i
                            class="fa-solid fa-exchange-alt"></i> Transferir Animal</a>
                    <a data-id="{{ $animal->id }}" class="dropdown-item excluir-animal text-danger" role="button"><i
                            class="fa-solid fa-trash"></i> Excluir animal</a>
                </ul>
            </div>

        </td>
    </tr>
@endforeach
