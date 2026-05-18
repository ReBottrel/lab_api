@php
    $statusOptions = $statusOptions ?? \App\Http\Controllers\Admin\AnimaisController::animalStatusOptions();
@endphp

@if ($animais->isEmpty())
    <tr>
        <td colspan="7" class="text-center py-5 text-muted">
            <i class="fa-solid fa-horse fa-2x mb-3 d-block opacity-50"></i>
            Nenhum animal encontrado com os filtros aplicados.
        </td>
    </tr>
@else
    @foreach ($animais as $animal)
        @php
            $statusMeta = $statusOptions[$animal->status] ?? ['label' => 'Sem status', 'class' => 'light'];
        @endphp
        <tr>
            <td>
                <div class="animal-name-cell">
                    <strong>{{ $animal->animal_name }}</strong>
                    @if ($animal->identificador)
                        <small class="d-block text-muted">{{ $animal->identificador }}</small>
                    @endif
                </div>
            </td>
            <td>{{ $animal->breed ?? '—' }}</td>
            <td>{{ $animal->especies ?? '—' }}</td>
            <td>
                @if ($animal->codlab)
                    <span class="codlab-badge">{{ $animal->codlab }}</span>
                @else
                    <span class="badge bg-light text-muted border">Sem codlab</span>
                @endif
            </td>
            <td>
                @if ($animal->number_definitive || $animal->register_number_brand)
                    <small>{{ $animal->number_definitive ?? $animal->register_number_brand }}</small>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $statusMeta['class'] }} status-badge">{{ $statusMeta['label'] }}</span>
            </td>
            <td class="text-end">
                <div class="dropdown d-inline-block">
                    <a class="btn btn-sm btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Ações
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('animais.show', $animal->id) }}" class="dropdown-item">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Editar
                            </a>
                        </li>
                        <li>
                            <a data-id="{{ $animal->id }}" class="dropdown-item transferir-animal" role="button">
                                <i class="fa-solid fa-exchange-alt me-2 text-primary"></i> Transferir
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a data-id="{{ $animal->id }}" class="dropdown-item excluir-animal text-danger" role="button">
                                <i class="fa-solid fa-trash me-2"></i> Excluir
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
@endif
