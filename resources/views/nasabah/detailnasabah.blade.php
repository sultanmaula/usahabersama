@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Detail Nasabah</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Nama Nasabah</label>
                    <div>{{$nasabah[0]->nama}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Alamat</label>
                    <div>{{$nasabah[0]->alamat}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Kelompok</label>
                    <div>{{$nasabah[0]->nama_kelompok}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">No. Handphone</label>
                    <div>{{$nasabah[0]->no_hp}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">NIK</label>
                    <div class="mb-4">
                        @if(!empty($nasabah[0]->nik))
                            {{ $nasabah[0]->nik }}
                        @else
                            -
                        @endif
                    </div>
                    <label class="control-label" style="font-weight: bold;">QR-Code</label>
                    <div class="mb-3">{!! QrCode::size('150')->generate($nasabah[0]->id_nasabah); !!}</div>
                    <a href="/nasabah/detail/printqrcode/{{$nasabah[0]->id_nasabah}}" class="btn-sm btn-info" type="button" target="_blank">Print QR-Code</a>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Foto</label>
                    <div>
                        <img src="{{$nasabah[0]->foto}}" width="200">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
