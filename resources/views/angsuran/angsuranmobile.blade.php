@extends('layouts._layout')
@section('content')
<div class="container-fluid">
	<div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Data Nasabah</h4>
        </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="ml-auto mr-auto">
		            <div class="card hovercard">
		                <div class="avatar" style="text-align:center;">
		                    <img src="/nasabah_image/{{ $nasabah[0]->foto }}" width="150" height="150" class="rounded-circle">
		                </div>
		                <div class="info">
		                    <h4 class="mt-3" style="text-align:center; font-weight: 500;">{{ $nasabah[0]->nama }}</h4>
		                    <div style="text-align:center;">{{ $nasabah[0]->nama_kelompok }}</div>
		                </div>
		            </div>
		        </div>
			</div>
			@if (isset($transaksi[0]->nama_produk))
				<div class="row">
	                <table class="table table-md col-md-12 noborder" style="border-style: none;">
	                    <tr>
	                        <td><b>Nama Produk</b></td>
	                        <td>:</td>
	                        <td>{{ $transaksi[0]->nama_produk }}</td>
	                    </tr>
	                    <tr>
	                        <td><b>Total Pinjaman</b></td>
	                        <td>:</td>
	                        <td>Rp. {{ number_format($transaksi[0]->jumlah_pinjaman_pokok + $transaksi[0]->jumlah_pinjaman_laba, 0, ",", ".") }}</td>
	                    </tr>
	                    <tr>
	                        <td><b>Angsuran Perbulan (24 bulan)</b></td>
	                        <td>:</td>
	                        <td>Rp. {{ number_format($transaksi[0]->jumlah_cicilan, 0, ",", ".") }}</td>
	                    </tr>                    
	                    <tr>
	                        <td><b>Total Sisa</b></td>
	                        <td>:</td>
	                        <td>Rp. {{ number_format($sisa_pinjaman_total, 0, ",", ".") }}</td>
	                    </tr>
	                </table>
	            </div>
	        @else
	        	<div><h4 style="color: red;">Data tidak ditemukan</h4></div>
			@endif
		</div>
	</div>
</div>
@endsection
@section('script')
@endsection