<div class="row">
    <div class="form-group col-md-6">
        <div>{!! QrCode::size('200')->generate($nasabah[0]->id); !!}</div>
    	<h4>{{ $nasabah[0]->nama }}</h4>
    </div>
</div>
<script type="text/javascript">
	window.print();
</script>