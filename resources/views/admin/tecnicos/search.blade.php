@forelse ($tecnicos as $tecnico)
    @php
        $nome = trim((string) $tecnico->professional_name);
        $partes = preg_split('/\s+/', $nome) ?: [];
        $iniciais = '';
        if (!empty($partes[0])) {
            $iniciais .= mb_substr($partes[0], 0, 1);
        }
        if (count($partes) > 1) {
            $iniciais .= mb_substr($partes[count($partes) - 1], 0, 1);
        }
        $iniciais = mb_strtoupper($iniciais ?: '?');
    @endphp
    <tr>
        <td>
            <div class="tec-name">
                <span class="tec-avatar">{{ $iniciais }}</span>
                <span class="tec-name-text">{{ $tecnico->professional_name }}</span>
            </div>
        </td>
        <td class="tec-meta">{{ $tecnico->email ?: '—' }}</td>
        <td class="tec-meta">{{ $tecnico->cell ?: '—' }}</td>
        <td>
            <div class="dropdown">
                <a class="btn btn-acoes dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Ações
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('techinical.edit', $tecnico->id) }}" class="dropdown-item">
                        <i class="fas fa-pen fa-fw mr-1"></i> Editar
                    </a>
                    <a data-id="{{ $tecnico->id }}" class="dropdown-item delete">
                        <i class="fas fa-trash fa-fw mr-1"></i> Excluir
                    </a>
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4">
            <div class="empty-state">
                <div><i class="fas fa-user-md"></i></div>
                <div>Nenhum técnico encontrado.</div>
            </div>
        </td>
    </tr>
@endforelse
