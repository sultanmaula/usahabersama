@extends('layouts._layout')
@section('asset')
<style>
.noborder td {
    border: none;
}

</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Detail Angsuran</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <table class="table table-sm col-md-6 noborder" style="border-style: none;">
                    <tr>
                        <td><b>Nama</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->nama }}</td>
                    </tr>
                    <tr>
                        <td><b>Kelompok</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->nama_kelompok }}</td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Realisasi</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->tanggal }}</td>
                    </tr>                    
                    <tr>
                        <td><b>Harga Produk</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->harga_produk }}</td>
                    </tr>
                    <tr>
                        <td><b>Pokok Pinjaman</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->jumlah_pinjaman_pokok }}</td>
                    </tr>
                    <tr>
                        <td><b>Laba 30%</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->jumlah_pinjaman_laba }}</td>
                    </tr>
                    <tr>
                        <td><b>Jumlah</b></td>
                        <td>:</td>
                        <td><b>{{ $angsuran->transaksi->hargaPlusLaba }}</b></td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <table class="table table-sm col-md-6 noborder">
                    <tr>
                        <td><b>Progress Angsuran</b></td>
                        <td>:</td>
                        <td>Ke- {{$angsuran->cicilan_ke}}</td>
                    </tr>
                    <tr>
                        <td>
                            <h4>STATUS</h4>
                        </td>
                        <td>:</td>
                        <td><b>
                                <h4 class=<?php if ( $angsuran->transaksi->status == 'LUNAS' ) { echo "text-success";} else { echo "text-danger"; } ?>>{{$angsuran->transaksi->status}}</h4>
                            </b></td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Diangsur (bulan)</b></td>
                        <td>:</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td><b>Jatuh Tempo</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->jatuh_tempo }}</td>
                    </tr>
                    <tr>
                        <td><b>Angsuran Pokok</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->angsuran_pokok }}</td>
                    </tr>
                    <tr>
                        <td><b>Angsuran Bagi Hasil</b></td>
                        <td>:</td>
                        <td>{{ $angsuran->transaksi->angsuran_bagihasil }}</td>
                    </tr>
                    <tr>
                        <td><b>Jumlah Angsuran</b></td>
                        <td>:</td>
                        <td><b>{{ $angsuran->transaksi->jumlah_cicilan }}</b></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="table">
                    <tr>
                        <th>Deskripsi</th>
                        <th>Pokok</th>
                        <th>Laba</th>
                        <th>Jumlah</th>
                    </tr>
                    <tr>
                        <th>Sisa</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Total Angsuran</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="table">
                    <tr>
                        <th>Angsuran Ke</th>
                        <th>Tanggal</th>
                        <th>Angsuran Pokok</th>
                        <th>Sisa Pokok</th>
                        <th>Angsuran Laba</th>
                        <th>Sisa Laba</th>
                        <th>Total Angsuran</th>
                    </tr>
                    @foreach($list_angsuran as $angsuran)
                    <tr>
                        <td>{{ $angsuran->cicilan_ke }}</td>
                        <td>{{ $angsuran->tanggal }}</td>
                        <td>Rp. {{ number_format($angsuran->angsuran_pokok, 0, ",", ".") }}</td>
                        <td>Rp. {{ number_format($angsuran->sisa_pinjaman, 0, ",", ".") }}</td>
                        <td>Rp. {{ number_format($angsuran->angsuran_laba, 0, ",", ".") }}</td>
                        <td>Rp. {{ number_format($angsuran->sisa_laba, 0, ",", ".") }}</td>
                        <td>Rp. {{ number_format($angsuran->jml_angsuran, 0, ",", ".") }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
