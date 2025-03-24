<?php 
	session_start();
	include 'connexion.php';

	if($_SERVER['REQUEST_METHOD'] == 'POST' AND !empty($_POST['email']) AND !empty($_POST['password']))
	{
			$conn = Connexion::connect();
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			$email = htmlspecialchars(trim($_POST['email']));
			$password = htmlspecialchars(trim($_POST['password']));
			
			$sql = "SELECT id_utl,email_utl,password_utl FROM utilisateurs WHERE email_utl = :email AND control_deleted = 'no_deleted' AND is_actif = 'active'";
			$query = $conn->prepare($sql);
			$query->bindParam(':email',$email,PDO::PARAM_STR);
			$query->execute();
			$res = $query->rowCount();
			
			if($res > 0){
				$result = $query->fetch(PDO::FETCH_ASSOC);

			if($result && password_verify($password, $result['password_utl'])){
				$sql = "SELECT * FROM utilisateurs u JOIN niveaux n ON u.id_niveau_fk = n.id_niv INNER JOIN evenements e ON e.id_util_org = u.id_utl WHERE u.id_utl = :id AND e.control_deleted = 'no_deleted' AND e.is_actif = 'active'";
				$query = $conn->prepare($sql);
				$query->bindParam(':id',$result['id_utl']);
				$query->execute();
				$users = $query->fetch();
				if(empty($users)){
					$sql = "SELECT * FROM utilisateurs u JOIN niveaux n ON u.id_niveau_fk = n.id_niv WHERE u.id_utl = :id";
					$query = $conn->prepare($sql);
					$query->bindParam(':id',$result['id_utl']);
					$query->execute();
					$users = $query->fetch();
				}
				
				//==========================
				$sql1 = "SELECT n.sigle_niv FROM utilisateurs u JOIN niveaux n ON u.id_niveau_fk = n.id_niv WHERE u.id_utl = :id_";
				$query1 = $conn->prepare($sql1);
				$query1->bindParam(':id_',$result['id_utl']);
				$query1->execute();
				$name_niv = $query1->fetchColumn();

				$sigle = $users['sigle_niv'];
				$id_user = $users['id_utl'];


				if($users['sigle_niv'] == 'LEVEL_0'){
					$_SESSION['id_user'] = $id_user;
					$_SESSION['niveau'] = $name_niv;
					$_SESSION['usersInfo'] = $users;
					header('Location:../administract/dashboard');
					exit;
				}elseif ($sigle == 'LEVEL_1' OR $sigle == 'LEVEL_NIV4') {
					$_SESSION['id_user'] = $id_user;
					$_SESSION['niveau'] = $name_niv;
					$_SESSION['usersInfo'] = $users;
					header('Location:dashboard');
					exit;
				}else{
					$_SESSION['message'] = 'Vous n\'etes pas autorisé';
					header('Location:../index.php');
					exit;
				}
		}else{
			$_SESSION['message'] = 'Le nom et le mot de passe ne correspond pas !';
			header('Location:../index.php');
		}
	}else{
		$sql = "SELECT id_inviter,email_inviter,password_inviter FROM comite_organisateur WHERE email_inviter = :email AND control_deleted = 'no_deleted' AND is_actif_inviter = 'active'";
			$query = $conn->prepare($sql);
			$query->bindParam(':email',$email,PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			$id_invit = $result['id_inviter'];
		if($result && password_verify($password, $result['password_inviter'])){
				$sql = "SELECT * FROM comite_organisateur c JOIN niveaux n ON c.id_niveau_inv_fk = n.id_niv WHERE c.id_inviter = :id";
				$query = $conn->prepare($sql);
				$query->bindParam(':id',$id_invit);
				$query->execute();
				$users = $query->fetch();

				//==========================
				$sql1 = "SELECT n.sigle_niv FROM comite_organisateur c JOIN niveaux n ON c.id_niveau_inv_fk = n.id_niv WHERE c.id_inviter = :id_";
				$query1 = $conn->prepare($sql1);
				$query1->bindParam(':id_',$id_invit);
				$query1->execute();
				$name_niv = $query1->fetchColumn();

				$sigle = $users['sigle_niv'];
				$id_user = $users['id_inviter'];
				$poste = $users['poste_inviter'];

				if ($sigle == 'LEVEL_NIV1') {
					$_SESSION['id_user'] = $id_user;
					$_SESSION['niveau'] = $name_niv;
					if($poste == 'vente'){
						$_SESSION['poste'] = $poste;
						$_SESSION['usersInfo'] = $users;
						header('Location:form-participant');
						exit;
					}elseif ($poste == 'porte') {
						$_SESSION['poste'] = $poste;
						$_SESSION['usersInfo'] = $users;
						header('Location:../scanner/index');
						exit;
					}
					
				}else{
					$_SESSION['message'] = 'Vous n\'etes pas autorisé';
					header('Location:../index.php');
					exit;
				}
		}else{
			$_SESSION['message'] = 'Le nom et le mot de passe ne correspond pas !';
			header('Location:../index.php');
		}
	}
		
	}else{
		$_SESSION['message'] = 'Veuillez remplir tous les champs !';
		header('Location:../index');
	}
?>