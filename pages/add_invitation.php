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
    <div class="container mt-5">
       <div class="d-flex justify-content-center">
        <button class="btn btn-primary me-4 btn-lg" id="btnRfid">FORM RFID</button>
        <button class="btn btn-secondary" id="btnQrcode">FORM QR Code</button>

    </div>
        <div id="rfidForm" class="mt-4">
        <p>Formulaire d'enregistrèment d'une Invitation RFID</p>	
        <form method="post" action="enregistrement" enctype="multipart/form-data">
        <div class="mb-3" id="rfid_input">
            <label for="code_rfid_value" class="form-label">Code RFID</label>
            <input type="text" class="form-control" id="code_rfid_value" name="code_value" required>
        </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type d'invitation</label>
                <select class="form-control" name="type" required>
                    <option value="">...</option>
                    <?php
                        $data = lsTypeInvitation();
                        foreach($data as $aff)
                        {
                            echo '<option value="'.$aff['id_typ_inv'].'">'.$aff['nom_typ_inv'].'</option>';
                        }
                    ?>
                </select>
            </div>
            
            
            
            <div class="mb-3">
                <input type="hidden" name="control" value="int_invitation_rfid">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <div id="qrcodeForm" class="mt-4 d-none">
        <p>Formulaire d'enregistrèment d'une Invitation QR code</p>
        <form method="post" action="enregistrement" enctype="multipart/form-data">
        <div class="mb-3" id="rfid_input">
            <label for="code_rfid_value" class="form-label">Code Qr</label>
            <input type="text" class="form-control" id="code_rfid_value" name="code_value" required>
        </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type d'invitation</label>
                <select class="form-control" name="type" required>
                    <option value="">...</option>
                    <?php
                        $data = lsTypeInvitation();
                        foreach($data as $aff)
                        {
                            echo '<option value="'.$aff['id_typ_inv'].'">'.$aff['nom_typ_inv'].'</option>';
                        }
                    ?>
                </select>
            </div>
            
            
            
            <div class="mb-3">
                <input type="hidden" name="control" value="int_invitation_qr">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
    </div>
</body>
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
</html>
