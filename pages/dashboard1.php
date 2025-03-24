<?php
	error_reporting(E_ALL);
	session_start();
	include '../functions.php';
	
	if(!isset($_SESSION['id_user']) && !checkRole(['LEVEL_1','LEVEL_NIV4'])){
		header('Location:../index');
		exit;
	}
?>
Bienvenue<br>
<a href="comite">Ajouter comite</a><br><br>
<a href="add_invitation">Ajouter Invitation</a><br><br>
<a href="affect_qr_rfid">Affectation code QR/RFID</a><br><br>
<a href="ls_evenement">Liste des Evenement</a><br><br>

<a href="participant">Ajouter participant</a><br><br>
<a href="../deconnexion.php">Deconnection</a>