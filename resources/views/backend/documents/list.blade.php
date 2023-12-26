@php
    $url_doc_status = request()->query('doc_status'); // "all"
    $url_doc_user_id = request()->query('doc_user_id'); // "12"
@endphp
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
        </th>

        <th class="sortable" data-val="id" data-direction="@php if( $sp_sort_filed_name == 'id') { echo $sp_sort_filed_direction; } else { echo 'DESC'; } @endphp" >ID</th>
        <th class="sortable" data-val="supplier" data-direction="@php if( $sp_sort_filed_name == 'supplier') { echo $sp_sort_filed_direction; } else { echo 'DESC'; } @endphp" >Supplier</th>
        <th >Invoice No.</th>
        <th class="sortable" data-val="total_amount" data-direction="@php if( $sp_sort_filed_name == 'total_amount') { echo $sp_sort_filed_direction; } else { echo 'DESC'; } @endphp" >Gross Amount</th>
        <th>Tax</th>
        <th class="sortable" data-val="net_amount" data-direction="@php if( $sp_sort_filed_name == 'net_amount') { echo $sp_sort_filed_direction; } else { echo 'DESC'; } @endphp" >Net Amount</th>
        <th class="sortable" data-val="invoice_date" data-direction="@php if( $sp_sort_filed_name == 'invoice_date') { echo $sp_sort_filed_direction; } else { echo 'DESC'; } @endphp" >Invoice Date</th>
        <th >A/c Code</th>
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

        @php

             /*$gross_amount = $row->net_amount + $row->tax_amount + $row->standard_vat; */

        @endphp
        <tr class="search-items">


            <td>
                <div class="n-chk align-self-center text-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox sp_chkbox primary" id="{{ $row->id }}" />
                        <label class="form-check-label" for="checkbox8"></label>
                    </div>
                </div>
            </td>


            <!--td >{{ $i }}</td-->
            <td><a href="{{ route('admin.documentsView', ['invoice_id' => $row->id]) }}"  data-bs-toggle="tooltip" title="View Invoice" data-trigger="hover"> @php echo 'CH-' . str_pad($row->id, 6, '0', STR_PAD_LEFT) @endphp </a> </td>
            <td>{{ $row->supplier }}</td>
            <td>{{ $row->invoice_number }}</td>
            <td>{{ number_format($row->total_amount, 2) }}</td>
            <td>{{ number_format($row->tax_amount, 2) }} <small>({{ $row->tax_percent }}%)<small></td>
            <td>{{ number_format($row->net_amount, 2) }}</td>
            <td>{{ $row->invoice_date }}</td>
            <td>@if( !empty( $row->account_code ) ) {{ $row->code }}-{{ $row->report_code }}-{{ $row->account_name }} @endif</td>
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

                    <!--a href="{{ route('admin.documentsView', ['invoice_id' => $row->id]) }}" class="btn mb-1 btn-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center sp_ajax_tooltip" data-bs-toggle="tooltip" title="View Invoice" data-trigger="hover">
                        <i class="ti ti-eye fs-5"></i>
                    </a-->

                    <!--a  class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center modal-delete-trigger sp_ajax_tooltip" data-bs-toggle="tooltip" title="Delete Invoice" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a-->

                    @if ($row->status != 'Archive')

                    <a  class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center sp_put_document_to_archive sp_ajax_tooltip" data-bs-toggle="tooltip" title="Archive Invoice" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-undo"></i></a>

                    @endif

                    <a class="btn mb-1 btn-warning btn-circle btn-sm d-inline-flex align-items-center justify-content-center " href="javascript:void(0)" id="t2" data-bs-toggle="dropdown" aria-expanded="false"  >
                            <i class="ti ti-info-small fs-8"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="t2" style="">
                          <li>
                            <a class="dropdown-item" href="#">
                              Uploaded BY : {{ $row->session_first_name }} </a>
                          </li>
                          <li>
                            <a class="dropdown-item" href="#">
                              Platform : {{ $row->platform }} </a>
                          </li>
                          <li>
                            <a class="dropdown-item" href="#">
                              Date : @php echo date("d/m/Y", strtotime( $row->created_at )); @endphp </a>
                          </li>
                        </ul>

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