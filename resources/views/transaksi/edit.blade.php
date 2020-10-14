@extends('layouts._layout')
@section('asset')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Tambah Transaksi</h4>
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
                    <form action="{{route('update-transaksi', $transaksi['id'])}}" method="post">@csrf
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Nama Produk</label>
                                        <input type="text" name="nama_produk" class="form-control" value="{{ $transaksi['nama_produk'] }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Harga Produk</label>
                                        <input type="text" name="harga_produk" class="form-control nominal" spellcheck="false" value="{{ $transaksi['harga_produk'] }}" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Total Pinjaman</label>
                                        <input type="text" name="total_pinjaman" class="form-control nominal" spellcheck="false" value="{{ $transaksi['total_pinjaman'] }}" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Nasabah</label>
                                        <select class="select2 form-control" name="id_nasabah" required>
                                            <option value="{{$transaksi->nasabah->id}}">{{$transaksi->nasabah->nama}}</option>
                                            @foreach ($nasabah as $val)
                                            <option value="{{$val->id}}">{{$val->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tanggal</label>
                                        <input autocomplete="off" type="text" name="tanggal" class="form-control date" value="{{ $transaksi['tanggal'] }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Cicilan</label>
                                        <input type="text" id="jumlah_cicilan" name="jumlah_cicilan" value="{{ $transaksi['jumlah_cicilan'] }}" class="form-control nominal" spellcheck="false" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Sisa Pinjaman</label>
                                        <input type="text" name="sisa_pinjaman" value="{{ $transaksi['sisa_pinjaman'] }}" class="form-control nominal" spellcheck="false" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label><br>
                                        <div class="form-check form-check-inline mt-2">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="1" <?php if ($transaksi['status']==1) { echo "checked" ;} ?>>
                                            <label class="form-check-label" for="inlineRadio1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="0" <?php if ($transaksi['status']==0) { echo "checked" ;} ?>>
                                            <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions m-t-20">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            <a href="{{route('list-transaksi')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/select2/dist/js/select2.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap"
    });
    $('.date').datepicker({
        autoclose: true,
        language: 'id',
        locale: 'id',
        format: 'dd-mm-yyyy',
        orientation: 'bottom auto'
    });
});

$('.nominal').on('keyup', function(e) {
    this.value = formatRupiah(this.value, '');
});
/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

</script>
@endsection
