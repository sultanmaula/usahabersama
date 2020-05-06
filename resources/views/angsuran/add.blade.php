@extends('layouts._layout')
@section('asset')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" />
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" /> -->
@endsection
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Tambah Angsuran</h4>
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
                    <form action="{{route('store-angsuran')}}" method="post">@csrf
                        <div class="form-body">
                            <div class="row  p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Transaksi</label>
                                        <select style="height: 150%" class="select2 form-control" name="id_transaksi" required>
                                            <option value="">Pilih Transaksi</option>
                                            @foreach ($transaksi as $val)
                                            <option value="{{$val->id}}">{{$val->nama}} - {{$val->nama_kelompok}} - {{$val->nama_produk}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Cicilan Ke</label>
                                        <input min="1" type="number" name="cicilan_ke" class="form-control" spellcheck="false" value="{{ old('cicilan_ke') }}" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Angsuran</label>
                                        <input type="text" id="jumlah_cicilan" name="jml_angsuran" value="{{ old('jml_angsuran') }}" class="form-control nominal" spellcheck="false" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Tanggal</label>
                                        <input autocomplete="off" type="text" name="tanggal" class="form-control date" value="{{ old('tanggal') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control editor" rows="10"> {{ old('keterangan') }} </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions m-t-20">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            <a href="{{route('list-angsuran')}}" class="btn btn-secondary">Cancel</a>
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
<script type="text/javascript" src="{{ asset('assets/ckeditornotif/build/ckeditor.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
    $('.date').datepicker({
        autoclose: true,
        language: 'id',
        locale: 'id',
        format: 'dd-mm-yyyy'
    });



    ClassicEditor
        .create(document.querySelector('.editor'), {
            minHeight: '300px',

            toolbar: {
                items: [
                    '|',
                    'bold',
                    'italic',
                    '|',
                    '|',
                    'undo',
                    'redo',
                    'fontFamily',
                    'fontSize',
                    'underline',
                    'fontColor',
                    'fontBackgroundColor'
                ]
            },
            language: 'id',
            licenseKey: '',

        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error('Oops, something gone wrong!');
            console.error('Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:');
            console.warn('Build id: 5ujerc8eyrnw-t8cwpsoypgd1');
            console.error(error);
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
