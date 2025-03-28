<?php
  include "header.php";
  $data = lsEventUsersId($_SESSION['id_user']);
  if (!empty($data)) {
  	$id_evenement = $data['id_env'];
  	$result = lsParticipants($id_evenement);
  }else{
  	$result = lsParticipants($_SESSION['id_user']);
  }

?>

  <main class="main">
    <!-- Page Title -->
  <?php include 'title-main.php';?>
   <!-- End Page Title -->

    <!-- Starter Section Section -->
    <section id="services" class="starter-section section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <div class="row justify-content-between align-items-center">
      	   <div class="col-lg-6">
      	     <p>Liste des participants</p>
            </div>
            <div class="col-lg-4 text-end">
      	       <?php btnAddParticipant()?>
            </div>
        </div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up">

        <div class="col-lg-6">
            <input type="text" id="search_text_rfid" class="form-control"  placeholder="Rechercher..."><br>
            <input type="hidden" id="control" value="participant">
        </div>
        <div class="table-responsive mt-4 p-4 wrapper rounded-3">
        <table id="myTables" class="table table-scrollable" width="100%">
            <thead class="bg-light text-center">
              <tr class="align-middle">
              <th>Image</th>
        			<th>Nom</th>
              <th>Prénom</th>
        			<th>Sexe</th>
              <th>N° Tél.</th>
              <th>Place reservée</th>
              <th>Date d'énregistrement</th>
              <?php 
              if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
              echo '<th>Modifier</th>';
              echo '<th>Supprimer</th>';
            }?>
        		</tr>
        	</thead>
        	<tbody>
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