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
  
  // $data = lsEventUsersId($_SESSION['id_user']);
  // print_r($data);exit;
  // $id_evenement = $data['id_env'];

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
            <div class="col-lg-6">
              <p>Formulaire PARTICIPANT</p>
            </div>
            <div class="col-lg-4 text-end">
              <?php btnLsParticipant()?>
            </div>
          </div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
         <div class="col-lg-6">
            <form id="myForm_add" class="form_add" data-aos="fade-up" data-aos-delay="500" enctype="multipart/form-data">
              <div class="row gy-4">
                <div class="d-flex justify-content-center">
                <button class="btn btn-primary me-4 btn-lg" id="btnRfid">FORM RFID</button>
                <button class="btn btn-secondary" id="btnQrcode">FORM QR Code</button>
          </div>
                <div id="rfidForm" class="col-md-12">

                  <textarea name="code_value_id" id="search_text_rfid" class="form-control" placeholder="Please Tag your Card / Key Chain to display ID" rows="1" cols="1" autocomplete="off" readonly></textarea>
                </div>

                <div id="qrcodeForm" class="col-md-12 d-none" >
                  <div class="row">
                    <div class="col-9">
                      <input type="text" class="form-control" name="code_value_qr" placeholder="Scanner Qr code" aria-label="QRcode" aria-describedby="basic-addon1" id="search_text">
                    </div>
                    <div class="col-3">
                      <p class="btn btn-danger" id="btn-search-qr" style="cursor: pointer;">Scanner</p>
                    </div>
                  </div>
                  
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="name_part" placeholder="Nom du participant" required>
                </div>
                <div class="col-md-12">
                  <input type="text" class="form-control" name="first_part" placeholder="Prénom du participant" required>
                </div>

                 <div class="col-md-12">
                  <input type="text" class="form-control" name="email_part" placeholder="Email du participant">
                </div>

                <div class="col-md-12">
                  <select name="sexe" class="form-control" required>
                    <option value="">Sélectionner le genre</option>
                    <option value="femme">Femme</option>
                    <option value="homme">Homme</option>
                </select>
                </div>

                <div class="col-md-12">
                  <select name="categorie" class="form-control" required>
                    <option value="">Sélectionner la place reservée</option>
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

                    <option value="">Clic sur menu service /place pour ajouter d'autre</option>
                  </select>

                </div>

                <div class="col-md-12">
                    <input type="text" class="form-control" name="tel" placeholder="Numéro de téléphone">
                </div>

                <div class="col-md-12">
                  <input type="file" class="form-control" name="image" accept="image/*" placeholder="Photo de profil">
                </div>
                <div class="col-md-12 text-center">
                    <input type="hidden" name="control" id="control" value="int_participant">
                    <input type="hidden" name="token" id="token" value="<?=$token?>">
                    <input type="hidden" name="verif" id="verif" value="participant">
                    <input type="hidden" name="action" value="insert">
                    <div id="message"></div> 
                  <button type="submit" id="add_btn">Enregistrer</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->
          
          <div  id="lastClientInfo" class="col-lg-6 ">
            <div class="row gy-4">

              <div  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                  <img id="lastImage" width="30%">

                  <h3>Nom du comitard&nbsp;</h3>
                  <p id="lastName"></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Coordoné&nbsp;</h3>
                  <p id="lastPhone"></p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Comitard&nbsp;</h3>
                  <p id="lastEmail"></p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

          
          <div id="qrcodeLect" class="col-lg-6 d-none">
            <div class="row gy-4">
            <style>
              .qr-code{
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                left: 50px;
              }
            </style>

            <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <h3>Scanner le code QR</h3>
                  <div class="col-md-12">
                    <div class="qr-code">
                      <div id="qr-reader" style="display: none; position: absolute; top: 5px; right: 50px;"></div>
                  </div>
                        <div id="alert_camera" class="text-center"></div>
                </div>
                </div>
              </div><!-- End Info Item -->
            </div>
          </div><!-- End caméra scanner -->

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
<script src="../assets/js/main-form.js"></script>