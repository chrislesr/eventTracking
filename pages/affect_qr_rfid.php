<?php
    session_start();
     include 'connexion.php';
     include '../functions.php';

      if(isset( $_SESSION['message'])){
      echo $_SESSION['message'];
      unset( $_SESSION['message']);
  }
  $_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
  $token = generate_csrf();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation de Formulaire</title>
<!-- Vendor CSS Files --> 
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">


  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js" rel="stylesheet"></script>
  <script src="../assets/js/html5-qrcode.min.js"></script>
    <style>
        .popup {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>
</head>
<body>
	<div class="container">
					<div class="col-md-12">
				<div class="qr-code">
				<div id="qr-reader" style="display: none; position: absolute; top: 5px; right: 50px;"></div>
			</div>
			</div>
					</div>
    <div class="container mt-5">
    	<i>Cette page sera visible par l'organisateur et full control1</i>
        <form method="post" action="enregistrement" enctype="multipart/form-data">
        	<div class="mb-3">
                <label for="type" class="form-label">Code invitation</label>
                <input type="text" name="rfid" class="form-control" placeholder="Scanner la carte RFID" required>
            </div>
        	<div class="input-group mb-3">
			  <span class="input-group-text" id="btn-search-qr" style="cursor: pointer;">@</span>
			  <input type="text" class="form-control" name="qrcode" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" id="search_text">
			</div>
            <div class="mb-3">
                <input type="hidden" name="control" value="int_affect_invt">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</body>
<footer>
	<!-- Vendor JS Files -->
	<script src="../assets/js/jquery-3.6.0.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <!-- <script src="../assets/js/main.js"></script> -->
</footer>
<script>
	$(document).ready(function() {
		let isScanning = false;
		let html5QrCode;

		$('#btn-search-clear').on('click', function() {
			$('#search_text').val('');
			$('#result').html('');
			$('#result').hide();
			$('#results').hide();
		});

$('#btn-search-qr').on('click', function() {
	console.log('ok');
    if (!isScanning) {
        $('#qr-reader').show();
        html5QrCode = new Html5Qrcode("qr-reader");

        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        html5QrCode.start(
        	isMobile ? { facingMode: { exact: "environment"} } : { facingMode: "environment"},
            {
                fps: 10,
                qrbox: 250
            },
            qrCodeMessage => {
                $('#search_text').val(qrCodeMessage);
                $('#qr-reader').hide();
                html5QrCode.stop();
                isScanning = false;
                $('#search_text').trigger('input');

            },
            errorMessage => {
                console.log(`Le QR Code n'est plus devant la caméra.`);
            }
        ).catch(err => {
            console.log(`Impossible de démarrer la numérisation, error: ${err}`);
        });
        isScanning = true;
    } else {
        html5QrCode.stop().then(() => {
                $('#qr-reader').hide();
                isScanning = false;
            }).catch(err => {
                console.log(`Impossible d'arrêter la numérisation, error: ${err}`);
            });
        }
    });

	});

	function clearSearch() {
    $('#search_text').val('');
    $('#result').html('');
    $('#result').hide(); 
    $('#results').hide();
}

</script>
</html>
