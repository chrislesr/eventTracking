<?php
	if(!checkRole(['LEVEL_1','LEVEL_NIV4'])){
   	$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }
  if (isset($_SESSION['poste']) && $_SESSION['poste'] == 'porte') {
    $previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
    header("Location: " . $previous_url);
    exit;
  }
?>