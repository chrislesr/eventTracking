<?php
session_start();
include 'connexion.php';
include '../functions.php';
   
    $conn = Connexion::connect();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['control']) AND isset($_SESSION['token_csrf']) AND $_SESSION['token_csrf'] == $_POST['token']) {
    	$control = inputtest($_POST['control']);
        $id_utilisateur = $_SESSION['id_user'];
        $id_modif = inputtest($_POST['id']);

        if($control == 'edit_participant'){
        	$name_part = inputtest($_POST['name_part']);
            $email_part = inputtest($_POST['email_part']);
            $sexe = inputtest($_POST['sexe']);
            $id_categorie = inputtest($_POST['categorie']);
            $telNumber = inputtest($_POST['tel']);

            $aff = getParticepant($id_modif,$conn);
            $lastImg = $aff['image_part'];
            $cheminImg ='../assets/img/photoAffiche/' . $lastImg;
            
            //traitement de l'image
            if (empty($_FILES['image']['name'])) {
                     $fileImgName = $lastImg;
                 }else
                {
                    $image_comp = $_FILES['image'];
                     $maxSize = 2 * 1024 * 1024;
                     $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                     $errorMessage = "";
                    switch ($image_comp['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errorMessage = "Le fichier est trop volumineux (limite par les paramètres du serveur).";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = "Le fichier est trop volumineux (limite par le formulaire).";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = "Le téléchargement du fichier est incomplet.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = "Répertoire temporaire manquant.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = "Erreur d'écriture du fichier.";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = "Extension du fichier interdite.";
                        break;
                    default:
                        $errorMessage = "Erreur inconnue.";
                    }
                    if($errorMessage){
                        $response = ['success' => false, 'message' => "Erreur de téléchargement : " . $errorMessage];
                    }
                    $imageType = exif_imagetype($_FILES['image']['tmp_name']);
                    if ($imageType === false) {
                            $response = ['success' => false, 'message' => "Le fichier n'est pas une image valide"];
                          }
                    $image_comp = imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name']));
                    if ($image_comp === false) {
                                $error = error_get_last();
                                die("Erreur lors de la création de l'image: " . $error['message'] . " in file: " . __FILE__ . " on line: " . __LINE__);
                    }

                    $newWidth = 400;
                    $newHeight = 500;
                    $imageResized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($imageResized, $image_comp, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image_comp), imagesy($image_comp));
                    imagedestroy($image_comp);
                    $image_comp = $imageResized;
                    $quality = 80;
                    $img_name = $_FILES['image']['name'];
                    $extension = pathinfo($img_name,PATHINFO_EXTENSION);
                    $fileImgName =uniqid().'.'.$extension;

                     if(file_exists($cheminImg)) {
                     	unlink($cheminImg);
                     		
                     }
                }//End image


                if(!empty($id_modif)){
                    $sql = "UPDATE `participants` SET `id_cat_inviter_fk` = :id_cat, `nom_part` = :name,`email_part`=:email,`sexe_part`=:sexe,`tel_part`=:tel,`id_ops_update_part`=:id_ops,`image_part`=:img WHERE `id_part` = :id";

                	$query = $conn->prepare($sql);
                    $query->bindParam(':id_cat',$id_categorie,PDO::PARAM_INT);
                    $query->bindParam(':name',$name_part,PDO::PARAM_STR);
                    $query->bindParam(':email',$email_part,PDO::PARAM_STR);
                    $query->bindParam(':sexe',$sexe,PDO::PARAM_STR);
                    $query->bindParam(':tel',$telNumber,PDO::PARAM_STR);
                    $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_INT);
                    $query->bindParam(':img',$fileImgName,PDO::PARAM_STR);
                    $query->bindParam(':id',$id_modif,PDO::PARAM_INT);

                    $result = $query->execute();

                    if($result && !empty($result)){
                        if (!empty($_FILES['image']['name'])){
                        $detinationImg = '../assets/img/photoAffiche/'.$fileImgName;
                        imagejpeg($image_comp, $detinationImg, $quality);
                        imagedestroy($image_comp);  
                                }

                             $_SESSION['message'] = 'Modifié avec succès';
                             $_SESSION['type-message'] = 'success';
                             header('location:form-modification?view=participant&verif='.$id_modif);
                             unset($_SESSION['token_csrf']);
                             exit;
                        }else{
                            $_SESSION['message'] = 'Echec de la modification';
                             $_SESSION['type-message'] = 'error';
                             header('location:form-modification?view=participant&verif='.$id_modif);
                             unset($_SESSION['token_csrf']);
                             exit;

                        }
                }else{
                			$_SESSION['message'] = 'Echec de la modification';
                             $_SESSION['type-message'] = 'error';
                             header('location:form-modification?view=participant&verif='.$id_modif);
                             unset($_SESSION['token_csrf']);
                             exit;
                }
        }//end edite participant
        else if($control == 'edite_comite'){
        	$com_name = inputtest($_POST['com_name']);
            $email = inputtest($_POST['com_email']);
            $sexe = inputtest($_POST['sexe']);
            $telNumber = inputtest($_POST['tel']);
            $id_level = inputtest($_POST['fonction']);
            $poste = inputtest($_POST['poste']);
            $aff = getComitard($id,$conn);
            $lastImg = $aff['image_inviter'];
            $cheminImg = '../assets/img/photoProfil/'.$lastImg;
            //traitement de l'image
                if (empty($_FILES['image']['name'])) {
                     $fileImgName = $lastImg;
                 }else{
                    $image_comp = $_FILES['image'];
                     $maxSize = 2 * 1024 * 1024;
                     $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                     $errorMessage = "";
                switch ($image_comp['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errorMessage = "Le fichier est trop volumineux (limite par les paramètres du serveur).";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = "Le fichier est trop volumineux (limite par le formulaire).";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = "Le téléchargement du fichier est incomplet.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = "Répertoire temporaire manquant.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = "Erreur d'écriture du fichier.";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = "Extension du fichier interdite.";
                        break;
                    default:
                        $errorMessage = "Erreur inconnue.";
                    }
                    if($errorMessage){
                        $response = ['success' => false, 'message' => "Erreur de téléchargement : " . $errorMessage];
                    }
                    $imageType = exif_imagetype($_FILES['image']['tmp_name']);
                    if ($imageType === false) {
                            $response = ['success' => false, 'message' => "Le fichier n'est pas une image valide"];
                          }
                    $image_comp = imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name']));
                    if ($image_comp === false) {
                                $error = error_get_last();
                                die("Erreur lors de la création de l'image: " . $error['message'] . " in file: " . __FILE__ . " on line: " . __LINE__);
                    }

                    $newWidth = 400;
                    $newHeight = 500;
                    $imageResized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($imageResized, $image_comp, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image_comp), imagesy($image_comp));
                    imagedestroy($image_comp);
                    $image_comp = $imageResized;
                    $quality = 80;
                    $img_name = $_FILES['image']['name'];
                    $extension = pathinfo($img_name,PATHINFO_EXTENSION);
                    $fileImgName ='PF'.uniqid().'.'.$extension;

                    if(file_exists($cheminImg)){
                    	unlink($cheminImg);
                     }
                    }

                    if (!empty($email)){
                    	$sql = "UPDATE `comite_organisateur` SET `id_niveau_inv_fk` = :id_level, `nom_inviter` = :name, `sexe_inviter` = :sexe, `tel_inviter` = :tel, `poste_inviter` = :poste, `image_inviter` = :image, `email_inviter` =:email,`id_ops_update_cmt` = :id_ops WHERE `id_inviter` = :id";
	                    $query = $conn->prepare($sql);
	                    
	                    $query->bindParam(':id_level', $id_level,PDO::PARAM_INT);
	                    $query->bindParam(':name', $com_name,PDO::PARAM_STR);
	                    $query->bindParam(':sexe', $sexe,PDO::PARAM_STR);
	                    $query->bindParam(':tel', $telNumber,PDO::PARAM_STR);
	                    $query->bindParam(':poste', $poste,PDO::PARAM_STR);
	                    $query->bindParam(':image', $fileImgName,PDO::PARAM_STR);
	                    $query->bindParam(':email', $email,PDO::PARAM_STR);
	                    $query->bindParam(':id_ops', $id_utilisateur,PDO::PARAM_INT);
	                    $query->bindParam(':id', $id_modif,PDO::PARAM_INT);

	                    $result = $query->execute();

		                if($result){
		                    if (!empty($_FILES['image']['name'])){
		                        $detinationImg = '../assets/img/photoProfil/'.$fileImgName;
		                        imagejpeg($image_comp, $detinationImg, $quality);
		                        imagedestroy($image_comp);  
		                    }
		                    $_SESSION['message'] = 'Modifié avec succès';
                             $_SESSION['type-message'] = 'success';
                             header('location:form-modification?view=comite&verif='.$id_modif);
                             unset($_SESSION['token_csrf']);
                             exit;
		                    
		                }else{
		                    $_SESSION['message'] = 'Echec de la modification';
                             $_SESSION['type-message'] = 'error';
                             header('location:form-modification?view=participant&verif='.$id_modif);
                             unset($_SESSION['token_csrf']);
                             exit;
		                    }
                    }else{
                    	$_SESSION['message'] = 'Echec de la modification';
                        $_SESSION['type-message'] = 'error';
                        header('location:form-modification?view=participant&verif='.$id_modif);
                        unset($_SESSION['token_csrf']);
                        exit;
                    }
        }//end edite comite
        elseif($control == 'edite_categorie'){
            $cat_name = inputtest($_POST['cat_name']);
            if(!empty($cat_name)){
                $sql = "UPDATE categorie_inviter SET nom_categorie = :cat_name WHERE id_cat_inv = :id";
                $query = $conn->prepare($sql);
                $query->bindParam(':cat_name',$cat_name,PDO::PARAM_STR);
                $query->bindParam(':id', $id_modif,PDO::PARAM_INT);
                $result = $query->execute();
                if($result){
                  $_SESSION["message"] = "Modifié avec succès !"; 
                    $_SESSION["message_type"] = "success";
                    header('location:form-modification?view=categorie&verif='.$id_modif);
                    exit();  
                }else{
                     $_SESSION["message"] = "Échec de la modification!"; 
                    $_SESSION["message_type"] = "error";
                    header('location:form-modification?view=categorie&verif='.$id_modif);
                    exit();
                }
            }else{
                $_SESSION["message"] = "Veillez entré le nom !"; 
                $_SESSION["message_type"] = "error";
                header('location:form-modification?view=categorie&verif='.$id_modif);
                exit();
            }

        }//end edite categorie or place
        else if($control == 'edit_profil'){
            $name = htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
            $sexe = htmlspecialchars($_POST['sexe'],ENT_QUOTES,'UTF-8');
            $phone = htmlspecialchars($_POST['phone'],ENT_QUOTES,'UTF-8');
            $sexe = htmlspecialchars($_POST['sexe'],ENT_QUOTES,'UTF-8');

            if(isset($_POST['last_name']) AND !empty($_POST['last_name'])){
                $last_name = htmlspecialchars($_POST['last_name'],ENT_QUOTES,'UTF-8');
            }
             if(isset($_POST['first_name']) AND !empty($_POST['first_name'])){
                $first_name = htmlspecialchars($_POST['first_name'],ENT_QUOTES,'UTF-8');
                $nationality = htmlspecialchars($_POST['nationality'],ENT_QUOTES,'UTF-8');
            }

            if($_SESSION['niveau'] == 'LEVEL_NIV4'){
                 $sql = "UPDATE `utilisateurs` SET nom_utl = :name,postnom_utl = :last,prenom_utl = :first,sexe_utl = :sexe,tel_utl = :phone,nationalite_utl = :nation,id_ops_update_user = :id_ops WHERE id_utl = :id";
                $query = $conn->prepare($sql);
                 $query->bindParam(':name',$name,PDO::PARAM_STR);
                $query->bindParam(':last',$last_name,PDO::PARAM_STR);
                $query->bindParam(':first',$first_name,PDO::PARAM_STR);
                $query->bindParam(':sexe',$sexe,PDO::PARAM_STR);
                $query->bindParam(':phone',$phone,PDO::PARAM_STR);
                $query->bindParam(':nation',$nationality,PDO::PARAM_STR);
                $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_INT);
                $query->bindParam(':id',$id_utilisateur,PDO::PARAM_INT);

                $result = $query->execute();
                if($result){
                    $_SESSION["message"] = "Modifié avec succès !"; 
                    $_SESSION["message_type"] = "success";
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();  
                }else{
                    $_SESSION["message"] = "Échec de la modification!"; 
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();
                }
            }
            else{
                $sql = "UPDATE `comite_organisateur` SET nom_inviter = :name,sexe_inviter = :sexe,tel_inviter = :phone,id_ops_update_cmt = :id_ops WHERE id_inviter = :id";
                $query = $conn->prepare($sql);
                
                $query->bindParam(':name',$name,PDO::PARAM_STR);
                $query->bindParam(':sexe',$sexe,PDO::PARAM_STR);
                $query->bindParam(':phone',$phone,PDO::PARAM_STR);
                $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_INT);
                $query->bindParam(':id',$id_utilisateur,PDO::PARAM_INT);
                $result = $query->execute();
                if($result){
                    $_SESSION["message"] = "Modifié avec succès !"; 
                    $_SESSION["message_type"] = "success";
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();  
                }else{
                    $_SESSION["message"] = "Échec de la modification!"; 
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();
                }
            }

        }//end edite profil
        else if($control == 'edit_password'){

            if(!empty($_POST['last_password']) AND !empty($_POST['new_password']) AND !empty($_POST['cfNew_password'])){

                $last_password = htmlspecialchars($_POST['last_password'],ENT_QUOTES,'UTF-8');
                $new_password = htmlspecialchars($_POST['new_password'],ENT_QUOTES,'UTF-8');
                $cfr_new_password = htmlspecialchars($_POST['cfNew_password'],ENT_QUOTES,'UTF-8');

                if($new_password == $cfr_new_password){
                     $infoUser = getPassword($id_utilisateur);
                     $passwordDb = $infoUser['password'];

                     if (password_verify($last_password, $passwordDb)) {
                         $passwordUpdate = password_hash($new_password, PASSWORD_DEFAULT);
                         if($_SESSION['niveau'] == 'LEVEL_NIV4'){
                            $sql = "UPDATE utilisateurs SET password_utl = :pass,id_ops_update_user = :id_ops WHERE id_utl = :id";
                        }else{
                            $sql = "UPDATE `comite_organisateur` SET password_inviter = :pass,id_ops_update_cmt = :id_ops WHERE id_inviter = :id";
                        }
                        $query = $conn->prepare($sql);
                        $query->bindParam(':pass',$passwordUpdate,PDO::PARAM_STR);
                        $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_INT);
                        $query->bindParam(':id',$id_utilisateur,PDO::PARAM_INT);
                        $result = $query->execute();
                        if($result){
                            $_SESSION["message"] = "Modifié avec succès !"; 
                            $_SESSION["message_type"] = "success";
                            unset($_SESSION['token_csrf']);
                            header('location:profil');
                            exit();  
                        }else{
                            $_SESSION["message"] = "Échec de la modification!"; 
                            $_SESSION["message_type"] = "error";
                            unset($_SESSION['token_csrf']);
                            header('location:profil');
                            exit();
                        }
                     }else{
                        $_SESSION["message"] = "Veillez saisir le vrai ancien mot de passe !"; 
                        $_SESSION["message_type"] = "error";
                        unset($_SESSION['token_csrf']);
                        header('location:profil');
                        exit();
                     }

                }else{
                    $_SESSION["message"] = "le Mot de passe ne correspond !"; 
                    $_SESSION["message_type"] = "error";
                    $_SESSION["message_input_p"] = "le Mot de passe ne correspond"; 
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();
                }

            }else{
                    $_SESSION["message"] = "Veillez saisir le vrai ancien mot de passe !"; 
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:profil');
                    exit();
                } 
        }
        else{
            $_SESSION["message"] = "Échec de la modification!"; 
            $_SESSION["message_type"] = "error";
            unset($_SESSION['token_csrf']);
            $previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
            header("Location: " . $previous_url);;
            exit();
        }
    }else{
    	$previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
	    header("Location: " . $previous_url);
	    exit;
    }

?>