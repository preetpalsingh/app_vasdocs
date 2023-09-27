<table class="table search-table align-middle text-nowrap">
    <thead class="header-item">
        <th>Date</th>
        <th>Title</th>
        <th>Status</th>
        <th>Action</th>
    </thead>
    <tbody>

    @php

    $i = ($data->perPage() * ($data->currentPage() - 1)) + 1;

    @endphp

    @if( count( $data ) > 0 )

    @foreach ($data as $row)

        @php

            $desc = $row->description;

            $desc = str_replace("'", '', $desc);

            unset($row->description);

        @endphp

        <tr class="search-items">
            <td>{{ \Carbon\Carbon::parse($row->date)->format('M j, Y') }}</td>
            <td><a data-desc='{!! $desc !!}' class="sp_ajax_tooltip sp_view_detail"  data-bs-toggle="tooltip" title="View Detail" style="cursor: pointer;">{{ $row->title }}</a></td>
            <td>
                @if ($row->status == 'Pending')
                    <span class="mb-1 badge bg-warning">Pending</span>
                @elseif ($row->status == 'Complete')
                    <span class="mb-1 badge bg-success">Complete</span>
                @else
                    <span class="mb-1 badge bg-info">Under Review</span>
                @endif
            </td>

            

            <td style="display: flex">
                
                <div class="action-btn">

                    <a href="javascript:void(0)" class="btn mb-1 btn-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center  edit-user sp_ajax_tooltip" data-user='@php echo json_encode($row);@endphp' data-desc='{!! $desc !!}' style="margin-right: 5px;" data-bs-toggle="tooltip" title="Edit Detail">
                        <i class="ti ti-pencil fs-5"></i>
                    </a>

                @if( Auth::user()->role_id == 5 )

                    <a  class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center modal-delete-trigger sp_ajax_tooltip" data-bs-toggle="tooltip" title="Delete Task" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>

                @endif

                </div>
            </td>

           

        </tr>

            @php

            $i = $i + 1;

            @endphp

        @endforeach


        @else

        <tr class="search-items"><td colspan="7" class="text-center text-danger"> No record Found  </td></tr>

        @endif

    </tbody>
</table>

<div id="pagination">{{ $data->links() }}</div>