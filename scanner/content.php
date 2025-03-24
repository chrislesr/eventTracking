<?php
session_start();
include "../connexion.php";
include "../functions.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['texte']) AND isset($_SESSION['token_csrf']) AND $_SESSION['token_csrf'] == $_POST['token'])
{
    $Dresult = htmlspecialchars(trim($_POST['texte']));
    $idEvent = htmlspecialchars(trim($_POST['idEvent']));
    if (empty($Dresult)) {
        $Dresult = '====';
    }
    $Write = "<?php $" . "Dresult ='" . $Dresult . "'; " . "echo $" . "Dresult ;" . " ?>";
    file_put_contents('DatacontentTexte.php', $Write);

    $conn = Connexion::connect();
    try {
            $checkParticipant = checkAffectCodePartScan($Dresult,$idEvent);
            if($checkParticipant == 0){
            $sql = "SELECT id_part,background_evenement,image_part,sigle_cat_inviter,nom_part,prenom_part,img_girl_evenement,img_man_evenement,affiche_evenemt FROM invitations iv LEFT JOIN affect_invitation_event af ON af.affect_invit_id = iv.id_inv 
            INNER JOIN participants p ON p.id_invitation_part_fk = af.id_aff
            INNER JOIN evenements e ON af.affect_event_id = e.id_env
            INNER JOIN categorie_inviter ct ON p.id_cat_inviter_fk = ct.id_cat_inv
            WHERE code_invitation = :Dresult AND af.affect_event_id = :id AND p.etat_participant='attente' AND af.control_deleted = 'no_deleted'
            OR code_qr_invitation = :Dresult AND af.affect_event_id = :id AND p.etat_participant='attente' AND af.control_deleted = 'no_deleted'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Dresult',$Dresult,PDO::PARAM_STR);
            $stmt->bindParam(':id',$idEvent,PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // header('Content-Type: application/json');
            if ($result) {
                $sql = "UPDATE participants SET etat_participant = 'confirme' WHERE id_part = :idP";
                $query = $conn->prepare($sql);
                $query->bindParam(':idP',$result['id_part']);
                $query->execute();
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'La carte est invalide']);
            }
        }else{
            echo json_encode(['error' => 'La carte est utilisé']);
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}else{
    echo json_encode(['error' => 'Echec de la verification']);
}
?>
