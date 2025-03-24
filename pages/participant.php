<?php
    session_start();
     include 'connexion.php';
     include '../functions.php';
     $conn = Connexion::connect();
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
    	<div class="d-flex justify-content-center">
        <button class="btn btn-primary me-4 btn-lg" id="btnRfid">FORM RFID</button>
        <button class="btn btn-secondary" id="btnQrcode">FORM QR Code</button>

    </div>

        <form method="post" action="enregistrement" enctype="multipart/form-data">
            <div class="mb-3" id="rfidForm">
                <label for="code_value" class="form-label">ID carte</label>
                <input type="text" class="form-control" name="code_value_id">
            </div>

            <div id="qrcodeForm" class="input-group mb-3 d-none">

              <span class="input-group-text" id="btn-search-qr" style="cursor: pointer;">@</span>
              <input type="text" class="form-control" name="code_value_qr" placeholder="Scanner Qr code" aria-label="QRcode" aria-describedby="basic-addon1" id="search_text">
            </div>

            <div class="mb-3">
                <label for="code_value" class="form-label">Nom du participant</label>
                <input type="text" class="form-control" name="name_part" placeholder="Nom du participant" required>
            </div>

            <div class="mb-3">
                <label for="sexe" class="form-label">Sexe</label>
                <select name="sexe" class="form-control" required>
                    <option value="">...</option>
                    <option value="femme">Femme</option>
                    <option value="homme">Homme</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie participant</label>
                <select name="categorie" class="form-control" required>
                    <option value="">...</option>
                    <?php
                        $data = lsCategorieInviter();
                        foreach($data as $aff)
                        {
                            echo '<option value="'.$aff['id_cat_inv'].'">'.$aff['nom_categorie'].'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="code_value" class="form-label">N° Tél.</label>
                <input type="text" class="form-control" name="tel">
            </div>
            <div class="mb-3">
                <input type="hidden" name="control" value="int_participant">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <input type="submit" name="" class="btn btn-primary">
        </form>

        <div>
            <h3 class="text-center">Liste des participants</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Evenement</th>
                        <th>Date d'enregistrement</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                       
                            $data = lsParticipants($_SESSION['id_user']);

                        
                        foreach ($data as $aff) {
                            echo "<tr>";
                            if(!empty($aff['code_invitation'])){
                                echo '<td>ID_'.$aff['code_invitation'].'</td>';
                            }else{
                                echo '<td>QR_'.$aff['code_qr_invitation'].'</td>';
                            }
                            
                            echo '<td>'.$aff['titre_evenement'].'</td>';
                            echo '<td>'.date('d/m/Y H:i:s', strtotime($aff['date_enreg_affect'])).'</td>';
                            echo "</tr>";
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script>
    const rfidForm = document.getElementById('rfidForm');
    const qrcodeForm = document.getElementById('qrcodeForm');
    const btnRfid = document.getElementById('btnRfid');
    const btnQrcode = document.getElementById('btnQrcode');

    btnRfid.addEventListener('click', () => {
        rfidForm.classList.remove('d-none');
        qrcodeForm.classList.add('d-none');
        btnRfid.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Login
        btnQrcode.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Register
    });

    btnQrcode.addEventListener('click', () => {
        rfidForm.classList.add('d-none');
        qrcodeForm.classList.remove('d-none');
        btnQrcode.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Register
        btnRfid.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Login
    });
</script>

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
