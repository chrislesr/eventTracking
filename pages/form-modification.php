<?php

  include "header.php";

  if(!checkRole(['LEVEL_1','LEVEL_NIV4','LEVEL_NIV1']) ){
    $previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }
  if (isset($_SESSION['poste']) && $_SESSION['poste'] == 'porte') {
    $previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }

?>
<?php
if (isset($_GET['view']) AND isset($_GET['verif'])) {
	$action = htmlspecialchars(trim($_GET['view']));
	$id = htmlspecialchars(trim($_GET['verif']));

	if($action == 'participant'){
		$result = getParticepant($id,$conn);
	}
	elseif ($action == 'comite') {
		$result = getComitard($id,$conn);
	}
  elseif ($action == 'categorie') {
    $result = getCategoriePlace($id,$conn);
  }
}
else{
		$action = '';
		$result = '';

	}
?>
<?php
  $evenement = $_SESSION['usersInfo']['id_event_cm_fk'] ?? $_SESSION['usersInfo']['id_env'];

?>
<?php pagetitleMain('form Participant','Formulaire d\'insertion d\'un participant')?>

    <!-- Team Section -->
    <section id="team" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
         <div class="row justify-content-between align-items-center">

         <?php 
         if($action == 'participant'){
            echo '<div class="col-lg-8">
              <p>Formulaire de modification d\'un PARTICIPANT</p>
            </div>';
            echo '<div class="col-lg-2 text-end">';
              btnLsParticipant();
            echo '</div>';
        }
        elseif ($action == 'comite') {
        	echo '<div class="col-lg-8">
              <p>Formulaire de modification d\'un comitard</p>
            </div>';
            echo '<div class="col-lg-2 text-end">';
             btnLsComite();
            echo '</div>';
        }

        elseif ($action == 'categorie') {
          echo '<div class="col-lg-8">
              <p>Formulaire de modification d\'une place</p>
            </div>';
            echo '<div class="col-lg-2 text-end">';
             btnLsPlace();
            echo '</div>';
        }

        ?>
          </div>

      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
         <div class="col-lg-6">
            <form action="action_midif" method="post" class="form_add" data-aos="fade-up" data-aos-delay="500" enctype="multipart/form-data">
            	<?php if($action == 'participant'){?>
              <div class="row gy-4">

                <div class="col-md-12">
                  <input type="text" class="form-control" name="name_part" placeholder="Nom du participant" value="<?=$result['nom_part']?>">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="email_part" placeholder="Email du participant" value="<?=$result['email_part']?>">
                </div>
                <div class="col-md-12">
                  <select name="sexe" class="form-control" required>
                    <option value="<?=$result['sexe_part']?>">Sélectionner le genre</option>
                    <option value="femme">Femme</option>
                    <option value="homme">Homme</option>
                </select>
                </div>

                <div class="col-md-12">
                  <select name="categorie" class="form-control" required>
                    <option value="<?=$result['id_cat_inviter_fk']?>">Sélectionner le catégorie</option>
                    <?php

                        $data = lsCategorieInviter($evenement);
                        $dataEv = lsEventUsersId($_SESSION['id_user']);
                        $type = $dataEv['nom_evenement'];

                        $dataDefault = lsDefaultCategorieInviter($type);
                        
                        foreach($dataDefault as $aff)
                        {
                            echo '<option value="'.$aff['id_cat_inv'].'">'.$aff['nom_categorie'].'</option>';
                        }
                        foreach($data as $aff1)
                        {
                            echo '<option value="'.$aff1['id_cat_inv'].'">'.$aff1['nom_categorie'].'</option>';
                        }
                    ?>
                  </select>
                </div>

                <div class="col-md-12">
                    <input type="text" class="form-control" name="tel" value="<?=$result['tel_part']?>">
                </div>

                <div class="col-md-12">
                  <input type="file" class="form-control" name="image" accept="image/*" >
                </div>
                <div class="col-md-12 text-center">
                    <input type="hidden" name="control" id="control" value="edit_participant">
                    <input type="hidden" name="token" id="token" value="<?=$token?>">
                    <input type="hidden" name="id" value="<?=$id?>">
                    <div id="message"></div> 
                  <button type="submit" id="add_btn">Enregistrer</button>
                </div>

              </div>
          <?php }elseif ($action == 'comite') {?>
          	<div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="com_name" id="com_name" class="form-control" placeholder="Nom du comitard" value="<?=$result['nom_inviter']?>">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="com_email" id="com_email" placeholder="Email du comitard" value="<?=$result['email_inviter']?>">
                </div>

                <div class="col-md-12">
                	<select class="form-control" name="sexe" id="sexe" required="">
                			<option value="<?=$result['sexe_inviter']?>">...</option>
		                	<option value="femme">Femme</option>
		                	<option value="homme">Homme</option>
                	</select>
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="tel" id="tel" placeholder="999333444" minlength="6" maxlength="9" value="<?=$result['tel_inviter']?>">
                </div>

                <div class="col-md-12">
                	<select class="form-control" name="fonction" id="fonction" required="">
                			<option value="<?=$result['id_niveau_inv_fk']?>">Sélectioner le niveau</option>
		                	<?php
		                        $data = lsniveau('comite');
		                        foreach($data as $aff)
		                        {
		                            echo '<option value="'.$aff['id_niv'].'">'.ucfirst($aff['nom_niv']).'</option>';
		                        }
		                    ?>
                	</select>
                </div>

                <div class="col-md-12">
                	<select class="form-control" name="poste" id="poste" required="">
                			<option value="<?=$result['poste_inviter']?>">Sélectionner une poste</option>
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

                <div class="col-md-12">
                  <input type="file" class="form-control" name="image" accept="image/*" placeholder="Photo de profil">
                </div>

                <div class="col-md-12">
                  <input type="hidden" name="control" id="control" value="edite_comite">
                  <input type="hidden" name="verif" id="verif" value="comite">
		              <input type="hidden" name="token" id="token" value="<?=$token?>">
                  <input type="hidden" name="id" value="<?=$result['id_inviter']?>">
                </div>

                <div class="col-md-12 text-center">
                   <div id="message"></div> 
                  <button type="submit" id="add_btn">Ajouter Comitard</button>
                </div>

              </div>
          <?php }else if($action == 'categorie'){?>
            <div class="row gy-4">

                <div class="col-md-12">
                  <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Nom du comitard" value="<?= $result['nom_categorie']?>">
                </div>

                <div class="col-md-12">
                  <input type="hidden" name="control" id="control" value="edite_categorie">
                  <input type="hidden" name="verif" id="verif" value="categorie">
                  <input type="hidden" name="token" id="token" value="<?=$token?>">
                  <input type="hidden" name="id" value="<?=$result['id_cat_inv']?>">
                </div>

                <div class="col-md-12 text-center">
                   <div id="message"></div> 
                  <button type="submit" id="add_btn">Ajouter place</button>
                </div>

              </div>
          <?php }?>
            </form>
          </div><!-- End Contact Form -->
          

          <div  id="lastClientInfo" class="col-lg-6 ">
          <?php if($action == 'participant'){?>
            <div class="row gy-4">

              <div  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                  <img src="../assets/img/photoProfil/<?=$result['image_part']?>" width="30%">

                  <h3>Nom du comitard&nbsp;</h3>
                  <p><?=$result['nom_part']?></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Coordoné&nbsp;</h3>
                  <p><?=$result['tel_part']?></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Comitard&nbsp;</h3>
                  <p><?=$result['email_part']?></p>
                </div>
              </div><!-- End Info Item -->
            </div>
    <?php }if($action == 'comite'){?>
    		<div class="row gy-4">

              <div  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                  <img src="../assets/img/photoProfil/<?=$result['image_inviter']?>" width="30%">

                  <h3>Nom du comitard&nbsp;</h3>
                  <p><?=$result['nom_inviter']?></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Coordoné&nbsp;</h3>
                  <p><?=$result['tel_inviter']?></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Comitard&nbsp;</h3>
                  <p><?=$result['email_inviter']?></p>
                </div>
              </div><!-- End Info Item -->

            </div>
    <?php }?>
    <?php if($action == 'categorie'){?>
          <div class="row gy-4">

              <div  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                  <img id="lastImage" width="30%">

                  <h3>Place reservée&nbsp;</h3>
                  <p><?=$result['nom_categorie']?></p>
                </div>
              </div><!-- End Info Item -->

            </div>
    <?php }?>
          </div>

        </div>

      </div>
    </section><!-- /Team Section -->
    <div id="popup" class="popup"></div> 
  </main>
  <!-- start footer and the end body -->
  <?php 
  include 'footer.php';
