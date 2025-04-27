<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
     crossorigin="anonymous">
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
                <div class="row">
                    <div class="col-md-12 alert alert-success text-center" id="success" style="display: none;text-align:center;font-size:35px"></div>
                </div>
                <div class="row">
                    <div class="col-md-12 alert alert-danger text-center" id="error" style="display: none;text-align:center;font-size:20px"></div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center" style="text-align: -webkit-center;margin-top: 21px;">
                        <button type="button" onclick="JsQRScannerReady()" id="startScanner" style="display: none;text-align:center;font-size: xx-large" class="btn btn-info">Scan again</button>
                    </div>
                </div>
                <div class="qrscanner" id="scanner"></div>
            </div>
        </div>
        <div class="col-md-4"></div>

    </div>

    <div class="row  ">
      <div class="col" style="    position: relative;
    margin-top: 20rem!important;
    text-align: center;">

        <div class="row h-100 mt-5">

          <div class="col d-flex flex-column align-items-center justify-content-center mt-5">
            <h1 style=" font-size: 30px;" class="fw-bold"> وجه الكاميرا الى رمز الاستجابة السريع</h1>
        </div>

        </div>
      </div>
    </div>
  </div>


<script type="text/javascript">
    let jbScanner;
    let scanOnceFlag = false; // Flag to control scanning once

  function onQRCodeScanned(scannedText) {
        showHint(scannedText);
        console.log(scannedText);

        stopScanner();
  }
  function stopScanner() {
      if (jbScanner) {
          jbScanner.stopScanning();
          // Optionally, remove the scanner from the DOM
          document.getElementById("scanner").innerHTML = "";
        }
        document.getElementById('startScanner').style.display="inline";
    }

    function play() {
        const audio1 = new Audio("{{ asset('audio_file.mpeg') }}");
        audio1.addEventListener('canplaythrough', function() {
            audio1.play();
        }, false);
    }

    function play2() {
        const audio2 = new Audio("{{ asset('wrong.mpeg') }}");
        audio2.addEventListener('canplaythrough', function() {
            audio2.play();
        }, false);
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
                if(jsondata.status===true){
                    document.getElementById('success').innerHTML=jsondata.message;
                    document.getElementById('success').style.display="block";
                    document.getElementById('error').style.display="none";
                }
                else{
                    document.getElementById('error').innerHTML=jsondata.message;
                    document.getElementById('error').style.display="block";
                    document.getElementById('success').style.display="none";

                }

            }
            };
            xmlhttp.open("GET", "https://ticketola.expola-sa.com/api/parcode_check?parcode=" + serial, true);
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
        document.getElementById('startScanner').style.display="none";
        document.getElementById('success').style.display="none";
        document.getElementById('error').style.display="none";

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
