@extends('layouts.app')

@section('title',  $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}} <br> ระบบข้อมูลสมาชิกสหกรณ์สำหรับตรวจสอบความเป็นสมาชิก<br>ก่อนออกใบเสนอราคานำไปสั่งซื้อกับตัวสหกรณ์ที่เป็นสมาชิกอยู่</h1>
            <div class="row" style="width: 240px;">
                <div class="col-md-6">

                {{--  @if ( $allowed_permissions['union-membership-add'] )  --}}

                    <a id="addFeed" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มรายชื่อบุคคล
                    </a>

                {{--  @endif  --}}

                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.umimport') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> นำเข้าข้อมูล
                    </a>
                </div>
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All {{$title}} <br>รายชื่อสมาชิกของสหกรณ์ทั้งหมด</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">ชื่อสหกรณ์<br>Union</th>
                                <th width="25%">รหัสสมาชิก<br>6 Digit ID</th>
                                <th width="15%">ชื่อ <br> Name</th>
                                <th width="15%">นามสกุล <br>Surname</th>
                                <th width="15%">ที่ิอยู่<br>Address</th>
                                <th width="15%">เบอร์โทร<br>Phone</th>
                                <th width="15%">Status</th>
                                <th width="10%">ปุ่มคำสั่ง<br>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->union_name }}</td>
                                    <td>{{ $row->union_member_ID }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->surname }}</td>
                                    <td>{{ $row->address }}</td>
                                    <td>{{ $row->phone_number }}</td>
                                    <td>
                                        @if ($row->status == 0)
                                            <span class="badge badge-danger">Inactive</span>
                                        @elseif ($row->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td style="display: flex">
                                        @if ($row->status == 0)
                                            <a href="{{ route('admin.umustatusupdate', ['user_id' => $row->id, 'status' => 1]) }}"
                                                class="btn btn-success m-2">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @elseif ($row->status == 1)
                                            <a href="{{ route('admin.umustatusupdate', ['user_id' => $row->id, 'status' => 0]) }}"
                                                class="btn btn-danger m-2">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                        @endif

                                        <a class="btn btn-primary m-2 edit-user" data-user='@php echo json_encode($row);@endphp'><i aria-hidden="true" class="fa fa-pen"></i></a>

                                        <a class="btn btn-danger m-2 modal-delete-trigger"  id='{{ $row->id }}'>
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $data->links() }}
                    
                </div>
            </div>
        </div>

    </div>

    <!-- Add feed Modal -->
    
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
                <form class="sp_form">

                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="deleteModalExample">Add {{$title}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <input type="hidden" value="0" id="edit_id">
                    <input type="hidden" value="0" id="previous_img_val">

                        <div class="modal-body">
                        
                        <div class="msg_status" ></div>
                    
                        <div class="form-horizontal">

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ชื่อสหกรณ์ </label>
                                    <input type="text" class="form-control" name="union_name" id="union_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">หมายเลขสมาชิกสหกรณ์</label>
                                    <input type="text" class="form-control" id="union_member_ID" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ชื่อสมาชิก </label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">นามสกุล </label>
                                    <input type="text" class="form-control" id="surname" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">ที่อยู่ </label>
                                    <input type="text" class="form-control" id="address" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="English ">เบอร์โทร</label>
                                    <input type="text" class="form-control" id="phone_number" >
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="btn_add_feed">Add</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('page_scripts')
   
   <!-- Custom JS -->

    <script>

       var base_url = "{{ Config::get('app.url') }}";

    </script>

    <script src="{{ asset('js/union-membership.js') }}"></script>

   @endpush


@endsection

@section('scripts')
    
@endsection
