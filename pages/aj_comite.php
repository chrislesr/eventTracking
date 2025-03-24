<?php
    session_start();
     include 'connexion.php';
     include '../functions.php';

     if(isset( $_SESSION['message'])){
      echo $_SESSION['message'];

      unset( $_SESSION['message']);
  }
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
        <form method="post" action="enregistrement">
            <div class="mb-3">
                <label for="username" class="form-label">Nom</label>
                <input type="com_name" class="form-control" name="com_name" >
            </div>

            <div class="mb-3">
                <label for="com_email" class="form-label">Email</label>
                <input type="text" class="form-control" name="com_email" >
            </div>

            <div class="mb-3">
                <label for="sexe" class="form-label">Sexe</label>
                <select name="sexe" class="form-label" required>
                	<option value="">...</option>
                	<option value="femme">Femme</option>
                	<option value="homme">Homme</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">N° Tél.</label>
                <input type="text" class="form-control" name="tel" >
            </div>

            <div class="mb-3">
                <label for="fonction" class="form-label">Fonction</label>
                <select name="fonction" class="form-control" required>
                    <option value="">...</option>
                    <?php
                        $data = lsniveau('comite');
                        print_r($data);
                        foreach($data as $aff)
                        {
                            echo '<option value="'.$aff['id_niv'].'">'.ucfirst($aff['nom_niv']).'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="poste" class="form-label">Poste</label>
                <select name="poste" class="form-label" required>
                    <option value="">...</option>
                    <?php 
                     $data = lsEventUser();
                     if($data['nom_evenement'] == 'fete'){
                        echo '<option value="vente">Distribution</option>';
                        echo '<option value="porte">Entrée</option>';
                     }else{
                        echo '<option value="vente">Vente</option>';
                        echo '<option value="porte">Entrée</option>';
                     }
                    ?>
                    
                </select>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Mot de pasee</label>
                <input type="text" class="form-control" name="password" >
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" >
            </div>
            <div class="mb-3">
                <input type="hidden" name="control" value="int_comite">
                <input type="hidden" name="token" value="<?=$token?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</body>
</html>