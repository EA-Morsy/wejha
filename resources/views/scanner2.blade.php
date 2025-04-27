<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('styles.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <style>
    video{
        width: 100%
    }
    .qrPreviewVideo{
        border-radius: 10px
    }
  </style>
      <script type="text/javascript" src="{{ asset('jsPretty/jsqrscanner.nocache.js') }}"></script>
</head>

<body>

  <div class="container-fluid">
    <br><br>
    <div class=" row" style="position: absolute;width:100%">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div style="width: 90%;height: 40%;margin: auto">
                <div class="qrscanner" id="scanner"></div>
            </div>
        </div>
        <div class="col-md-4"></div>

    </div>

    <div class="row  ">
      <div class="col mt-5" style="margin-top: 5rem!important">

        <div class="row h-100 mt-5">

          <div class="col d-flex flex-column align-items-center justify-content-center mt-5">
            <h1 style="color: #fff; font-size: 30px;" class="fw-bold"> وجه الكاميرا الى رمز الاستجابة السريع</h1>
            <div class="position-relative">
              <input id="scannedTextMemo"
                style=""
                class="form-control borderless" type="text" readonly>
            </div>
          </div>
              <div class="col-md-12 alert alert-success text-center" id="startScanner" style="display: none;">
                <button type="button" onclick="JsQRScannerReady()" class="btn btn-inf">Scan again</button>
              </div>
              <div class="col-md-12 alert alert-success text-center" id="success" style="display: none;"></div>
              <div class="col-md-12 alert alert-danger text-center" id="error" style="display: none;"></div>
           
        </div>
      </div>
    </div>
  </div>


<script type="text/javascript">
    let jbScanner;
    let scanOnceFlag = false; // Flag to control scanning once

  function onQRCodeScanned(scannedText) {
    var scannedTextMemo = document.getElementById("scannedTextMemo");
    if (scannedTextMemo) {
        scannedTextMemo.value = scannedText;
        showHint(scannedText);
        stopScanner();

    }
    // var scannedTextMemoHist = document.getElementById("scannedTextMemoHist");
    // if(scannedTextMemoHist)
    // {
    // 	scannedTextMemoHist.value = scannedTextMemoHist.value + '\n' + scannedText;
    // }
  }
  function stopScanner() {
      if (jbScanner) {
          jbScanner.stopScanning();
          // Optionally, remove the scanner from the DOM
          document.getElementById("scanner").innerHTML = "";
        }
        document.getElementById('startScanner').style.display="block";
    }

    function play() {
        var audio = new Audio("{{ asset('audio_file.mpeg') }}");
        audio.play();
    }
    function showHint(serial) {

        if (serial.length == 0) {
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                jsondata=JSON.parse(this.responseText);
                    play();
                if(jsondata.status===0){
                    document.getElementById('error').innerHTML=jsondata.message;
                    document.getElementById('error').style.display="block";
                    document.getElementById('success').style.display="none";
                }
                else{
                    document.getElementById('success').innerHTML=jsondata.message;
                    document.getElementById('success').style.display="block";
                    document.getElementById('error').style.display="none";

                }

            }
            };
            xmlhttp.open("GET", "{{ route('checkSerial') }}?s=" + serial, true);
            xmlhttp.send();
        }
    }

  function provideVideo() {
    var n = navigator;

    if (n.mediaDevices && n.mediaDevices.getUserMedia) {
      return n.mediaDevices.getUserMedia({
        video: {
          facingMode: "environment"
        },
        audio: false
      });
    }

    return Promise.reject('Your browser does not support getUserMedia');
  }

  function provideVideoQQ() {
    return navigator.mediaDevices.enumerateDevices()
      .then(function (devices) {
        var exCameras = [];
        devices.forEach(function (device) {
          if (device.kind === 'videoinput') {
            exCameras.push(device.deviceId)
          }
        });

        return Promise.resolve(exCameras);
      }).then(function (ids) {
        if (ids.length === 0) {
          return Promise.reject('Could not find a webcam');
        }
        return navigator.mediaDevices.getUserMedia({
          video: {
            'optional': [{
              'sourceId': ids.length === 1 ? ids[0] : ids[1]
            }]
          }
        });
      });
  }

  function JsQRScannerReady() {
     jbScanner = new JsQRScanner(onQRCodeScanned);
    jbScanner.setSnapImageMaxSize(300);
    var scannerParentElement = document.getElementById("scanner");
    if (scannerParentElement) {
      jbScanner.appendTo(scannerParentElement);
    }
  }
</script>
</body>

</html>
