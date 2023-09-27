@extends('layouts.app')

@section('title',  $title . ' List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
            

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All {{$title}}</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Record Id</th>
                            <th class="text-center">Menu</th>
                            <th class="text-center">IP</th>
                            <th class="text-center">Action</th>                      
                            <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                @foreach ($logs as $key => $row)
                @php

                    $name = '';
                    $record_id = '';
                    $menu = '';
                    $action = '';

                    $sp_params = json_decode($row->context , true);

                    extract($sp_params);

                    //echo "<pre>";print_r($sp_params);die(); 
                
                @endphp

                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $row->record_datetime }}</td> 
                        <td class="text-center">@php echo ucwords($name); @endphp</td>
                        <td class="text-center">{{ $record_id }}</td>  
                        <td class="text-center">{{ $menu }}</td> 
                        <td class="text-center">{{ $row->remote_addr }}</td>  
                        <td class="text-center">{{ $action }}</td>   
                        <td class="text-center text-red h4">

                            <a  class="btn btn-danger m-2   modal-delete-trigger" data-id="{{ $row->id }}" id="{{ $row->id }}"><i aria-hidden="true" class="fas fa-trash"></i></a>
                            
                        </td>
                    </tr>
                @endforeach
                </tbody>
                    </table>

                    {{ $logs->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="delete_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalExample">Delete Permanently</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"><div class="msg_status" ></div>Are you Sure You wanted to Delete?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="btn_delete_feed" class="btn btn-danger" >
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
   
    <!-- Custom JS -->

    <script>

        var base_url = "{{ Config::get('app.url') }}";

    </script>

    <script src="{{ asset('js/log.js') }}"></script>

    @endpush

@endsection

@section('scripts')
    
@endsection

