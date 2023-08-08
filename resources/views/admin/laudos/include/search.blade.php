@foreach ($laudos as $item)
    <tr>
        <th scope="row">{{ $item->id }} / {{ $item->order_id }}</th>

        <td>{{ $item->animal->animal_name }}</td>
        <th>{{ date('d/m/Y', strtotime($item->created_at)) }}</th>
        <td>
            <div class="row">
                <div class="col-4"><a href="{{ route('laudo.download', $item->id) }}"><button class="btn btn-primary"><i
                                class="fa-solid fa-download"></i>
                        </button></a>
                </div>

            </div>
        </td>
    </tr>
@endforeach
