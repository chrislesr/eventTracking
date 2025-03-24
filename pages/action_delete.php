<?php
session_start();
include 'connexion.php';
include '../functions.php';
$conn = Connexion::connect();

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' AND isset($_GET['view']) AND !empty($_GET['verif'])) {

	$control = inputtest($_GET['view']);
	$id_delete = inputtest($_GET['verif']);
	
	header('Content-Type: application/json');
    // Requête pour vérifier le hachage et supprimer en utilisant PDO
    /*
    $stmt = $pdo->prepare("SELECT id, hashed_id FROM votre_table WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($id, $row['hashed_id'])) {
        $stmt = $pdo->prepare("DELETE FROM votre_table WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Renvoyer une réponse JSON indiquant le succès
        echo json_encode(['success' => true, 'message' => 'Enregistrement supprimé avec succès.']);
    } else {
        // Renvoyer une réponse JSON indiquant l'échec
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    }
    */
    if($control =='invitation'){
    	$sql = "UPDATE affect_invitation_event SET control_deleted = 'deleted' WHERE id_aff = :id_delete";
    	$query = $conn->prepare($sql);
    	$query->bindParam(':id_delete',$id_delete,PDO::PARAM_INT);
    	$result = $query->execute();

    	if($result && $result != ""){
    		echo json_encode(['success' => true, 'message' => 'Enregistrement supprimé avec succès.']);
    	}else{
    		echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    	}

    }else if($control =='participant'){
    	$sql = "UPDATE participants SET control_deleted = 'deleted' WHERE id_part = :id_delete";
    	$query = $conn->prepare($sql);
    	$query->bindParam(':id_delete',$id_delete,PDO::PARAM_INT);
    	$result = $query->execute();

    	if($result && $result != ""){
    		echo json_encode(['success' => true, 'message' => 'Enregistrement supprimé avec succès.']);
    	}else{
    		echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    	}

    }else if($control =='categorie'){
        $sql = "UPDATE categorie_inviter SET controle_deleted_cat = 'deleted' WHERE id_cat_inv = :id_delete";
        $query = $conn->prepare($sql);
        $query->bindParam(':id_delete',$id_delete,PDO::PARAM_INT);
        $result = $query->execute();

        if($result && $result != ""){
            echo json_encode(['success' => true, 'message' => 'Enregistrement supprimé avec succès.']);
        }else{
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
    }
    else{
    	echo json_encode(['success' => false, 'message' => 'Erreur lors .']);
    }

    exit; // Important: arrêter l'exécution du script ici
}

?>
