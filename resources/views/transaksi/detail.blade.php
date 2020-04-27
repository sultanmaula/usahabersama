@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Detail Transaksi</h4>
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
                    <label class="control-label" style="font-weight: bold;">Nama Produk</label>
                    <div>{{$transaksi->nama_produk}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Harga Produk</label>
                    <div>{{$transaksi->harga_produk}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Total Pinjaman</label>
                    <div>{{$transaksi->total_pinjaman}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Nama Nasabah</label>
                    <div>{{$transaksi->nasabah->nama}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Tanggal</label>
                    <div>{{$transaksi->tanggal}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Jumlah Cicilan</label>
                    <div>{{$transaksi->jumlah_cicilan}}</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Sisa Pinjaman</label>
                    <div>{{$transaksi->sisa_pinjaman}}</div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Status</label>
                    <div>{{$transaksi->status}}</div>
                </div>
            </div>
            <div class="form-group mt-5">
                <a href="{{route('list-transaksi')}}" class="btn btn-info">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
