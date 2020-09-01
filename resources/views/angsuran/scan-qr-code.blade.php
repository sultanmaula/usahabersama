<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
@extends('layouts._layout')
@section('content')
<div style="margin: 0 auto;" id="reader"></div>
<!-- <div class="container-fluid">
	<div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">QR Code Scanner</h4>
        </div>
    </div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
	            	<video style="width: 100%;" id="preview"></video>
				</div>
			</div>
		</div>
	</div>
</div> -->
@endsection
@section('script')
<script src="/js/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{URL::asset('assets')}}/js/adapter.min.js"></script>
<script type="text/javascript" src="{{URL::asset('assets')}}/js/instascan.js"></script>
<script type="text/javascript" src="{{URL::asset('assets')}}/js/QrCodeScanner.js"></script>

<script>
	function onScanSuccess(qrCodeMessage) {
		html5QrcodeScanner.clear();
		window.location.replace('/angsuran/mobile-view/'+qrCodeMessage);
	}

	var html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", { fps: 10, qrbox: 250 });
	html5QrcodeScanner.render(onScanSuccess);

</script>
<!-- 
<script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        // alert(content);
        window.location.replace('/angsuran/mobile-view/'+content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[1]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
</script> -->
@endsection