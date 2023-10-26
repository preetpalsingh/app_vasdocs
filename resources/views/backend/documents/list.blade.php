<table class="table search-table align-middle text-nowrap">
    <thead class="header-item">
        <th>
            <div class="n-chk align-self-center text-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input primary" id="contact-check-all">
                    <label class="form-check-label" for="contact-check-all"></label>
                    <span class="new-control-indicator"></span>
                </div>
            </div>
        <th>ID</th>
        <th>Company</th>
        <th>Supplier</th>
        <th>Net Amount</th>
        <th>Tax</th>
        <th>Total Amount</th>
        <th>Status</th>

        {{-- @hasrole('Admin') --}}
        <th>Action</th>
        {{-- @endhasrole --}}

    </thead>
    <tbody>

    @php

    $i = ($data->perPage() * ($data->currentPage() - 1)) + 1;

    @endphp

    @if( count( $data ) > 0 )

    @foreach ($data as $row)
        <tr class="search-items">
            <td>
                <div class="n-chk align-self-center text-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8" />
                        <label class="form-check-label" for="checkbox8"></label>
                    </div>
                </div>
            </td>
            <!--td >{{ $i }}</td-->
            <td><a href="{{ route('admin.documentsView', ['invoice_id' => $row->id]) }}"  data-bs-toggle="tooltip" title="View Invoice" data-trigger="hover"> @php echo 'CH-' . str_pad($row->id, 6, '0', STR_PAD_LEFT) @endphp </a> </td>
            <td>{{ $row->company_name }}</td>
            <td>{{ $row->supplier }}</td>
            <td>{{ $row->net_amount }}</td>
            <td>{{ $row->tax_amount }} <small>({{ $row->tax_percent }}%)<small></td>
            <td>{{ $row->total_amount }}</td>
            <td>
                @if ($row->status == 'Processing')
                    <span class="mb-1 badge bg-warning">Processing</span>
                @elseif ($row->status == 'Review')
                    <span class="mb-1 badge bg-secondary">Reviewed</span>
                @elseif ($row->status == 'Ready')
                    <span class="mb-1 badge bg-success">Ready</span>
                @elseif ($row->status == 'Archive')
                    <span class="mb-1 badge bg-primary">Archive</span>
                @endif
            </td>

            {{-- @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 4 ) --}}

            <td style="display: flex">
                
                <div class="action-btn">

                    <a href="{{ route('admin.documentsView', ['invoice_id' => $row->id]) }}" class="btn mb-1 btn-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center sp_ajax_tooltip" data-bs-toggle="tooltip" title="View Invoice" data-trigger="hover">
                        <!-- <i class="ti ti-trash fs-5"></i> -->
                        <i class="ti ti-eye fs-5"></i>
                    </a>

                    <a  class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center modal-delete-trigger sp_ajax_tooltip" data-bs-toggle="tooltip" title="Delete Invoice" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>

                    <!--a alt="alert" class="text-danger  ms-2 img-fluid model_img" id="sa-confirm">
                        <i class="ti ti-trash fs-5"></i>
                    </a-->
                </div>
            </td>

            {{-- 
                @endif --}}

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