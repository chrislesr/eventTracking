<?php
    include "header.php";
    include 'Authentification.php';

    $data_Comite = infolastComite($conn,$_SESSION['id_user']);
    $data = lsEventUsersId($_SESSION['id_user']);
    $id_evenement = $data['id_env'];

?>

<?php pagetitleMain('form Invitation','Formulaire d\'insertion d\'une Invitation ou billet')?>

    <!-- Team Section -->
    <section id="team" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
           <div class="row justify-content-between align-items-center">
                <div class="col-lg-6">
                    <p>Formulaire Invitation</p>
                </div>
                <div class="col-lg-4 text-end">
                    <?php btnLsInvitation()?>
                </div>
            </div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
         <div class="col-lg-6">
            <form id="myForm_add" class="form_add" data-aos="fade-up" data-aos-delay="500">
            <div class="row-btn-scan">
                    
                <?php btnScanningRFID();?>
                <?php 
                    btnScanningQR();
                 ?>
            </div>
              <div class="row gy-4">
                <div class="col-md-12">
                    <p>Please Tag your Card / Key Chain to display ID</p>
                    <p id="scanning">Scanning ....(met champs en hidden)</p>
                    <textarea name="code_value_id" id="search_text_rfid" class="form-control" placeholder="Please Tag your Card / Key Chain to display ID" rows="1" cols="1" autocomplete="off" ></textarea>
                </div>

                <div class="col-md-12" >
                    <input  type="text" name="code_value_qr" id="search_text" class="form-control" placeholder="Scanner qr">
                    
               
                </div>

                <div class="col-md-12 text-center">
                    <input type="hidden" name="control" id="control" value="int_affect_invt">
                    <input type="hidden" name="token" id="token" value="<?=$token?>">
                    <input type="hidden" name="verif" id="verif" value="affectationCode">
                    <input type="hidden" name="action" value="insert">
                    <div id="message"></div> 
                  <button type="submit" id="add_btn">Valider</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->
            
          <div id="lastClientInfo" class="col-lg-6 ">
            <div class="row gy-4">
                <div id="qrcodeLect"  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-md-12">
                        <div class="qr-code">
                            <div id="qr-reader" style="display: none; position: absolute; top: 5px; right: 50px;"></div>
                        </div>
                        <div id="alert_camera" class="text-center"></div>
                    </div>
                </div>
              </div><!-- End Info Item -->
              <div class="col-lg-12">
                <div id="rfidLect" class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <img id="lastImage" width="30%">
                  <h3>Code ID invitation&nbsp;</h3>
                  <p id="lastID"></p>
                </div>
            </div>
            <style>
                .qr-code{
                    position: relative;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    left: 50px;
                }
            </style>
            

            </div>
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
<script src="../assets/js/main-form.js"></script>
<script>
    
</script>