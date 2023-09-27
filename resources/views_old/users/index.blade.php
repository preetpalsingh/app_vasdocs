@extends('layouts.app')

@section('title', 'Users List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users Management <br> ระบบจัดการผู้ใช้งาน</h1>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                </div>
                <!--div class="col-md-6">
                    <a href="{{ route('users.export') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Export To Excel
                    </a>
                </div-->
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List All Users <br> ผู้ใช้ในระบบทั้งหมด</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="10%">บริษัท<br>องค์กร</th>
								<th width="10%">ชื่อ<br>Name</th>
                                <th width="25%">Email</th>
                                <th width="15%">เบอร์โทร <br> Mobile</th>
                                <th width="15%">สิทธิ์ <br>Role</th>
                                <th width="15%">สถานะ <br> Status</th>
                                <th width="10%">ปุ่มคำสั่ง<br>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->first_name }}</td>
									<td>{{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_number }}</td>
                                    <td>{{ $user->roles ? $user->roles->pluck('name')->first() : 'N/A' }}</td>
                                    <td>
                                        @if ($user->status == 0)
                                            <span class="badge badge-danger">Inactive</span>
                                        @elseif ($user->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td style="display: flex">
                                        @if ($user->status == 0)
                                            <a href="{{ route('users.status', ['user_id' => $user->id, 'status' => 1]) }}"
                                                class="btn btn-success m-2">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @elseif ($user->status == 1)
                                            <a href="{{ route('users.status', ['user_id' => $user->id, 'status' => 0]) }}"
                                                class="btn btn-danger m-2">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('users.edit', ['user' => $user->id]) }}"
                                            class="btn btn-primary m-2">
                                            <i class="fa fa-pen"></i>
                                        </a>

                                        <!--a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </a-->
                                        
                                        <a class="btn btn-danger m-2 modal-delete-trigger"  data-id="{{ $user->id }}" id="{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>

    </div>

    

    @include('users.delete-modal')

    @push('page_scripts')
   
    <!-- Custom JS -->

    <script>

        var base_url = "{{ Config::get('app.url') }}";

        $(document).on("click",".modal-delete-trigger",function(event) {	

            event.stopPropagation();
            delete_id = $(this).attr('id');

            images = $(this).data("images");

            swal({
                title: "Delete Permanently",
                text: "Are you Sure You wanted to Delete?",
                icon: "warning",
                buttons: {
                    cancel : 'No, cancel please!',
                    confirm : {text:'Yes, delete it!',className:'sweet-warning',
                closeModal: false,}
                },
                dangerMode: true,
            })
            .then((willDelete) => {

                if (willDelete) {

                    $(".sa-confirm-button-container .confirm").prop('disabled',true);

                    var url = base_url+'/admin/users/delete';

                    var FormData = {
                        delete_id: delete_id
                        };


                    datastring = 'delete_id='+delete_id;
                    $.ajax({
                        url:url,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        data:datastring,
                        
                        beforeSend: function()
                        {
                            $(".sa-confirm-button-container .confirm").prop('disabled',true);
                            
                            $('.sa-confirm-button-container .confirm').html('<center><i class="fa fa-refresh fa-spin  fa-fw"></i> Wait...</center>');
                            
                        },
                        
                        success: function(data)   
                        {  
                    
                            //var obs = $.parseJSON(data);
                            var obs = data;
                        
                            if(obs['status']== true)
                            {  
                                swal("Deleted!", 'Record Delete Successfully !', "success");
                                setTimeout(function(){location.reload();},3000);
                                    
                            }
                            else{
                                
                                swal("Cancelled", 'Technical Error !', "error");
                            }
                            
                        },

                        error: function(data, textStatus, errorThrown)
                        {
                            if(data.status == '403'){

                                swal("Cancelled", 'You does not have the right permissions.', "error");
                            }
                        }
                    });
                    
                    return false;
                    
                } else {

                    swal("Cancelled", "This process has been cancelled :)", "error");

                }
            });
            });

    </script>

    @endpush


@endsection

@section('scripts')
    
@endsection
