@extends('layouts._layout')
@section('content')
<div style="margin: 0 auto;" id="reader"></div>
@endsection
@section('script')
<script src="/js/html5-qrcode.min.js"></script>

<script>
	function onScanSuccess(qrCodeMessage) {
		html5QrcodeScanner.clear();
		window.location.replace('/angsuran/mobile-view/'+qrCodeMessage);
	}

	var html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", { fps: 10, qrbox: 250 });
	html5QrcodeScanner.render(onScanSuccess);

</script>
@endsection