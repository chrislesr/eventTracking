<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: default-src \'self\';');
session_start();
include 'connexion.php';
include '../functions.php';
 try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['id']) AND isset($_POST['csrf_token']) || $_POST['csrf_token'] == $_SESSION['token_csrf']) {


      $targetDir = "../assets/img/photoProfil/";
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

      if (!isset($_FILES['image-edit']) || !is_uploaded_file($_FILES['image-edit']['tmp_name'])) {
        echo json_encode(['success' => false, 'message' => 'Erreur de téléchargement de fichier.']);
        exit;
      }
      $conn = Connexion::connect();

      $file = $_FILES['image-edit'];
      $id_modif = htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8');
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Type de fichier non autorisé.']);
        exit;
      }
      $nameImge = uniqid() . '.' . $ext;
      $targetFile = $targetDir . $nameImge; 
      $aff = UsersComiteOneline($id_modif);

      if($_SESSION['niveau']=='LEVEL_NIV4'){
        $lastImg = $aff['image_utl'];

        $sql = "UPDATE utilisateurs SET image_utl = :img WHERE id_utl = :idUpdate";
      }else{
        $lastImg = $aff['image_inviter'];
        $sql = "UPDATE comite_organisateur SET image_inviter = :img WHERE id_inviter = :idUpdate";
      }

      $query = $conn->prepare($sql);
      $query->bindParam(':img',$nameImge);
      $query->bindParam(':idUpdate',$id_modif,PDO::PARAM_INT);
      $result = $query->execute();
      $cheminImg ='../assets/img/photoProfil/' . $lastImg;
      if($result){
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
          list($width, $height) = getimagesize($targetFile);
          $newWidth = 300; // Nouvelle largeur souhaitée
          $newHeight = ($height / $width) * $newWidth;
          $image = imagecreatefromstring(file_get_contents($targetFile));// Ou imagecreatefrompng(), imagecreatefromgif(),imagecreatefromjpeg selon le type de fichier
          $newImage = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
          imagejpeg($newImage, $targetFile, 90); // Enregistre l'image redimensionnée (qualité 90%)
          imagedestroy($image);
          imagedestroy($newImage);
          
          //DELETE LAST IMAGE PROFIL
            if(file_exists($cheminImg)) {
              unlink($cheminImg);                 
            }

          echo json_encode(['success' => true, 'message' => 'Image téléchargée avec succès.', 'filename' => basename($targetFile)]);
        } else {
          echo json_encode(['success' => false, 'message' => 'Erreur lors du déplacement du fichier.']);
        }
      }else {
          echo json_encode(['success' => false, 'message' => 'Erreur lors de le maodifcation.']);
        }
    }
  }catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => true, 'message' => $e->getMessage()]);
  }

?>