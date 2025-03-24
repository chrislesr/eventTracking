<?php
	include "header.php";
  include 'Authentification.php';
	
?>
<main class="main">
   
  <!-- Page Title -->
  
  <?php pagetitleMain('Membres du comite','Formulaire d\'insertion d\'une Invitation ou billet')?>
   <!-- End Page Title -->

    <!-- Team Section -->
    <section id="team" class="team section ">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <div class="row justify-content-between align-items-center">
      	   <div class="col-lg-6">
      	     <p>Liste des membres du comité</p>
            </div>
            <div class="col-lg-4 text-end">
      	       <?php btnAddComite()?>
            </div>
        </div>
      </div><!-- End Section Title -->

      <div class="container">
        <div lang="row m-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-deplay="100">
            <input type="text" id="emailFilter" class="form-control" placeholder="Filtrer par email">
            <br><br>
          </div>
        </div>
        <div class="row gy-5">
        <?php 
        $data = lsAllcomite($_SESSION['id_user']);
       	foreach ($data as $aff) { 
          if(empty($aff['image_inviter'])){
            if($aff['sexe_inviter'] == 'femme')
            {
              $imageSource = '../assets/img/photoProfil/default_girls.jpg';
            }else{
              $imageSource = '../assets/img/photoProfil/default_man.jpg';
            }
          }else{
            $imageSource = '../assets/img/photoProfil/'.$aff['image_inviter'];
          }
         
          ?>
       		<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member">

              <div class="pic"><img src="<?=$imageSource?>" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4><?=$aff['email_inviter']?></h4>
                <?php 
                if($aff['nom_evenement'] == 'fete'){
                	if($aff['poste_inviter'] == 'vente'){
                		echo "<span>Poste : Distribution</span>";
                	}elseif ($aff['poste_inviter'] == 'porte') {
                		echo "<span>Poste : Entrée</span>";
                	}
                }else{
                	if($aff['poste_inviter'] == 'vente'){
                		echo "<span>Poste : Vente</span>";
                	}elseif ($aff['poste_inviter'] == 'porte') {
                		echo "<span>Poste : Entrée</span>";
                	}
                }
                ?>
                <div class="social">
                  <a href="form-modification?view=comite&verif=<?=$aff['id_inviter']?>'"><i class="bi bi-pencil" title="modification"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->
       	<?php } ?>
          
        </div>

      </div>
    </section><!-- /Team Section -->
  </main>
  <!-- start footer and the end body -->
  <?php 
  include 'footer.php';
?>
<script>
  $(document).ready(function() {
    $("#emailFilter").on("keyup", function() {
        var value = $(this).val().toLowerCase(); // Récupérer la valeur de l'entrée et la mettre en minuscules
        $(".member").filter(function() {
            $(this).toggle($(this).find("h4").text().toLowerCase().indexOf(value) > -1);
        });
    });
});

</script>