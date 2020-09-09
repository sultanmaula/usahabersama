<div class="row">
	@foreach($nasabah as $n)
    <div style="float: left; margin-right: 30px;">
	    <div>{!! QrCode::size('120')->generate($n->id); !!}</div>
		<h4>{{ $n->nama }}</h4>
    </div>
	@endforeach
</div>
<script type="text/javascript">
	window.print();
</script>