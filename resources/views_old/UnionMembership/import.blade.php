@extends('layouts.app')

@section('title', 'Import  ' . $title )

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Import ข้อมูลสมาชิกสหกรณ์</h1>
        <a href="{{route('users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Import ข้อมูลสมาชิกสหกรณ์</h6>
        </div>
        <form method="POST" action="{{route('admin.umupload')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    
                    <div class="col-md-12 mb-3 mt-3">
                        <p>กรุณา Upload file ข้อมูลสมาชิกสหกรณ์ในรูปแบบ CSV ตามตัวอย่างที่ให้ไว้ <br><a href="{{ asset('files/sample-data-sheet-union-membership.csv') }}" target="_blank">ตัวอย่าง File ชนิดCSV | Sample CSV Format</a></p>
                    </div>
                    {{-- File Input --}}
                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Input File (Datasheet)</label>
                        <input 
                            type="file" 
                            class="form-control form-control-user @error('file') is-invalid @enderror" 
                            id="exampleFile"
                            name="file" 
                            value="{{ old('file') }}">

                        @error('file')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Upload ข้อมูลสมาชิกสหกรณ์</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('admin.umlist') }}">Cancel</a>
            </div>
        </form>
    </div>

</div>


@endsection