<?php
session_start();
include 'connexion.php';
include '../functions.php';
$conn = Connexion::connect();

$id_utilisateur = $_SESSION['id_user'];
$id_evenement = $_SESSION['usersInfo']['id_env'];
$control = inputtest($_GET['control']);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rowsPerPage = isset($_GET['rowsPerPage']) ? intval($_GET['rowsPerPage']) : 10;
$offset = ($page - 1) * $rowsPerPage;
$conditions = [];
$searchs = isset($_GET['search']) ? $_GET['search'] : '';
 
if (!empty($searchs)) {
    $querySearch = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    $querySearch = htmlspecialchars(trim($querySearch), ENT_QUOTES, 'UTF-8');
    $searchTerms = explode(' ', $querySearch);
    foreach ($searchTerms as $term) {
        if ($control == 'invitation') {
            $conditions[] = "(code_invitation LIKE '%$term%' OR code_qr_invitation LIKE '%$term%')";
        }

        else if($control == 'participant')
        {
             $conditions[] = "(nom_part LIKE '%$term%' OR prenom_part LIKE '%$term%' OR sexe_part LIKE '%$term%' OR tel_part LIKE '%$term%' OR DATE_FORMAT(date_enreg_part, '%d-%m-%Y') LIKE '%$term%')";
        }
        else if($control == 'categorie'){
            $conditions[] = "(nom_categorie LIKE '%$term%' OR DATE_FORMAT(date_enreg_cat, '%d-%m-%Y') LIKE '%$term%')";
        }
        else if($control == 'notification'){
            $conditions[] = "(nom_part LIKE '%$term%' OR DATE_FORMAT(date_enreg_part, '%d-%m-%Y') LIKE '%$term%')";
        }
    }
} else {
    if ($control == 'invitation') {
        $conditions[] = "code_invitation != ''";
    }
    else if($control == 'participant'){
        $conditions[] = "nom_part != ''";
    }
    else if($control == 'categorie'){
        $conditions[] = "nom_categorie != ''";
    }
    else if($control == 'notification'){
        $conditions[] = "nom_part != ''";
    }
}

if ($control == 'invitation') {
    if ($id_utilisateur > 0) {
            $conditions[] = "E.id_util_org = $id_utilisateur AND A.control_deleted = 'no_deleted' AND A.is_actif ='active' ";
        }
        $conditionString = implode(' AND ', $conditions);

    // Requête pour obtenir le nombre total de lignes
    $sqlTotal = "SELECT COUNT(*) AS count FROM affect_invitation_event A LEFT JOIN invitations I ON A.affect_invit_id = I.id_inv 
    INNER JOIN evenements E ON A.affect_event_id = E.id_env
    WHERE $conditionString";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalRows = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    $totalPages = ceil($totalRows / $rowsPerPage);

    // Requête pour obtenir les données
    $sql = "SELECT E.affiche_evenemt as autre_img,E.titre_evenement,I.code_invitation,I.code_qr_invitation,A.id_aff as id_delete FROM affect_invitation_event A LEFT JOIN invitations I ON A.affect_invit_id = I.id_inv 
    INNER JOIN evenements E ON A.affect_event_id = E.id_env
    WHERE $conditionString LIMIT :offset, :rowsPerPage";
    $stmt = $conn->prepare($sql);
    // $stmt->bindValue(':id_utilisateur', $id_utilisateur,PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//participant ==================

else if ($control == 'participant') {
    if ($id_utilisateur > 0) {
        if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
            $conditions[] = "(id_event_part_fk = $id_evenement) AND is_actif_part ='active' ";
        }else{
            $conditions[] = "(id_util_ops_part_fk = $id_utilisateur OR id_cmt_osp_part_fk = $id_utilisateur) AND is_actif_part ='active' ";
            }
        }
        $conditionString = implode(' AND ', $conditions);
    // Requête pour obtenir le nombre total de lignes
    $sqlTotal = "SELECT COUNT(*) AS count FROM participants WHERE $conditionString";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalRows = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    $totalPages = ceil($totalRows / $rowsPerPage);

    // Requête pour obtenir les données
    if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
    $sql = "SELECT image_part as autre_img,nom_part,prenom_part,sexe_part,tel_part,nom_categorie,DATE_FORMAT(date_enreg_part, '%d-%m-%Y'),id_part as id_edite,id_part as id_delete FROM participants LEFT JOIN categorie_inviter ON participants.id_cat_inviter_fk = categorie_inviter.id_cat_inv WHERE $conditionString LIMIT :offset, :rowsPerPage";
    }else{
        $sql = "SELECT image_part as img_profil,nom_part,prenom_part,sexe_part,tel_part,nom_categorie,DATE_FORMAT(date_enreg_part, '%d-%m-%Y'), FROM participants WHERE $conditionString LIMIT :offset, :rowsPerPage";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//categorie ou place reservé ==================

else if ($control == 'categorie') {
    if ($id_utilisateur > 0) {
            $conditions[] = "(id_org_ctg_fk = $id_utilisateur AND id_event_ctg = $id_evenement ) AND controle_deleted_cat ='no_deleted' ";
        }
        $conditionString = implode(' AND ', $conditions);
    // Requête pour obtenir le nombre total de lignes
    $sqlTotal = "SELECT COUNT(*) AS count FROM categorie_inviter WHERE $conditionString";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalRows = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    $totalPages = ceil($totalRows / $rowsPerPage);

    // Requête pour obtenir les données
    if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
    $sql = "SELECT nom_categorie,titre_evenement,DATE_FORMAT(date_enreg_cat, '%d-%m-%Y'),id_cat_inv as id_edite,id_cat_inv as id_delete FROM categorie_inviter LEFT JOIN evenements ON categorie_inviter.id_event_ctg = evenements.id_env WHERE $conditionString LIMIT :offset, :rowsPerPage";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Notification ==================

else if ($control == 'notification') {
    if ($id_utilisateur > 0) {
        if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
            $conditions[] = "(id_event_part_fk = $id_evenement) AND is_actif_part ='active' ";
            }
        }
        $conditionString = implode(' AND ', $conditions);
    // Requête pour obtenir le nombre total de lignes
    $sqlTotal = "SELECT COUNT(*) AS count FROM participants WHERE $conditionString AND id_invitation_part_fk IN (SELECT id_invitation_part_fk FROM participants WHERE $conditionString GROUP BY id_invitation_part_fk HAVING COUNT(*) > 1)";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalRows = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    $totalPages = ceil($totalRows / $rowsPerPage);

    // Requête pour obtenir les données
    if(checkRole(['LEVEL_1','LEVEL_NIV4'])){
    $sql = "SELECT image_part as autre_img,nom_part,etat_participant,nom_inviter,nom_utl,DATE_FORMAT(date_enreg_part, '%d-%m-%Y') FROM participants LEFT JOIN comite_organisateur ON participants.id_cmt_osp_part_fk = comite_organisateur.id_inviter LEFT JOIN utilisateurs ON participants.id_util_ops_part_fk = utilisateurs.id_utl WHERE $conditionString AND id_invitation_part_fk IN (SELECT id_invitation_part_fk FROM participants WHERE $conditionString GROUP BY id_invitation_part_fk HAVING COUNT(*) > 1) LIMIT :offset, :rowsPerPage";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$response = array(
    'totalPages' => $totalPages,
    'data' => $data
);

header('Content-Type: application/json');
echo json_encode($response);

$conn = Connexion::disconnect();
?>
