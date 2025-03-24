<?php
	include "header.php";

	if(!checkRole(['LEVEL_1','LEVEL_NIV4'])){
   	$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }
	$data_Comite = infolastComite($conn,$_SESSION['id_user']);
	$data = lsEventUsersId($_SESSION['id_user']);
    $id_evenement = $data['id_env'];

?>
<?php include 'title-main.php';?>

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
            <form action="action_register.php" method="post" class="form_add" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">
              	<div class="d-flex justify-content-center">
		            <button class="btn btn-primary me-4 btn-lg" id="btnRfid">FORM RFID</button>
		            <button class="btn btn-secondary" id="btnQrcode">FORM QR Code</button>
    			</div>
                <div id="rfidForm" class="col-md-12">

                	<select class="form-control" name="code_value_id">
                    <option value="">Scanner rfid</option>
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

                <div id="qrcodeForm" class="col-md-12 d-none" >
                	<input  type="text" name="code_value_qr" id="search_text" class="form-control" placeholder="Scanner qr">
                	
                <p id="btn-search-qr" style="cursor:pointer;">Start camera</p>
                </div>

                <div class="col-md-12 text-center">
                	<input type="hidden" name="event" value="<?=$id_evenement?>">
                	<input type="hidden" name="control" id="control" value="int_af_int_evt">
                	<input type="hidden" name="token" id="token" value="<?=$token?>">
                    <input type="hidden" name="verif" id="verif" value="invitation">
                    <input type="hidden" name="action" value="insert">
                    <div id="message"></div> 
                  <button type="submit" id="add_btn">Valider</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->
        	
          <div id="lastClientInfo" class="col-lg-6 ">
            <div class="row gy-4">

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
            <div id="qrcodeLect"  class="col-lg-12 d-none">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                	<div class="col-md-12">
		                <div class="qr-code">
		                	<div id="qr-reader" style="display: none; position: absolute; top: 5px; right: 50px;"></div>
            			</div>
            		</div>
                </div>
              </div><!-- End Info Item -->

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

<script>
    const rfidForm = document.getElementById('rfidForm');
    const rfidLect = document.getElementById('rfidLect');
    const qrcodeForm = document.getElementById('qrcodeForm');
    const qrcodeLect = document.getElementById('qrcodeLect');
    const btnRfid = document.getElementById('btnRfid');
    const btnQrcode = document.getElementById('btnQrcode');
    btnRfid.addEventListener('click', (e) => {
    	e.preventDefault();
        
        rfidForm.classList.remove('d-none');
        rfidLect.classList.remove('d-none');
        qrcodeForm.classList.add('d-none');
        qrcodeLect.classList.add('d-none');
        btnRfid.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Login
        btnQrcode.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Register
    });

    btnQrcode.addEventListener('click', (e) => {
    	e.preventDefault();
        rfidForm.classList.add('d-none');
        rfidLect.classList.add('d-none');
        qrcodeForm.classList.remove('d-none');
        qrcodeLect.classList.remove('d-none');
        btnQrcode.classList.replace('btn-secondary', 'btn-primary'); // Change couleur bouton Register
        btnRfid.classList.replace('btn-primary', 'btn-secondary'); // Remet la couleur par défaut du bouton Login
    });

    $('#btnRfid').on('click', function() {
    	$('#search_text').val('');

	});

	$('#btnQrcode').on('click', function() {
    	$('#search_text').val('');

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
<!-- <script src="../assets/js/main-form.js"></script> -->