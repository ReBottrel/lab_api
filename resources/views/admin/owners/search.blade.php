@forelse ($owners as $owner)
    @php
        $nome = trim((string) $owner->owner_name);
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
            <div class="owner-name">
                <span class="owner-avatar">{{ $iniciais }}</span>
                <span class="owner-name-text">{{ $owner->owner_name }}</span>
            </div>
        </td>
        <td class="owner-meta">{{ $owner->email ? strtolower($owner->email) : '—' }}</td>
        <td>
            @if ($owner->user_id)
                <span class="badge-access yes">
                    <i class="fas fa-check-circle"></i> Possui acesso
                </span>
            @else
                <span class="badge-access no">
                    <i class="fas fa-times-circle"></i> Sem acesso
                </span>
            @endif
        </td>
        <td>
            <div class="dropdown">
                <a class="btn btn-acoes dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Ações
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('owner.edit', $owner->id) }}" class="dropdown-item">
                        <i class="fas fa-pen fa-fw mr-1"></i> Editar
                    </a>
                    <a href="{{ route('get.owners.details', $owner->id) }}" class="dropdown-item">
                        <i class="fas fa-eye fa-fw mr-1"></i> Detalhes
                    </a>
                    @if ($owner->user_id)
                        <a href="{{ route('owner.user', $owner->user_id) }}" class="dropdown-item">
                            <i class="fas fa-user fa-fw mr-1"></i> Ver usuário
                        </a>
                    @else
                        <button type="button" data-id="{{ $owner->id }}" class="dropdown-item create-access">
                            <i class="fas fa-user-plus fa-fw mr-1"></i> Criar acesso
                        </button>
                    @endif
                    <a class="dropdown-item"
                        href="{{ route('get.animals', $owner->old_id ?: $owner->id) }}">
                        <i class="fas fa-paw fa-fw mr-1"></i> Ver animais
                    </a>
                    <a data-id="{{ $owner->id }}" class="dropdown-item delete">
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
                <div><i class="fas fa-users"></i></div>
                <div>Nenhum proprietário encontrado.</div>
            </div>
        </td>
    </tr>
@endforelse
