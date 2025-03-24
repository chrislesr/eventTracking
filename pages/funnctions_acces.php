<?php
	function btnAddComite(){
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="form-comite" class="btn btn-primary">Ajouter comité </a>';
	}
	}

	function btnAddInvitation(){
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="form-invitation" class="btn btn-primary">Ajouter une Invitation </a>';
	}
	}

	function btnLsInvitation()
	{
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="liste-invitations" class="btn btn-primary">Liste des invitation </a>';
		}
	}

	function btnLsComite()
	{
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="comites" class="btn btn-primary">Liste comitards </a>';
		}
	
	}

	function btnAddParticipant(){
		if(checkRole(['LEVEL_1','LEVEL_NIV4','LEVEL_NIV1'])){
		echo '<a href="form-participant" class="btn btn-primary">Ajouter un participant </a>';
	}
	}

	function btnLsParticipant()
	{
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="liste-participants" class="btn btn-primary">Liste participants </a>';
	}
	}

	function btnLsPlace()
	{
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="liste-place" class="btn btn-primary">Liste place </a>';
		}
	
	}

	function btnAddPlace(){
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="form-place" class="btn btn-primary">Ajouter une place </a>';
		}
	}

	function btnEditeCmt($id){
		if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
		echo '<a href="form-modification?view=comite&verif='.$id.'"><i class="bi bi-pencil" title="modification"></i></a>';
		}
	}

	function pagetitleMain($page,$texte)
	{
		echo '<main class="main">
			   <!-- Page Title -->
			    <div class="page-title dark-background" data-aos="fade" style="background-image: url(../assets/img/page-title-bg.webp);">
			      <div class="container position-relative">
			        <h1>'.$page.'</h1>
			        <p>'.$texte.'</p>
			        <nav class="breadcrumbs">
			          <ol>
			            <li><a href="dashboard">Accueil</a></li>
			            <li class="current">'.$page.'</li>
			          </ol>
			        </nav>
			      </div>
			    </div><!-- End Page Title -->';
	}

	function btnScanningRFID()
	{
		echo '<button id="btnSCAnning" type="button" class="btn btn-outline-secondary">scanning RFID</button>';
		
	}

	function btnScanningQR()
	{
		echo '<button id="btn-search-qr" type="button" class="btn btn-outline-success">scanning Code QR</button>';
	}
?>