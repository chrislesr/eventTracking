<?php
	include "header.php";

  if($_SESSION['niveau']=='LEVEL_NIV4'){
    $id_evenement = htmlspecialchars($_SESSION['usersInfo']['id_env']);
    $nbreInvitation = nbrInvitation($id_evenement,$conn);
    $nbrParticipant = nbrParticipant($id_evenement,$conn);
    $nbrParticipee = nbrParticipee($id_evenement,$conn);
    $nbrComitard = nbrComitard($id_evenement,$conn);
  }else if($_SESSION['niveau']=='LEVEL_NIV1'){
    $id_evenement = htmlspecialchars($_SESSION['usersInfo']['id_event_cm_fk']);
     $id = htmlspecialchars($_SESSION['id_user']);
    $nbreInvitation = nbrInvitation($id_evenement,$conn);
    $nbrParticipant = nbrParticipantId($id,$conn);
    $nbrParticipee = nbrParticipee($id,$conn);
     $nbrComitard = nbrComitard($id_evenement,$conn);
  }

?>
<main class="main">
   

    <!-- Stats Section -->

    <section id="stats" class="stats section dark-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
		<br>
        <div class="row gy-4">

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-emoji-smile color-blue flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?=$nbreInvitation?>" data-purecounter-duration="1" class="purecounter"></span>
                <p><a href="#" class="text-white">Invitations</a></p>
            </div>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-journal-richtext color-orange flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?=$nbrParticipant?>" data-purecounter-duration="1" class="purecounter"></span>
                <p><a href="#" class="text-white">Participants</a></p>
              </div>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-headset color-green flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?=$nbrParticipee?>" data-purecounter-duration="1" class="purecounter"></span>
                <p><a href="#" class="text-white">Participés</a></p>
              </div>
            </div>
          </div><!-- End Stats Item -->
          
          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-people color-pink flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?=$nbrComitard?>" data-purecounter-duration="1" class="purecounter"></span>
                <p><a href="#" class="text-white">Comité</a></p>
              </div>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Projets future<br></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <div class="img">
                <img src="../assets/img/services-1.jpg" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-activity"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Tracking event</h3>
                </a>
                <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="service-item">
              <div class="img">
                <img src="../assets/img/services-2.jpg" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-broadcast"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Tracking car</h3>
                </a>
                <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="service-item">
              <div class="img">
                <img src="../assets/img/services-3.jpg" class="img-fluid" alt="">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-easel"></i>
                </div>
                <a href="service-details.html" class="stretched-link">
                  <h3>Tracking commodity</h3>
                </a>
                <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Team Section -->
    <section id="team" class="team section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Comité</h2>
      <p>Membres du comité</p>

      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">
            <?php 
        $data = lsAllcomiteLimit($id_evenement,$conn);
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
                  <?php btnEditeCmt($aff['id_inviter'])?>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->
            <?php }?>
          
        </div>

      </div>
    </section><!-- /Team Section -->
  </main>
  <!-- start footer and the end body -->
  <?php 
  include 'footer.php';
?>