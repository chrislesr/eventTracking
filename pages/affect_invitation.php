<?php
    session_start();
     include 'connexion.php';
     include '../functions.php';
     $conn = Connexion::connect();
      if(isset( $_SESSION['message'])){
      echo $_SESSION['message'];
      unset( $_SESSION['message']);
  }
  if(isset($_SERVER['HTTP_REFERER'])){
    $_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
  }
  
  $token = generate_csrf();
 
    $data = lsEventUsersId($_SESSION['id_user']);
    $id_evenement = $data['id_env'];
  
  
  if($data['nom_evenement'] == 'fete'){
        $name_evenement = 'des invitations pour la Fête'; 
    }
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
    	
        <form method="post" action="affect_register" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="code_value" class="form-label">Code invitation</label>
                <select class="form-control" name="code_value" required>
                    <option value="">...</option>
                    <?php
                        $data = lsInvitation();
                        foreach($data as $aff)
                        {
                            if(!empty($aff['code_invitation'])){
                            echo '<option value="'.$aff['id_inv'].'">'.htmlspecialchars($aff['code_invitation']).'</option>';
                        }else{
                            echo '<option value="'.$aff['id_inv'].'">'.htmlspecialchars($aff['code_qr_invitation']).'</option>';
                        }
                        }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <input type="hidden" name="event" value="<?=$id_evenement?>">
            </div>
            <div class="mb-3">
                <input type="hidden" name="control" value="int_af_int_evt">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <input type="submit" name="" class="btn btn-primary">
        </form>

        <div>
            <h3 class="text-center">Liste <?= $name_evenement?></h3>
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
                        $data = lsAffect_invitation_event($id_evenement);
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
</html>
