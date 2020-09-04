<meta charset="utf-8" />
@extends('layouts._layout')
@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div id="camera" class="row">
				<div style="margin: 0 auto;" id="reader"></div>
			</div>
			<div class="row">
				<div id="tombol" style="margin: 0 auto;">
					<button id="mulaiscan" class="btn-xs btn-info mt-2" type="button" onclick="listcamera()">Mulai Scan</button>
					<button id="maticamera" class="btn-xs btn-danger mt-2" type="button" style="display: none;" onclick="maticamera('reader')">Matikan Scan</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script src="/js/html5-qrcode.min.js"></script>

<script type="text/javascript">
	function listcamera() {
		Html5Qrcode.getCameras().then(cameras => {
			if (cameras[1] == null) {
				cameraId = cameras[0].id;
				scan(cameraId);
			} else {
				cameraId = cameras[1].id;
				scan(cameraId);
			}
			
		}).catch(err => {
				// handle err 
					alert(err);
		});
		
	}

	function maticamera(elementID) {
		document.getElementById(elementID).innerHTML = ''
		$("#maticamera").css('display', 'none');
		$("#mulaiscan").css('display', 'block');
	}

	function scan(cameraId) {

		$("#maticamera").css('display', 'block');
		$("#mulaiscan").css('display', 'none');
		const html5Qr = new Html5Qrcode("reader");

		html5Qr.start(
		cameraId, // retreived in the previous step.
		   {
		      fps: 10,    // sets the framerate to 10 frame per second 
		      qrbox: 250  // sets only 250 X 250 region of viewfinder to
		                  // scannable, rest shaded.
		 },
		 qrCodeMessage => {     // do something when code is read. For example:
		    window.location.replace('/angsuran/mobile-view/'+qrCodeMessage);

		    html5Qr.stop().then(ignore => {
			  // QR Code scanning is stopped.
			}).catch(err => {
			  // Stop failed, handle it.
			});
		 },
		 errorMessage => {     // parse error, ideally ignore it. For example:
		     console.log(`QR Code no longer in front of camera.`);
		 })
		.catch(err => {     // Start failed, handle it. For example, 
		     console.log(`Unable to start scanning, error: ${err}`);
		});
	}
</script>

@endsection