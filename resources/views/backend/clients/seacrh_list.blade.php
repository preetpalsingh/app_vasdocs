@if( count( $data ) > 0 )

    @foreach ($data as $row)

        <li class="p-1 mb-1 bg-hover-light-black">
            <a href="{{ route('home', ['status' => 'all']) }}/{{$row->id}}">
                <span class="fs-3 text-black fw-normal d-block">{{ $row->first_name }}</span>
                <span class="fs-3 text-muted d-block">{{ $row->email }}</span>
            </a>
        </li>

    @endforeach


@else

    <li class="p-1 mb-1 bg-hover-light-black text-center text-danger"> No record Found  </li>

@endif