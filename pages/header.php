<?php
  error_reporting(E_ALL);
  session_start(); 
  include 'connexion.php';
  include '../functions.php';
  include 'funnctions_acces.php';
  if(!isset($_SESSION['id_user']) && !checkRole(['LEVEL_1','LEVEL_NIV4','LEVEL_NIV1'])){
    header('Location:../index');
    exit;
  }
?>
<?php
$conn = Connexion::connect();
$token = generate_csrf();
if(isset($_SERVER['HTTP_REFERER'])){
$_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
}
?>
<?php 
if($_SESSION['niveau']=='LEVEL_NIV4'){
    $id_evenement = $_SESSION['usersInfo']['id_env'];
  }
  if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $csrf_token = $_SESSION['csrf_token'];
}
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
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">

  <!-- Bibliotheque qr code -->
  <script src="../assets/js/html5-qrcode.min.js"></script>
</head>

<body class="index-page">
<div id="popup" class="popup"></div> 
<?php include "alert_popup.php";?>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">Event-Tracking</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="dashboard" class="active">Acceuil</a></li>
          
          <li class="dropdown"><a href="#"><span>services</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <?php if(checkRole(['LEVEL_1','LEVEL_NIV4'])){?>
              <li class="dropdown"><a href="#"><span>Invitations</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="form-invitation">Ajouter</a></li>
                  <li><a href="form-liaison">Affecter une carte/Qr code</a></li>
                  <li><a href="liste-invitations">Liste invitation</a></li>
                  <li><a href="../scanner/index">Lecture d'invitation</a></li>
                </ul>
              </li>

              <li class="dropdown"><a href="#"><span>Comité</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="form-comite">Ajouter</a></li>
                  <li><a href="liste-comites">Liste Comitards</a></li>
                </ul>
              </li>
              <li class="dropdown"><a href="#"><span>Place reservée</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="form-place">Ajouter</a></li>
                  <li><a href="liste-place">Liste place</a></li>
                </ul>
              </li>
            <?php }?>

              <li class="dropdown"><a href="#"><span>Participant</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <?php if(isset($_SESSION['poste']) && $_SESSION['poste'] != 'porte'){
                    echo '<li><a href="form-participant">Ajouter</a></li>';
                  }elseif ($_SESSION['niveau'] == 'LEVEL_NIV4') {
                     echo '<li><a href="form-participant">Ajouter</a></li>';
                  }?>
                  <li><a href="liste-participants">Liste participants</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <?php if(checkRole(['LEVEL_NIV1'])){
            if(isset($_SESSION['poste']) AND $_SESSION['poste'] == 'porte'){
              echo '<li><a href="Scanning">Scanner</a></li>';
            }
          }?>
          
          <?php if(checkRole(['LEVEL_1','LEVEL_NIV4'])){?>
          <li class="dropdown"><a href="#"><span>Paramètre</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="profil">Mon Profil</a></li>
              <li><a href="rapports">Rapport</a></li>
            </ul>
          </li>
          <li class="icon-nav" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="cursor: pointer;"><i class="bi bi-bell-fill fs-3"></i><span id="nbrenotification"></span></li>
        <?php }?>
        <?php if(checkRole(['LEVEL_NIV1'])){?>
          <li class="dropdown"><a href="#"><span>Paramètre</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="profil">Mon Profil</a></li>
              <li><a href="rapports">Rapport</a></li>
            </ul>
          </li>
        <?php }?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="cta-btn" href="../deconnexion">Se déconnecté</a>

    </div>
  </header>
  <?php include "includ-modal.php";?>
<form id="myform">
  <input type="hidden" id="token_csrf" value="<?php echo $_SESSION['csrf_token']; ?>">
</form>

 <script src="../assets/js/script-notification.js"></script>