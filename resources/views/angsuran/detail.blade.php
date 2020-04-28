@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Detail Angsuran</h4>
        </div>
        <!-- <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('list-admin')}}">Administrator</a></li>
                    <li class="breadcrumb-item active">Show Administrator</li>
                </ol>
            </div>
        </div> -->
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Transaksi</label>
                    <div>{{$angsuran->id_transaksi}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Cicilan Ke</label>
                    <div>{{$angsuran->cicilan_ke}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Jumlah Angsuran</label>
                    <div>{{$angsuran->jml_angsuran}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Sisa Pinjaman</label>
                    <div>{{$angsuran->sisa_pinjaman}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Tanggal</label>
                    <div>{{$angsuran->tanggal}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Status</label>
                    <div>{{$angsuran->status}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label" style="font-weight: bold;">Keterangan</label>
                    <div class="text-justify text-wrap" >{!! $angsuran->keterangan !!}</div>
                </div>
                
            </div>
            <div class="form-group mt-5">
                <a href="{{route('list-angsuran')}}" class="btn btn-info">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
