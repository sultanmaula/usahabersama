<div class="row">
	@foreach($nasabah as $n)
    <div class="form-group col-md-12 inline">
	    <div>{!! QrCode::size('200')->generate($n->id); !!}</div>
		<h4>{{ $n->nama }}</h4>
    </div>
	@endforeach
	<a href="/nasabah" type="button" class="btn btn-danger">Kembali</a>
</div>
<script type="text/javascript">
	window.print();
</script>