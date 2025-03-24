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
		if($control == 'int_comite')
		{
			$com_name = inputtest($_POST['com_name']);
	        $email = inputtest($_POST['com_email']);
	        $sexe = inputtest($_POST['sexe']);
	        $tel = inputtest($_POST['tel']);
	        $id_level = inputtest($_POST['fonction']);
            $poste = inputtest($_POST['poste']);
	        $pass = inputtest($_POST['password']);
	        $password = password_hash($pass, PASSWORD_DEFAULT);
	        $ops = $_SESSION['id_user'];
            $data = lsEventOneline($ops);
            $id_event = $data['id_env'];
        	
        
         if (empty($_FILES['image']['name'])) {
             $fileImgName = '';
         }else{
            $image_comp = $_FILES['image'];
             $maxSize = 2 * 1024 * 1024;
             $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
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
             $_SESSION['message'] = "Erreur de téléchargement : " . $errorMessage;
            header('location:add_comite');
        }
        $imageType = exif_imagetype($_FILES['image']['tmp_name']);
        if ($imageType === false) {
                $_SESSION['error'] = "Le fichier n'est pas une image valide.";
                header('location:add_comite');
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
        $fileImgName ='PF'.time().'.'.$extension;
         }
        if (!empty($email) && !empty($password)) {
            $checkData = checkDataUser($email);
            $checkData1 = checkDataInviter($email);
            if($checkData >= 1){
            $_SESSION['message'] = 'Email existe, Veillez nous contacter !';
            unset($_SESSION['token_csrf']);
            header('location:add_comite');
            exit;
        }elseif($checkData1 >= 1){
        	 $_SESSION['message'] = 'Email existe, Veillez nous contacter !';
             unset($_SESSION['token_csrf']);
            header('location:add_comite');
            exit;
        }else{
            $sql = ("INSERT INTO comite_organisateur(`id_utl_inviter_fk`, `id_niveau_inv_fk`,`id_event_cm_fk`, `nom_inviter`, `sexe_inviter`, `tel_inviter`, `poste_inviter`, `image_inviter`, `email_inviter`, `password_inviter`) VALUES (:id_usr,:id_level,:id_event, :name, :sexe, :tel, :poste, :image, :email, :password)");
            $query = $conn->prepare($sql);
            $query->bindParam(':id_usr', $ops,PDO::PARAM_INT);
            $query->bindParam(':id_level', $id_level,PDO::PARAM_INT);
            $query->bindParam(':id_event', $id_event,PDO::PARAM_INT);
            $query->bindParam(':name', $com_name,PDO::PARAM_STR);
            $query->bindParam(':sexe', $sexe,PDO::PARAM_STR);
            $query->bindParam(':tel', $tel,PDO::PARAM_STR);
            $query->bindParam(':poste', $poste,PDO::PARAM_STR);
            $query->bindParam(':image', $fileImgName,PDO::PARAM_STR);
            $query->bindParam(':email', $email,PDO::PARAM_STR);
            $query->bindParam(':password', $password,PDO::PARAM_STR);
            $result = $query->execute();
            if($result){
                if (!empty($_FILES['image']['name'])){
                    $detinationImg = '../assets/img/photoProfil/'.$fileImgName;
                    imagejpeg($image_comp, $detinationImg, $quality);
                    imagedestroy($image_comp);  
                }
                $_SESSION['message'] = "Enregistrement réussi!";
                $_SESSION["message_type"] = "success";
                unset($_SESSION['token_csrf']);
                header('Location: add_comite');
                exit();
            }else{
                $_SESSION["message"] = "Échec de la validation!"; 
                $_SESSION["message_type"] = "error";
                unset($_SESSION['token_csrf']);
                header('Location: add_comite');
                exit();
            }
        }
            
         }else{
            $_SESSION["message"] = "Veillez remplir tous les champs "; 
            $_SESSION["message_type"] = "error";
            unset($_SESSION['token_csrf']);
            header('Location: add_comite');
            exit();
         }
		}//end control add comite

        
        elseif ($control == 'int_affect_invt') 
        {
            $code_rfid = inputtest($_POST['rfid']);
            $code_qr = inputtest($_POST['qrcode']);

            if(!empty($code_rfid) && !empty($code_qr)){
                $checked = checkAffectQrRfid($code_rfid,$code_qr);
                $checked_qr = checkAllData('invitations','code_qr_invitation',$code_value);
                if($checked >= 1 AND $checked_qr >=1){
                    $_SESSION['message'] = 'le code QR est affecté sur cette carte !';
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:affect_qr_rfid');
                    exit;
                }else{
                    $sql = "UPDATE invitations SET code_qr_invitation = :code,id_utl_affect = :id_user WHERE code_invitation = :codV";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':code',$code_qr,PDO::PARAM_STR);
                    $query->bindParam(':id_user',$id_utilateur,PDO::PARAM_STR);
                    $query->bindParam(':codV',$code_rfid,PDO::PARAM_STR);
                    $result = $query->execute();
                    if($result){
                            $_SESSION['message'] = "Enregistrement réussi!";
                            $_SESSION["message_type"] = "success";
                            unset($_SESSION['token_csrf']);
                            header('Location: affect_qr_rfid');
                            exit();
                        }else{
                            $_SESSION["message"] = "Échec de la validation!"; 
                            $_SESSION["message_type"] = "error";
                            unset($_SESSION['token_csrf']);
                            header('Location: affect_qr_rfid');
                            exit();
                        }
                    
                }

            }else{
                 $_SESSION['message'] = 'Veillez remplir les champs obligatoire';
                unset($_SESSION['token_csrf']);   
                header('location:affect_qr_rfid');
                exit;
            }
        }//end
        elseif ($control == 'int_participant') {
            $code_value_id = inputtest($_POST['code_value_id']);
            $code_value_qr = inputtest($_POST['code_value_qr']);
            $name_part = inputtest($_POST['name_part']);
            $sexe = inputtest($_POST['sexe']);
            $id_categorie = inputtest($_POST['categorie']);
            $tel = inputtest($_POST['tel']);


            if(!empty($code_value_qr) && !empty($name_part)){
                $checked = checkAffectCodePart($code_value_qr,'qr');
                if($checked == 1){
                    $data = UsersComiteOneline($id_utilateur);
                    if(isset($data['id_utl']) AND !empty($data['id_utl'])){
                        $id_organisateur = $data['id_utl'];
                    }else{
                        $id_organisateur = $data['id_utl_inviter_fk'];
                    }
                    
                    $data1 = lsAffectCodePart($code_value_qr,$id_organisateur,'qr');
                    $id_invit = $data1['id_inv'];
                    $id_event = $data1['id_env'];
                    $checkedInvitation = checkAllDataInvitation($id_invit);
                    if($checkedInvitation == 0){

                    if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){
                    $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `sexe_part`, `tel_part`, `id_util_ops_part_fk`) VALUES (:id_inv, :id_event, :id_cat,:name, :sexe, :tel, :id_ops)";
                }else{
                    $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `sexe_part`, `tel_part`, `id_cmt_osp_part_fk`) VALUES (:id_inv, :id_event, :id_cat,:name, :sexe, :tel, :id_ops)";
                }
            }else{
                    $_SESSION['message'] = "Doulons d'invitation !";
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:participant');
                    exit;
            }
                }else{
                    $_SESSION['message'] = 'Erreur du code !';
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:participant');
                    exit;
                }
            }elseif (!empty($code_value_id) && !empty($name_part)) {
                
                $checked = checkAffectCodePart($code_value_id,'rfid');
                if($checked == 1){
                    $data = UsersComiteOneline($id_utilateur);

                    if(isset($data['id_utl']) AND !empty($data['id_utl'])){
                        $id_organisateur = $data['id_utl'];
                    }else{
                        $id_organisateur = $data['id_utl_inviter_fk'];
                    }
                    
                    $data1 = lsAffectCodePart($code_value_id,$id_organisateur,'rfid');
                    $id_invit = $data1['id_inv'];
                    $id_event = $data1['id_env'];
                    $checkedInvitation = checkAllDataInvitation($id_invit);
                    if($checkedInvitation == 0){
                    if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){
                    $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `sexe_part`, `tel_part`, `id_util_ops_part_fk`) VALUES (:id_inv, :id_event, :id_cat,:name, :sexe, :tel, :id_ops)";
                }else{
                    $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `sexe_part`, `tel_part`, `id_cmt_osp_part_fk`) VALUES (:id_inv, :id_event, :id_cat,:name, :sexe, :tel, :id_ops)";
                }
            }else{
                    $_SESSION['message'] = "Doulons d'invitation !";
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:participant');
                    exit;
            }
                }else{
                    $_SESSION['message'] = 'Erreur du code !';
                    $_SESSION["message_type"] = "error";
                    unset($_SESSION['token_csrf']);
                    header('location:participant');
                    exit;
                }
            }
                    
                    $query = $conn->prepare($sql);
                    $query->bindParam(':id_inv',$id_invit,PDO::PARAM_INT);
                    $query->bindParam(':id_event',$id_event,PDO::PARAM_INT);
                    $query->bindParam(':id_cat',$id_categorie,PDO::PARAM_INT);
                    $query->bindParam(':name',$name_part,PDO::PARAM_STR);
                    $query->bindParam(':sexe',$sexe,PDO::PARAM_STR);
                    $query->bindParam(':tel',$tel,PDO::PARAM_STR);
                    $query->bindParam(':id_ops',$id_utilateur,PDO::PARAM_INT);
                    
                    $result = $query->execute();

                    if($result){
                            $_SESSION['message'] = "Enregistrement réussi!";
                            $_SESSION["message_type"] = "success";
                            unset($_SESSION['token_csrf']);
                            header('Location: participant');
                            exit();
                        }else{
                            $_SESSION["message"] = "Échec de la validation!"; 
                            $_SESSION["message_type"] = "error";
                            unset($_SESSION['token_csrf']);
                            header('Location: participant');
                            exit();
                        }
        }
	}else{
        $previous_url = $_SESSION['previous_url'] ?? '../deconnexion';
         header("Location: " . $previous_url);
        exit;
    }
?>