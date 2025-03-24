<?php 
session_start();
include 'connexion.php';
include '../functions.php';
try {
   
    $conn = Connexion::connect();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['control']) AND isset($_SESSION['token_csrf']) AND $_SESSION['token_csrf'] == $_POST['token']) {

        $action = isset($_POST['action']) ? $_POST['action'] : 'insert';
        $tableName = isset($_POST['tableName']) ? $_POST['tableName'] : null; // Nom de la table
        $control = inputtest($_POST['control']);
        $id_utilisateur = $_SESSION['id_user'];
        $id_evenement = $_SESSION['usersInfo']['id_event_cm_fk'] ?? $_SESSION['usersInfo']['id_env'];

        if($control == 'int_comite'){
            $tableName = 'comite_organisateur';
            $id = 'id_event_cm_fk';
            $id_opsTable = 'id_utl_inviter_fk';
        }
        else if($control == 'int_af_int_evt'){
            $tableName = 'affect_invitation_event';
            $id = 'id_aff';
            $id_opsTable = 'affect_util_id';
        }

        else if($control == 'int_participant'){
            $tableName = 'participants';
            if ($_SESSION['niveau'] == 'LEVEL_NIV4') {
                $id_opsTable = 'id_util_ops_part_fk';
            }else{
                $id_opsTable = 'id_cmt_osp_part_fk';
            }
            $id = 'id_part';
            
        }

        else if($control == 'int_affect_invt'){
            $tableName = 'invitations';
            $id = 'id_inv';
            $id_opsTable = 'id_utl_affect';
        }
        else if($control == 'int_categorie'){
            $tableName = 'categorie_inviter';
            $id = 'id_cat_inv';
            $id_opsTable = 'id_org_ctg_fk';
        }

        if ($action === 'insert') {
            // Insertion (code adapté à la table spécifiée dans $tableName)
            // ... (gérer l'insertion en fonction de $tableName) ... Exemple:
            if($tableName === 'comite_organisateur'){
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
                
        //traitement de l'image
                if (empty($_FILES['image']['name'])) {
                     $fileImgName = '';
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
                     }

                     if (!empty($email) && !empty($password)) {
                        $checkData = checkDataUser($email);
                        $checkData1 = checkDataInviter($email);
                        if($checkData >= 1){
                            $response = ['success' => true, 'message' => 'Email existe, Veillez nous contacter '];

                    }elseif($checkData1 >= 1){
                        $response = ['success' => true, 'message' => 'Email existe, Veillez nous contacter '];
                    }else{

                     //traitement de l'image
                    $sql = "INSERT INTO comite_organisateur (`id_utl_inviter_fk`, `id_niveau_inv_fk`,`id_event_cm_fk`, `nom_inviter`, `sexe_inviter`, `tel_inviter`, `poste_inviter`, `image_inviter`, `email_inviter`, `password_inviter`) VALUES (:id_usr,:id_level,:id_event, :name, :sexe, :tel, :poste, :image, :email, :password)";
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
                    $response = ['success' => true, 'message' => 'Membre ajouté'];
                }else{
                    $response = ['success' => false, 'message' => 'Eche de la validation'];
                    }
                    
                }
            }else{
                $response = ['success' => true, 'message' => 'Veillez remplir tous les champs'];
            }

//insertion invitation

        } elseif($tableName === 'affect_invitation_event'){

            if(!empty($_POST['code_value_id'])){
                $code_rfid = inputtest($_POST['code_value_id']);
                $data = getInvitation($code_rfid,'rfid');
                if(!empty($data)){
                  $id_invit = $data['id_inv'];  
              }else{
                 $id_invit = '';
              }
                $checked_id = checkAllData('invitations','code_invitation',$code_rfid);
            }elseif (!empty($_POST['code_value_qr'])) {
                $code_qr = inputtest($_POST['code_value_qr']);
                $data = getInvitation($code_qr,'qr');
                if(!empty($data)){
                  $id_invit = $data['id_inv'];  
              }else{
                 $id_invit = '';
              }
               
                $checked_id = checkAllData('invitations','code_qr_invitation',$code_qr);
            }elseif (!empty($_POST['code_value']) AND !empty($_POST['code_value'])) {
                $response = ['success' => false, 'message' => 'Enregistrer d\'abord le premier code'];
            }else{
                $response = ['success' => false, 'message' => 'Scanner l\'invitation'];
            }
            
            $id_event = inputtest($_POST['event']);

            if(!empty($id_invit) AND !empty($id_event))
            {

                $checked = checkAffectCode($id_invit,$id_event);
                if($checked >=1){
                    $response = ['success' => false, 'message' => 'l\'invitation ou billet existe'];
                }else
                {
                    if (!empty($id_invit)) {
                        if($checked_id == 0){
                            $response = ['success' => false, 'message' => 'ID invalide'];
                        }else{
                            $sql = ("INSERT INTO `affect_invitation_event`(`affect_invit_id`, `affect_event_id`, `affect_util_id`) VALUES (:id_inv,:id_even,:id_ut)");
                                $query = $conn->prepare($sql);
                                $query->bindParam(':id_inv', $id_invit,PDO::PARAM_INT);
                                $query->bindParam(':id_even', $id_event,PDO::PARAM_INT);
                                $query->bindParam(':id_ut', $id_utilisateur,PDO::PARAM_INT);
                                $result = $query->execute();

                            if($result AND !empty( $result)){
                                $response = ['success' => true, 'message' => 'ajouté avec succès'];
                             }else{
                                $response = ['success' => false, 'message' => 'Eche de la validation'];
                                }
                            }
                        }
                    }
                }else{
                    $response = ['success' => false, 'message' => 'Code invalide Ou le Champs est vide'];
                }
//Insertion affectation carte / code qr
       } elseif($tableName === 'invitations'){

            $code_rfid = inputtest($_POST['code_value_id']);
            $code_qr = inputtest($_POST['code_value_qr']);

            if(!empty($code_rfid) && !empty($code_qr)){
                $checked = checkAffectQrRfid($code_rfid,$code_qr);
                $checked_uid = checkAllData('invitations','code_invitation',$code_rfid);
                $checked_qr = checkAllData('invitations','code_qr_invitation',$code_qr);
            
                if($checked >= 1 AND $checked_uid == 0){
                    $response = ['success' => false, 'message' => 'La carte existe'];
                }
                else{
                    if($checked_uid >= 1 && $checked_qr == 0){
                    $sql = "UPDATE invitations SET code_qr_invitation = :code,id_utl_affect = :id_user WHERE code_invitation = :codV";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':code',$code_qr,PDO::PARAM_STR);
                    $query->bindParam(':id_user',$id_utilisateur,PDO::PARAM_STR);
                    $query->bindParam(':codV',$code_rfid,PDO::PARAM_STR);
                    $result = $query->execute();
                    if($result){
                             $response = ['success' => false, 'message' => 'Enregistrement réussie!'];
                        }else{
                            $response = ['success' => false, 'message' => 'Échec de la validation!'];
                        }
                    }else{
                         $response = ['success' => false, 'message' => 'La carte est invalide !'];
                    }
                }

            }else{
                $response = ['success' => false, 'message' => 'Veillez remplir les champs obligatoire'];
            }
//Insertion participant
        } elseif($tableName === 'participants'){
            $code_value_id = inputtest($_POST['code_value_id']);
            $code_value_qr = inputtest($_POST['code_value_qr']);
            $name_part = inputtest($_POST['name_part']);
            $first_part = inputtest($_POST['first_part']);
            $email_part = inputtest($_POST['email_part']);
            $sexe = inputtest($_POST['sexe']);
            $id_categorie = inputtest($_POST['categorie']);
            $tel = inputtest($_POST['tel']);
            $sql ='';   

        //traitement de l'image
            if (empty($_FILES['image']['name'])) {
                     $fileImgName = '';
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
                    $fileImgName ='profil'.date('Y').uniqid().'.'.$extension;
                }//End image

                     if(!empty($code_value_qr)){
                        $checked = checkAffectCodePart($code_value_qr,'qr');
                        if($checked == 1){
                                    $data = UsersComiteOneline($id_utilisateur);

                                if(isset($data['id_utl']) AND !empty($data['id_utl'])){
                                $id_organisateur = $data['id_utl'];
                            }else{
                                $id_organisateur = $data['id_utl_inviter_fk'];
                            }
                            
                            $data1 = lsAffectCodePart($code_value_qr,$id_organisateur,'qr');
                            $id_invit = $data1['id_aff'];
                            $id_event = $data1['affect_event_id'];
                            $checkedInvitation = checkAllDataInvitation($id_invit);
                            if($checkedInvitation == 0){

                                if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){
                                        $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `prenom_part`,`email_part`, `sexe_part`, `tel_part`, `id_util_ops_part_fk`, `image_part`) VALUES (:id_inv, :id_event, :id_cat,:name,:first,:email, :sexe, :tel, :id_ops, :img)";
                                }else{
                                        $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `prenom_part`, `email_part`, `sexe_part`, `tel_part`, `id_cmt_osp_part_fk`, `image_part`) VALUES (:id_inv, :id_event, :id_cat,:name,:first,:email, :sexe, :tel, :id_ops, :img)";
                                    }
                            }else{
                                $response = ['success' => false, 'message' => 'Code invalide'];
                            }
                        }else{
                            $response = ['success' => false, 'message' => 'Code invalide'];
                        }
                    }
                     /* ======END INSERT EN FONCTION DU CODE QR==========
                        ================================================= */
                    elseif (!empty($code_value_id) && !empty($name_part)) {
                    
                        $checked = checkAffectCodePart($code_value_id,'rfid');
                        if($checked == 1){
                            $data = UsersComiteOneline($id_utilisateur);

                            if(isset($data['id_utl']) AND !empty($data['id_utl'])){
                                $id_organisateur = $data['id_utl'];
                            }else{
                                $id_organisateur = $data['id_utl_inviter_fk'];
                            }
                           
                            $data1 = lsAffectCodePart($code_value_id,$id_organisateur,'rfid');
                            
                            $id_invit = $data1['id_aff'];
                            $id_event = $data1['affect_event_id'];

                            $checkedInvitation = checkAllDataInvitation($id_invit);
                            
                            if($checkedInvitation == 0){
                                if($_SESSION['niveau'] == 'LEVEL_1' OR $_SESSION['niveau'] == 'LEVEL_0' OR $_SESSION['niveau'] == 'LEVEL_NIV4' ){
                                 $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `prenom_part`, `sexe_part`, `tel_part`, `id_util_ops_part_fk`, `image_part`) VALUES (:id_inv, :id_event, :id_cat,:name,:first,:email, :sexe, :tel, :id_ops, :img)";
                            }else{
                                $sql = "INSERT INTO `participants`(`id_invitation_part_fk`, `id_event_part_fk`, `id_cat_inviter_fk`, `nom_part`, `prenom_part`, `sexe_part`, `tel_part`, `id_cmt_osp_part_fk`, `image_part`) VALUES (:id_inv, :id_event, :id_cat,:name,:first,:email, :sexe, :tel, :id_ops, :img)";
                        }
                    }else{
                            $response = ['success' => true, 'message' => 'Code invalide'];

                    }
                }else{
                    $response = ['success' => true, 'message' => 'Code invalide'];
                }
            }
            /* ======END INSERT EN FONCTION DU CODE QR==========
                        ================================================= */
                $req_base = $sql;
                    
                if($sql != null){
                    $query = $conn->prepare($sql);
                    $query->bindParam(':id_inv',$id_invit,PDO::PARAM_INT);
                    $query->bindParam(':id_event',$id_event,PDO::PARAM_INT);
                    $query->bindParam(':id_cat',$id_categorie,PDO::PARAM_INT);
                    $query->bindParam(':name',$name_part,PDO::PARAM_STR);
                    $query->bindParam(':first',$first_part,PDO::PARAM_STR);
                    $query->bindParam(':email,',$email_part,PDO::PARAM_STR);
                    $query->bindParam(':sexe',$sexe,PDO::PARAM_STR);
                    $query->bindParam(':tel',$tel,PDO::PARAM_STR);
                    $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_INT);
                    $query->bindParam(':img',$fileImgName,PDO::PARAM_STR);
                    
                    $result = $query->execute();

                    if($result && !empty($result)){
                        if (!empty($_FILES['image']['name'])){
                        $detinationImg = '../assets/img/photoAffiche/'.$fileImgName;
                        imagejpeg($image_comp, $detinationImg, $quality);
                        imagedestroy($image_comp);  
                                }
                            $response = ['success' => true, 'message' => 'Ajouter avec succès'];
                        }else{
                            $response = ['success' => false, 'message' => 'Eche de la validation'];

                        } 
                    }else{
                        $response = ['success' => false, 'message' => 'Eche de la validation'];
                    }
                            
