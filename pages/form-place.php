<?php
	include "header.php";

	if(!checkRole(['LEVEL_1','LEVEL_NIV4'])){
   	$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }
	$data_Comite = infolastComite($conn,$_SESSION['id_user']);

?>
<?php pagetitleMain('form comité','Formulaire d\'insertion d\'une place')?>

    <!-- Team Section -->
    <section id="team" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
	       <div class="row justify-content-between align-items-center">
		      	<div class="col-lg-6">
		      		<p>Formulaire place reservée</p>
		       	</div>
		      	<div class="col-lg-4 text-end">
		      		<?php btnLsPlace()?>
		      	</div>
	      	</div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

        	<div class="col-lg-6">

              <form id="myForm_add" class="form_add" data-aos="fade-up" data-aos-delay="500" enctype="multipart/form-data">
              <div class="row gy-4">

                <div class="col-md-12">
                  <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Nom de la place" required="">
                </div>

                <div class="col-md-12">
                  <input type="hidden" name="control" id="control" value="int_categorie">
                  <input type="hidden" name="verif" id="verif" value="categorie">
		              <input type="hidden" name="token" id="token" value="<?=$token?>">
                  <input type="hidden" name="action" value="insert">
                </div>

                <div class="col-md-12 text-center">
                   <div id="message"></div> 
                  <button type="submit" id="add_btn">Ajouter place</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->
          <div  id="lastClientInfo" class="col-lg-6 ">
            <div class="row gy-4">

              <div  class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">

                  <img id="lastImage" width="30%">

                  <h3>Place reservée&nbsp;</h3>
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
    $(document).ready(function() {
      const nameTable = document.getElementById('verif').value;
      const control = document.getElementById('control').value;
      const token = document.getElementById('token').value;

    // Récupération du dernier client au chargement de la page
    $.ajax({
        url: 'action_register.php',
        type: 'POST',
        data: { tableName: nameTable, action: 'get_last', control: control, token: token },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.success && response.lastItem) {
                displayLastClientInfo(response.lastItem);
            } else {
                $('#lastClientInfo').html("<p>Aucun client trouvé.</p>");
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération du dernier ;::", error);
        }
    });

// Fonction pour afficher les infos du client (inchangée)
    
    function displayLastClientInfo(client) {
        if(nameTable === 'comite' || nameTable === 'categorie'){
        $('#lastName').text(client.name || client.nom_inviter || client.nom_categorie);
        $('#lastEmail').text(client.email || client.email_inviter || 'Non spécifié');
        $('#lastPhone').text(client.phone || client.tel_inviter || 'Non spécifié');
         $('#lastImage').attr('src', client.image_inviter ? '../assets/img/photoProfil/' + client.image_inviter : '../assets/img/photoProfil/default.jpg');
        $('#lastClientInfo').removeClass('d-none');
    }
}

    $('#myForm_add').submit(function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'action_register.php',
            type: 'POST',
            data:  formData, // Inclure les données du formulaire
            contentType: false, 
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showPopup(response.message, 'success');
                    $('#myForm_add')[0].reset();
                    // Raffraîchir l'affichage du dernier client
                    $.ajax({
                        url: 'action_register.php',
                        type: 'POST',
                        data: { tableName: nameTable, action: 'get_last', control: control, token: token },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.lastItem) {
                                displayLastClientInfo(response.lastItem);
                            } else {
                                $('#lastClientInfo').html("<p>Aucun client trouvé.</p>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur lors de la récupération du dernier ;::", error);
                        }
                    });
                    
                } else {
                    showPopup(response.message, 'danger');
                }
            },
            error: function() {
                showPopup("Erreur lors de la requête AJAX.", 'danger');
            }
        });
    });

    

  function showPopup(message, type) { 
    $('#popup').text(message).addClass(type).show(); 
    setTimeout(function() { 
    $('#popup').hide().removeClass('success').removeClass('danger'); 
  }, 5000); 
} 

});   
</script>