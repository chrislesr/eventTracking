<?php 
	session_start();
	include 'connexion.php';
	include '../functions.php';

$conn = Connexion::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST['control']) AND isset($_SESSION['token_csrf']) AND $_SESSION['token_csrf'] == $_POST['token'])
	{
		$control = inputtest($_POST['control']);
        $id_utilateur = $_SESSION['id_user'];

        if ($control == 'int_af_int_evt') {
        	if(!empty($_POST['code_value_id'])){
        		$id_invit = inputtest($_POST['code_value_id']);
        		$checked_id = checkAllData('invitations','code_invitation',$id_invit);
        	}elseif (!empty($_POST['code_value_qr'])) {
        		$id_invit = inputtest($_POST['code_value_qr']);
        		$checked_id = checkAllData('invitations','code_qr_invitation',$id_invit);
        	}elseif (!empty($_POST['code_value']) AND !empty($_POST['code_value'])) {
        		$_SESSION['message'] = "Ajouter d'abord le Qr code ou la carte";
        		$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
			    header("Location: " . $previous_url);
			    exit;
        	}else{
        		$_SESSION['message'] = "Veillez remplir les champs obligatoire !";
        		$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
			    header("Location: " . $previous_url);
			    exit;
        	}
        	
        	$id_event = inputtest($_POST['event']);

        	if(!empty($id_invit) AND !empty($id_event))
        	{

        		$checked = checkAffectCode($id_invit,$id_event);
        		if($checked >=1){
        			$_SESSION['message'] = 'Existe !';
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:form-invitation');
                    exit;
        		}else
        		{
        			if (!empty($id_invit)) {
        				if($checked_id == 0){
        					$_SESSION['message'] = 'Erreur du code !';
		                    $_SESSION["message_type"] = "error";
		                    unset($_SESSION['token_csrf']);
		                    header('location:form-invitation');
		                    exit;
        				}
        			}
	        		$sql = ("INSERT INTO `affect_invitation_event`(`affect_invit_id`, `affect_event_id`, `affect_util_id`) VALUES (:id_inv,:id_even,:id_ut)");
		            $query = $conn->prepare($sql);
		            $query->bindParam(':id_inv', $id_invit,PDO::PARAM_INT);
		            $query->bindParam(':id_even', $id_event,PDO::PARAM_INT);
		            $query->bindParam(':id_ut', $id_utilateur,PDO::PARAM_INT);
		            $result = $query->execute();

		            if($result AND !empty( $result))
		            {
		                $_SESSION['message'] = "Affectation réussie !";
		                $_SESSION["message_type"] = "success";
		                unset($_SESSION['token_csrf']);
		                header('Location: form-invitation');
		                exit();
	            	}else{
		                $_SESSION["message"] = "Échec de la validation!"; 
		                $_SESSION["message_type"] = "error";
		                unset($_SESSION['token_csrf']);
		                header('Location: form-invitation');
		                exit();
	            		}
        		}
        	}else{
        		$_SESSION["message"] = "Veillez remplir tous les champs "; 
	            $_SESSION["message_type"] = "error";
	            unset($_SESSION['token_csrf']);
	            header('Location: affect_invitation');
	            exit();
        	}
        }
	}
?>