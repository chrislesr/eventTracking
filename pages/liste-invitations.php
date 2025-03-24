<?php
  include "header.php";
  include 'Authentification.php';
  $data = lsEventUsersId($_SESSION['id_user']);
  $id_evenement = $data['id_env'];
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <main class="main">
    <!-- Page Title -->
  <?php include 'title-main.php';?>
   <!-- End Page Title -->
<style>
  .student-avathar {
  border: 1px solid var(--gray);
  border-radius: 50px;
  height: 3rem;
  width: 3rem;
}


/*.wrapper {
  background-color: #fdfdfd;
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.16);
}
.custom-dropdown-toggle {
  border: none !important;
  background: none !important;
}
.cursor-pointer{
  cursor: pointer;
}*/
</style>
    <!-- Starter Section Section -->
    <section id="services" class="starter-section section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <div class="row justify-content-between align-items-center">
      	   <div class="col-lg-6">
      	     <p>Liste des invitations</p>
            </div>
            <div class="col-lg-4 text-end">
      	       <?php btnAddInvitation()?>
            </div>
        </div>
      </div><!-- End Section Title -->
      <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
          <div class="col-md-6">
            <div class="qr-code">
              <div id="qr-reader" style="display: none; position: absolute; top: 5px; right: 50px;"></div>
            </div>
            <div id="alert_camera"></div>
          </div>
        </div>
      </div>

      <div class="container" data-aos="fade-up">
        
        <div class="col-lg-6">
            <input type="text" id="search_text_rfid" class="form-control"  placeholder="Rechercher..."><br>
            <input type="hidden" id="control" value="invitation">
            <?php btnScanningRFID();?>
            <?php btnScanningQR();
            ?>
            
        </div>
        <div class="table-responsive mt-4 p-4 wrapper rounded-3">
          <table id="myTables" class="table table-scrollable" width="100%">
            <thead class="bg-light text-center">
              <tr class="align-middle">
                <th>Photo</th>
                <th>Evénement</th>
                <th>Code 1</th>
                <th>Code 2</th>
                <th>Suppression</th>
              </tr>
            </thead>
            <tbody class="table-hover text-center">
              <!-- <tr class="align-middle">
                <td>1</td>
                <td>
                  <img src="adbz.png" alt="adbz" class="student-avathar" />
                </td>
                <td>STDID023</td>
                <td>Adbz</td>
                <td>95</td>
                <td><span class="passed">Passed</span></td>
              </tr> -->
              
            </tbody>
          </table>
          <div id="noResultsRow" style="display:none;" class="no-results mt-3">Aucun élément trouvé</div>
          <div id="pagination" class="d-flex justify-content-center align-items-center mt-3">
            <i class="bi bi-chevron-left bi-lg cursor-pointer" onclick="previousPage()"></i>
            <span id="pageNumber"></span>
            <i class="bi bi-chevron-right cursor-pointer" onclick="nextPage()"></i>
          </div>
        </div>
      </div>
      
      

    </section><!-- /Starter Section Section -->

  </main>
<script src="../assets/js/main-list.js"></script>
  <?php 
  include 'footer.php';
?>
<script>
  $(document).ready(function() {
    var isScanning = false;
        var html5QrCode;

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
                    $('#search_text_rfid').val(qrCodeMessage);
                    $('#qr-reader').hide();
                    html5QrCode.stop();
                    isScanning = false;
                    $('#search_text_rfid').trigger('input');
                    $('#alert_camera').hide().removeClass('success').removeClass('danger'); 
                },
                errorMessage => {
                    showPopup1("Le QR Code n'est plus devant la caméra.", 'danger');
                }
            ).catch(err => {
                showPopup1(`Impossible de démarrer la numérisation, error: ${err}`, 'danger');
            });
            isScanning = true;
        } else {
            html5QrCode.stop().then(() => {
                $('#qr-reader').hide();
                isScanning = false;
            }).catch(err => {
                showPopup1(`Impossible d'arrêter la numérisation, error: ${err}`, 'danger');
            });
        }
    });
      function showPopup1(message, type) {
        $('#alert_camera').text(message).removeClass('success').addClass(type).show();
    }

    $('#search_text_rfid').on('input', function() {
        var searchQuery = $(this).val();
        fetchData(1, searchQuery);
    });

  });
</script>