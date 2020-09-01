<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
@extends('layouts._layout')
@section('content')
<!-- <div style="margin: 0 auto;" id="reader"></div> -->
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<video id="preview" style="width: 100%"></video>
				<div class="btn-group btn-group-toggle mt-3" data-toggle="buttons">
				  <label class="btn btn-primary active">
				    <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
				  </label>
				  <label class="btn btn-secondary">
				    <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
				  </label>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script src="/js/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script>
	function onScanSuccess(qrCodeMessage) {
		html5QrcodeScanner.clear();
		window.location.replace('/angsuran/mobile-view/'+qrCodeMessage);
	}

	var html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", { fps: 10, qrbox: 250 });
	html5QrcodeScanner.render(onScanSuccess);

</script>
<script src="<a class="vglnk" href="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" rel="nofollow"><span>https</span><span>://</span><span>rawgit</span><span>.</span><span>com</span><span>/</span><span>schmich</span><span>/</span><span>instascan</span><span>-</span><span>builds</span><span>/</span><span>master</span><span>/</span><span>instascan</span><span>.</span><span>min</span><span>.</span><span>js</span></a>"></script>
<script type="text/javascript">
    var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
    scanner.addListener('scan',function(content){
        // alert(content);
        window.location.replace('/angsuran/mobile-view/'+content);
    });
    Instascan.Camera.getCameras().then(function (cameras){
        if(cameras.length>0){
            scanner.start(cameras[0]);
            $('[name="options"]').on('change',function(){
                if($(this).val()==1){
                    if(cameras[0]!=""){
                        scanner.start(cameras[0]);
                    }else{
                        alert('No Front camera found!');
                    }
                }else if($(this).val()==2){
                    if(cameras[1]!=""){
                        scanner.start(cameras[1]);
                    }else{
                        alert('No Back camera found!');
                    }
                }
            });
        }else{
            console.error('No cameras found.');
            alert('No cameras found.');
        }
    }).catch(function(e){
        console.error(e);
        alert(e);
    });
</script>
@endsection