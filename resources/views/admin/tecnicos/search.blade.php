@foreach ($tecnicos as $tecnico)
    <tr>
        <td>{{ $tecnico->professional_name }}</td>
        <td>{{ $tecnico->email }}</td>
        <td>{{ $tecnico->cell }}</td>
        <td>
            <div class="dropdown">
                <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </a>

                <ul class="dropdown-menu">
                    <a href="{{ route('techinical.edit', $tecnico->id) }}" class="dropdown-item">Editar</a>
                    <a href="" class="dropdown-item">Detalhes</a>
                </ul>
            </div>


        </td>
    </tr>
@endforeach
