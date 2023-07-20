<thead>
    <tr>
        <th scope="col">Numero/Pedido</th>
        <th scope="col">Animal</th>
        <th>Codlab</th>
        <th>Ação</th>
    </tr>
</thead>
<tbody class="table-busca">
    <tr>
        <th scope="row">{{ $item->order }}</th>

        <td>{{ $item->animal }}</td>
        <th>{{ $item->codlab }}</th>
        <td>
            <div class="row">
                <div class="col-4"><a href="{{ route('result.by.codlab', $item->id) }}"><button
                            class="btn btn-primary"><i class="fa-solid fa-eye"></i>
                        </button></a>
                </div>
                {{-- <div class="col-4"><button type="button" class="btn btn-danger delete" data-id="{{ $item->id }}"><i
                            class="fa-solid fa-trash"></i>
                    </button>
                </div> --}}

            </div>
        </td>
    </tr>
</tbody>
