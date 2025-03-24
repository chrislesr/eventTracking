<?php
  error_reporting(E_ALL);
  session_start(); 
  include '../connexion.php';
  include '../functions.php';
  include '../pages/funnctions_acces.php';
  if(!isset($_SESSION['id_user']) && !checkRole(['LEVEL_1','LEVEL_NIV4','LEVEL_NIV1'])){
    header('Location:../index');
    exit;
  }
?>
<?php
$conn = Connexion::connect();
$token = generate_csrf();
$id_evenement = htmlspecialchars($_SESSION['usersInfo']['id_env']);
if(isset($_SERVER['HTTP_REFERER'])){
$_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
}
?>
<?php
  $Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
  file_put_contents('UIDContainer.php',$Write);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>EventTracking</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
 
  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <!-- <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet"> -->
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
   <link rel="stylesheet" type="text/css" href="assets/css/AllStylescanner.css">
   <link rel="stylesheet" type="text/css" href="assets/css/StyleCodeBar.css">

  <!-- Bibliotheque qr code -->
  <script src="../assets/js/html5-qrcode.min.js"></script>
</head>
<body>

	<div class="container" id="show_user_data">
    
    <p id="btnClose" style="cursor: pointer;">Fermer</p>
		<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Erreur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            La carte est invalide
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

	<nav>
		<button class="nav-gene active">Code QR</button>
		<button class="nav-scan">RFID</button>
	</nav>

	<div class="scanner_qr">
	
		<h1>
			Bar code Scanner <i id="start-camera" class="bi"></i>

		</h1>
		<p id="alert_camera"></p>
		<div class="scanbar">
			<div class="img_scan"></div>
			<div id="qr-reader"></div>
		</div>
		<br><br>
		<div class="scanbar-details">
			<textarea id="texte_scan" autocomplete="off" hidden>Code scanner...</textarea >
		</div>
	</div>

	<div class="scanner_rfid" style="display: none;">
		<h1>
			Lecteur RFID
			<i class="bi bi-camera"></i>
		</h1>
		<div class="scanbar">
			<div class="img_scan"></div>
			<div id="qr-reader"></div>
		</div>
		<br><br>
		<div class="scanbar-details">

			<textarea id="texte_scan_rfid" class="d-none" autocomplete="off" hidden>Code scanner...</textarea >
		</div>
	</div>
</div>
</body>
<script src="assets/js/main.js"></script>
<!-- <script src="../assets/js/jquery-3.6.0.min.js"></script> -->
<script src="assets/js/jquery.min.js"></script>
<script>
      $(document).ready(function(){
        $('.nav-scan').on('click', function() {
         $("#texte_scan_rfid").load("UIDContainer.php");
        setInterval(function() {
          $("#texte_scan_rfid").load("UIDContainer.php");  
        }, 500);
      });
        $("#texte_scan_rfid").html(""); 
      });
    </script>
    <script>
      var token = '<?=$token ?>';
      var idEvent = '<?=$id_evenement ?>';
      var myVar = setInterval(myTimer, 1000);
      var myVar1 = setInterval(myTimer1, 1000);
      var oldID="";
      clearInterval(myVar1);

      function myTimer() {
        var getID=document.getElementById("texte_scan_rfid").innerHTML;
        oldID=getID;
        if(getID!="") {
          myVar1 = setInterval(myTimer1, 500);
          showUser(getID);
          clearInterval(myVar);
        }
      }
      
      function myTimer1() {
        var getID=document.getElementById("texte_scan_rfid").innerHTML;
        if(oldID!=getID) {
          myVar = setInterval(myTimer, 500);
          clearInterval(myVar1);
        }
      }
      
      function showUser(str) {
        if (str == "") {
          document.getElementById("show_user_data").innerHTML = "";
          return;
        } else {
          $.post('content.php', { texte: $('#texte_scan_rfid').val(), token: token, idEvent:idEvent }, function (data) {
                let result = JSON.parse(data);
                    if (result.error) {
                        $('#myModal .modal-body').text(result.error);
                        $('#myModal').modal('show');
                    } else {
                        // Stocker les informations récupérées dans le localStorage
                        localStorage.setItem('data', data);
                        // Rediriger vers la page de visualisation
                        window.location.href = 'visualisation.php';
                    }
                });
        }
      }
      
      // var blink = document.getElementById('blink');
      // setInterval(function() {
      //   blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
      // }, 750); 
      $('#btnClose').on('click', function(){
          window.location.href = '../pages/dashboard.php';
      });
    </script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
	 $(document).ready(function() {
        let isScanning = false;
        let html5QrCode;
        const img_scan = document.querySelector('.img_scan');
        const btnStart = document.querySelector('.bi');

        btnStart.classList.add('bi-camera');
	$('#texte_scan').on('input', function() {
			var query = $(this).val();

			if (query.length === 0) {
				$('#result').html('');
				$('#result').hide();
				$('#results').hide();

			}else{
				
        		$.post('content.php', { texte: $('#texte_scan').val(), token: token, idEvent:idEvent }, function (data) {
            	let result = JSON.parse(data);
		            if (result.error) {
		                $('#myModal .modal-body').text(result.error);
		                $('#myModal').modal('show');
		            } else {
		                // Stocker les informations récupérées dans le localStorage
		                localStorage.setItem('data', data);
		                // Rediriger vers la page de visualisation
		                window.location.href = 'visualisation.php';
		            }
        		});
			}
		});

$('#start-camera').on('click', function() {
	img_scan.style.display = "none";
	btnStart.classList.add('bi-stop-circle');
	 $('#alert_camera').hide().removeClass('success').removeClass('danger'); 
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
                $('#texte_scan').val(qrCodeMessage);
                $('#qr-reader').hide();
                html5QrCode.stop();
                isScanning = false;
                $('#texte_scan').trigger('input');
                $('#alert_camera').hide().removeClass('success').removeClass('danger'); 
                img_scan.style.display = "block";
                btnStart.classList.remove('bi-stop-circle');

            },
            errorMessage => {
                showPopup1("Le QR Code n'est plus devant la caméra.", 'danger');
            }
        ).catch(err => {
            showPopup1(`Impossible de démarrer la numérisation, error: ${err}`,'danger');
        });
        isScanning = true;
    } else {
        html5QrCode.stop().then(() => {
                $('#qr-reader').hide();
                isScanning = false;
                img_scan.style.display = "block";
                btnStart.classList.remove('bi-stop-circle');
            }).catch(err => {
               showPopup1(`Impossible d'arrêter la numérisation, error: ${err}`,'danger');

            });
        }
    });
function showPopup1(message, type) { 
    $('#alert_camera').text(message).addClass(type).show(); 
     
}

    });

    function clearSearch() {
    $('#texte_scan').val('');
    $('#result').html('');
    $('#result').hide(); 
    $('#results').hide();
}
</script>

</html>