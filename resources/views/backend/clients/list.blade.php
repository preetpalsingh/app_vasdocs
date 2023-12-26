<table class="table search-table align-middle text-nowrap">
    <thead class="header-item">
        <!--th>
            <div class="n-chk align-self-center text-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                    <label class="form-check-label" for="contact-check-all"></label>
                    <span class="new-control-indicator"></span>
                </div>
            </div>
        </th>
        <th>Sr No.</th-->
        <th>Company Name</th>
        <th>Name</th>
        <th>No. of Docs</th>
        <th>Email</th>
        <th>Phone</th>
        <!--th>Documents</th-->

        @if( Auth::user()->role_id == 1 )

        <th>Action</th>

        @endif

    </thead>
    <tbody>

    @php

    $i = ($data->perPage() * ($data->currentPage() - 1)) + 1;

    @endphp

    @if( count( $data ) > 0 )

    @foreach ($data as $row)
        <tr class="search-items">
            <!--td>
                <div class="n-chk align-self-center text-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox8" />
                        <label class="form-check-label" for="checkbox8"></label>
                    </div>
                </div>
</td>
            <td >{{ $i }}</td-->

            <td><a href="{{ route('admin.invoice_details', ['status' => 'Processing']) }}/{{$row->id}}">{{ $row->company_name }}</a></td>

            <td>  {{ $row->first_name }}  
               
                    @if ($row->status == 0)
                        <span class="mb-1 badge bg-danger" style="
                        font-size: 10px;
                        margin-left: 5px;
                        padding: 5px 5px 7px 5px !important;
                        ">Inactive</span>
                    @elseif ($row->status == 1)
                        <span class=" badge bg-success" style="
                        font-size: 10px;
                        margin-left: 5px;
                        padding: 5px 5px 7px 5px !important;
                        ">Active</span>
                    @endif

                    

            </td>

            <td>{{ $row->documents_count }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->mobile_number }}</td>
            <!--td>
            <a href="{{ route('admin.invoice_details', ['status' => 'all']) }}/{{$row->id}}" class="btn waves-effect waves-light btn-rounded btn-sm btn-primary">View Documents</a>
            </td-->

            @if( Auth::user()->role_id == 1 )

            <td style="display: flex">
                
                <div class="action-btn">

                @if ($row->status == 0)
                    <a href="{{ route('admin.clientStatusUpdate', ['user_id' => $row->id, 'status' => 1]) }}"
                        class="btn mb-1 btn-success btn-circle btn-sm d-inline-flex align-items-center justify-content-center" 
        data-bs-toggle="tooltip"
        title="Active" style="margin-right: 5px;">
                                <i class="fa fa-check"></i>
                            </a>
                        @elseif ($row->status == 1)
                            <a href="{{ route('admin.clientStatusUpdate', ['user_id' => $row->id, 'status' => 0]) }}"
                                class="btn mb-1 btn-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center"
        data-bs-toggle="tooltip"
        title="Deactive" style="margin-right: 5px;">
                                <i class="ti ti-ban fs-5"></i>
                            </a>
                        @endif

                    <a href="javascript:void(0)" class="btn mb-1 btn-info btn-circle btn-sm d-inline-flex align-items-center justify-content-center  edit-user" data-user='@php echo json_encode($row);@endphp' style="margin-right: 5px;" data-bs-toggle="tooltip" title="Edit Detail">
                        <i class="ti ti-pencil fs-5"></i>
                    </a>

                    <a href="javascript:void(0)" class="btn mb-1 btn-warning btn-circle btn-sm d-inline-flex align-items-center justify-content-center  sp_mail_login_detail" data-user-id='@php echo $row->id;@endphp' style="margin-right: 5px;" data-bs-toggle="tooltip" title="Send login detail to client ">
                        <i class="ti ti-mail-share fs-5"></i>
                    </a>

                    <!--a alt="alert" class="text-danger  ms-2 img-fluid model_img" id="sa-confirm">
                        <i class="ti ti-trash fs-5"></i>
                    </a-->
                </div>
            </td>

            @endif
            
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