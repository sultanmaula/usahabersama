@extends('layouts._layout')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Nasabah</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/nasabah') }}">List Nasabah</a></li>
                    <li class="breadcrumb-item active">Tambah Nasabah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{route('store-nasabah')}}" method="post" enctype="multipart/form-data">@csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Nama Nasabah</label>
                                        <input type="text" name="nama_nasabah" id="nama_nasabah" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Alamat</label>
                                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="form-group" id="kelopok">
                                        <label class="control-label">Kelompok</label>
                                    <select class="form-control select2 col-sm-12 col-md-7" name="kelompok" style="width:100%;" id="select_kelompok" required>
                                            <option value=''>Pilih Kelompok</option>
                                            @foreach ($groups as $group)
                                            <option value="{{$group->id}}">{{$group->nama_kelompok}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">No HP</label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">NIK</label>
                                        <input type="text" name="nik" id="nik" class="form-control" placeholder="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Foto</label>
                                        <input type="file" name="foto" id="foto" class="form-control" placeholder="" required>
                                        <p style="color:red; float:right; font-size:10px;">Max Upload 2Mb</p>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                        <a href="{{ url('/nasabah') }}"><button type="button" class="btn btn-inverse">Cancel</button></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{URL::asset('assets')}}/node_modules/select2/dist/js/select2.js"></script>
    <script>
           $(document).ready(function(){
            $('.select2').select2();
            })
    </script>
    
@endsection