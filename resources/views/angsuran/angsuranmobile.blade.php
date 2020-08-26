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
			@if (isset($transaksi[0]->nama_produk) && isset($sisa_pinjaman_total) )
			<h4 class="mt-3">Detail Angsuran</h4>
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
	        <div><h4 style="color: red;">Data angsuran tidak ditemukan</h4></div>
			@endif
		</div>
	</div>
    @if ($id_transaksi !== 0)
		<div class="row mt-5">
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
	                	<h4>Form Pengisian Angsuran</h4>
	                    <form action="{{route('store-mobile-view')}}" method="post">@csrf
	                        <div class="form-body">
	                            <div class="row p-t-20">
				                    <div class="col-md-6">
	                                    <div class="form-group">
	                                        <label class="control-label">Jumlah Angsuran</label>
	                                         <input type="hidden" id="id_transaksi" name="id_transaksi" value="{{ $id_transaksi }}">
	                                         <input type="hidden" id="id_nasabah" name="id_nasabah" value="{{ $nasabah[0]->id_nasabah }}">
	                                        <input type="text" id="jumlah_cicilan" name="jml_angsuran" value="{{ old('jml_angsuran') }}" class="form-control nominal" spellcheck="false" required>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-actions m-t-20">
	                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Angsur</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
		</div>
    @endif
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