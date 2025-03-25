<?php
	include "header.php";
  $id = htmlspecialchars($_SESSION['id_user'],ENT_QUOTES, 'UTF-8');
  
  if($_SESSION['niveau']=='LEVEL_NIV4'){
    $id_evenement = $_SESSION['usersInfo']['id_env'];
    $dataUser = getUsers($id,$conn);
    $name = htmlspecialchars($dataUser['nom_utl'],ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($dataUser['postnom_utl'],ENT_QUOTES, 'UTF-8');
    $firstname = htmlspecialchars($dataUser['prenom_utl'],ENT_QUOTES, 'UTF-8');
    $sexe = htmlspecialchars($dataUser['sexe_utl'],ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($dataUser['email_utl'],ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($dataUser['tel_utl'],ENT_QUOTES, 'UTF-8');
    $image = htmlspecialchars($dataUser['image_utl'],ENT_QUOTES, 'UTF-8');
    $allName = ucfirst($name).' '.$lastname.' '.$firstname;
    if(empty($image)){
      $image = 'dafault.png';
    } 
    $nationalite = htmlspecialchars($dataUser['nationalite_utl'],ENT_QUOTES, 'UTF-8');
    $level = htmlspecialchars($dataUser['nom_niv'],ENT_QUOTES, 'UTF-8');

  }else if($_SESSION['niveau']=='LEVEL_NIV1'){
      $dataUser = getComitard($id,$conn);
      $name = htmlspecialchars($dataUser['nom_inviter'],ENT_QUOTES, 'UTF-8');
      $sexe = htmlspecialchars($dataUser['sexe_inviter'],ENT_QUOTES, 'UTF-8');
      $email = htmlspecialchars($dataUser['email_inviter'],ENT_QUOTES, 'UTF-8');
      $phone = htmlspecialchars($dataUser['tel_inviter'],ENT_QUOTES, 'UTF-8');
      $image = htmlspecialchars($dataUser['image_inviter'],ENT_QUOTES, 'UTF-8');
      $level = htmlspecialchars($dataUser['nom_niv'],ENT_QUOTES, 'UTF-8');
      $nationalite = 'inconnue';
      $allName = ucfirst($name);
      if(empty($image)){
        $image = 'dafault.png';
      } 
  }
?>
<?php 
  if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $csrf_token = $_SESSION['csrf_token'];
}
?>
<main class="main">
<form id="myform">
  <input type="hidden" id="token_csrf" value="<?php echo $_SESSION['csrf_token']; ?>">
</form>
    <!-- Stats Section -->
    
    <section id="stats" class="stats section dark-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
		  <br>
        <div class="row gy-4">
          <div class="col-lg-3 col-md-6">
            <div class="row d-flex align-items-center w-100 h-100">
                  <div class="content-profil text-center">
                    <p><strong><?php echo $allName?></strong></p>
                    <img src="../assets/img/photoProfil/<?=$image?>">
                    <p><i><?= ucfirst($level)?></i></p>
                  </div>
            </div>
          </div><!-- End Stats Item -->
          <div class="col-lg-9 col-md-6">
            <div class="row gy-4 d-flex align-items-center w-100 h-100">
              <div class="col-lg-6 col-md-6">
                <ul class="info-profil">
                  <li>Mon profil</li>
                  <li><i class="bi bi-envelope"></i> <?=$email?></li>
                  <li><i class="bi bi-calendar"></i> <?= date('d-m-Y')?> <i class="bi bi-clock"></i> <?= date('H:i')?></li>
                </ul>
              </div>
              <?php if($_SESSION['niveau']=='LEVEL_NIV4'){?>
              <div class="col-lg-6 col-md-6 border">
                <div class="row py-2 text-center">
                  
                  <div class="col-lg-4 col-md-6">
                    <p class="messenger-profil"><a href="#"><i class="bi bi-messenger fs-2"></i></a></p>
                    <p>Message</p>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <p class="messenger-profil"><a href="#"><i class="bi bi-bell-fill fs-2"></i></a></p>
                    <p>Notification</p>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <p class="messenger-profil"><a href="#"><i class="bi bi-speedometer2 fs-2"></i></a></p>
                    <p>Mouvement</p>
                  </div>
                </div>
              </div>
            <?php }?>
            </div>
           </div><!-- End Stats Item -->
        </div>
      </div>
      <div class="menu-profil  w-100">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <nav class="nav-profil">
            <button class="btn-profile active">Profil</button>
            <button class="btn-edit">Modifier le profil</button>
            <button class="btn-password">Mot de passe</button>
          </nav>
        </div>
      </div>
    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section section-profil ">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Image du profil<br></p>
      </div><!-- End Section Title -->

      <div class="container border p-4" data-aos="fade-up" data-aos-delay="100" >

        <div class="row d-flex align-items-center">

          <div class="col-xl-3 col-md-6 " data-aos="zoom-in" data-aos-delay="200">
              <form class="form-img" enctype="multipart/form-data">
                <input type="file" name="image-edit" id="imageInput" accept="image/*" hidden>
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="csrf_token" value="<?=$token?>">
                    <img id="profileImage" class="shadow" src="../assets/img/photoProfil/<?=$image?>">
                    <div class="contentEdit-profil my-2">
                      <p class="btn btn-primary" id="importButton"><i class="bi bi-pencil"></i> Importer une photo</p>
                      <button type="button" id="saveButton" class="btn btn-success" style="display:none;">Modier l'image</button>
                    </div>
              </form>
          </div><!-- End Service Item -->

          <div class="col-xl-9 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="row">
              <?php if($_SESSION['niveau']=='LEVEL_NIV4'){?>
              <div class="biogra">
                  <p><span>Nom </span>: <?=ucfirst($name)?></p>
              </div>
              <div class="biogra">
                  <p><span>Post nom </span>: <?=ucfirst($lastname)?></p>
              </div>
              <div class="biogra">
                  <p><span>Prénom </span>: <?=ucfirst($firstname)?></p>
              </div>
            <?php }else{
              echo '<div class="biogra">
                  <p><span>Nom </span>: '.ucfirst($allName).'</p>
              </div>';
            }?>
              <div class="biogra">
                  <p><span>Sexe </span>: <?=ucfirst($sexe)?></p>
              </div>
              <div class="biogra">
                  <p><span>E-mail </span>: <?=ucfirst($email)?></p>
              </div>
              <div class="biogra">
                  <p><span>Numéro tél.</span>: (+243) <?=ucfirst($phone)?></p>
              </div>
            </div>
          </div><!-- End Service Item -->
        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Services Section -->
    <section id="services" class="services section section-edit d-none">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Info</h2>
        <p>Modier info profil<br></p>
      </div><!-- End Section Title -->

      <div class="container border p-4" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">
          <div class="col-xl-12 col-md-12" data-aos="zoom-in" data-aos-delay="200">
            <form action="action_midif" method="post" class="form_add" data-aos="fade-up" data-aos-delay="500" enctype="multipart/form-data">
              <div class="row gy-4">
                <div class="col-md-12 input_disable">
                  <label>Adresse électronique</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?=$email?>" readonly>
                </div>
                <div class="col-md-6">
                  <label>Nom </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nom" value="<?=$name?>">
                </div>
                <?php if($_SESSION['niveau']=='LEVEL_NIV4'){?>
                  <div class="col-md-6">
                    <label>Postnom </label>
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Postnom" value="<?=$lastname?>">
                </div>
                 <div class="col-md-6">
                  <label>Prénom </label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Prénom" value="<?=$firstname?>">
                </div>
                <div class="col-md-6">
                  <label>Nationalité </label>
                    <input type="text" name="nationality" id="nationality" class="form-control" placeholder="Prénom" value="<?=$nationalite?>">
                </div>
                <?php }?>

                <div class="col-md-6">
                  <label>Sexe</label>
                  <select class="form-control" name="sexe" id="sexe" required="">
                      <option value="<?=$sexe?>"><?=$sexe?></option>
                      <option value="femme">Femme</option>
                      <option value="homme">Homme</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Numéro tél. </label>
                  <input type="text" class="form-control" name="phone" id="phone" placeholder="999333444" minlength="6" maxlength="9" value="<?=$phone?>">
                </div>

                <div class="col-md-12">
                  <input type="hidden" name="control" id="control" value="edit_profil">
                  <input type="hidden" name="token" id="token" value="<?=$token?>">
                  <input type="hidden" name="id" value="<?=$id?>">
                  <div id="message"></div>  
                </div>

                <div class="col-md-12 text-center">
                   <div id="message"></div> 
                  <button type="submit" class="btn btn-primary" id="add_btn">Enregistrer</button>
                </div>

              </div>
            </form>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Services Section -->
    <section id="services" class="services section section-password d-none">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Mot de passe<br></p>
      </div><!-- End Section Title -->

      <div class="container border p-4" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">
                <div class="col-lg-6">
                  <form action="action_midif" method="post" class="form_add" data-aos="fade-up" data-aos-delay="500" enctype="multipart/form-data">

                    <div class="row gy-4">
                      <div class="col-md-6">
                        <p>Modification</p>
                      </div>
                      <div class="col-md-12">
                        <input type="password" class="form-control" name="last_password" id="last_password" placeholder="Ancien mot de passe" autocomplete="off" required="">
                      </div>

                      <div class="col-md-12">
                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Nouveau mot de passe" minlength="6" required="">
                        <?php if(isset($_SESSION['message_input_p'])){
                          echo '<i class="text-danger">'.$_SESSION['message_input_p'].'</i>';
                        }?>
                      </div>

                      <div class="col-md-12">
                        <input type="password" class="form-control" name="cfNew_password" id="cfNew_password" placeholder="Repéter le nouveau mot de passe" minlength="6" required="">
                        <?php if(isset($_SESSION['message_input_p'])){
                          echo '<i class="text-danger">'.$_SESSION['message_input_p'].'</i>';
                          unset($_SESSION['message_input_p']);
                        }?>
                        
                      </div>
                      <div class="col-md-12">
                        <input type="hidden" name="control" id="control" value="edit_password">
                        <input type="hidden" name="token" id="token" value="<?=$token?>">
                        <input type="hidden" name="id" value="<?=$id?>"> 
                      </div>
                      <?php if(isset($_SESSION['message_input'])){
                        echo '<div class="message_input">
                              <p style="color:red">'.$_SESSION['message_input'].'</p>
                              </div>';
                              unset($_SESSION['message_input']); 
                      }?>
                      
                      <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                      </div>
                    </div>
                  </form>
                </div><!-- End Contact Form -->
        </div>

      </div>

    </section><!-- /Services Section -->
    <div id="popup" class="popup"></div> 
  </main>
  <!-- start footer and the end body -->
  <?php 
  include 'footer.php';
?>
<script src="../assets/js/script_profil.js"></script>