?>

<script>
    const rfidForm = document.getElementById('rfidForm');
    const lastClientInfo = document.getElementById('lastClientInfo');
    const qrcodeForm = document.getElementById('qrcodeForm');
    const qrcodeLect = document.getElementById('qrcodeLect');
    const btnRfid = document.getElementById('btnRfid');
    const btnQrcode = document.getElementById('btnQrcode');
    btnRfid.addEventListener('click', (e) => {
      e.preventDefault();
        rfidForm.classList.remove('d-none');
        lastClientInfo.classList.remove('d-none');
        qrcodeForm.classList.add('d-none');
        qrcodeLect.classList.add('d-none');
        btnRfid.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Login
        btnQrcode.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Register
    });

    btnQrcode.addEventListener('click', (e) => {
      e.preventDefault();
        rfidForm.classList.add('d-none');
        lastClientInfo.classList.add('d-none');
        qrcodeForm.classList.remove('d-none');
        qrcodeLect.classList.remove('d-none');
        btnQrcode.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Register
        btnRfid.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Login
    });

     $('#btnRfid').on('click', function() {
      $('#search_text').val('');
      $('#search_text_rfid').val('');

  });

  $('#btnQrcode').on('click', function() {
      $('#search_text').val('');
      $('#search_text_rfid').val('');

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
                $('#alert_camera').hide().removeClass('success').removeClass('danger');

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
    $('#search_text').val('');
    $('#result').html('');
    $('#result').hide(); 
    $('#results').hide();
}

</script>
<script src="../assets/js/main-form.js"></script>