//Insertion ....

            } elseif($tableName === 'categorie_inviter'){
            $cat_name = inputtest($_POST['cat_name']);

            if(!empty($cat_name)){
                 $checked = checkCategorie($cat_name,$id_evenement,$id_utilisateur);
            
                if($checked == 0){

                    $sql = "INSERT INTO categorie_inviter(nom_categorie,id_event_ctg,id_org_ctg_fk) VALUES (:cat_name,:id_event,:id_ops)";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':cat_name',$cat_name,PDO::PARAM_STR);
                    $query->bindParam(':id_event',$id_evenement,PDO::PARAM_STR);
                    $query->bindParam(':id_ops',$id_utilisateur,PDO::PARAM_STR);
                    $result = $query->execute();

                    if($result){
                             $response = ['success' => false, 'message' => 'Enregistrement réussie!'];
                        }else{
                            $response = ['success' => false, 'message' => 'Échec de la validation!'];
                        }
                }
                else{
                    $response = ['success' => false, 'message' => 'Le nom de la place existe']; 
                }

            }else{
                $response = ['success' => false, 'message' => 'Veillez remplir les champs obligatoire'];
            }
//Insertion categorie ou place
            }
            else {
                throw new Exception("Table inconnue.");
            }

        } elseif ($action === 'get_last') {
            // Récupération du dernier élément
            if ($tableName) {
                $stmt = $conn->query("SELECT * FROM `$tableName` WHERE `$id_opsTable` = $id_utilisateur ORDER BY `$id` DESC LIMIT 1");
                $lastItem = $stmt->fetch(PDO::FETCH_ASSOC);
                $response = ['success' => true, 'lastItem' => $lastItem ? $lastItem : null];
            } else {
                throw new Exception("Nom de table manquant.");
            }
        } else {
            throw new Exception("Action invalide.");
        }



    } else {
        throw new Exception("Méthode HTTP non autorisée.");
    }
} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}
//header('Content-Type: application/json');
echo json_encode($response);
?>