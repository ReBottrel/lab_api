@foreach ($ordemServicos as $item)
    <tr>
        <th scope="row">{{ $item->order_id }}</th>

        <td>{{ $item->owner }}</td>
        <th>{{ date('d/m/Y', strtotime($item->created_at)) }}</th>
        <td>
            <div class="row">
                <div class="col-4"><a href="{{ route('ordem.servico.show', $item->id) }}"><button
                            class="btn btn-primary"><i class="fa-solid fa-eye"></i>
                        </button></a>
                </div>
                <div class="col-4"><button type="button" class="btn btn-danger delete" data-id="{{ $item->id }}"><i
                            class="fa-solid fa-trash"></i>
                    </button>
                </div>

            </div>
        </td>
    </tr>
@endforeach
