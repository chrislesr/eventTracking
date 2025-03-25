<?php 
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: default-src \'self\';');
session_start();

include 'connexion.php';
include '../functions.php';





// if (!isset($_SESSION['token_csrf'])) {
//     echo json_encode(['error' => true, 'message' => 'Le token CSRF de la session est introuvable.']);
//     exit;
// }


try {
    // **1. Vérification CSRF**
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => true, 'message' => 'Le token CSRF est manquant.']);
        exit;
    }
    // **1. Vérification CSRF**
    // **2. Protection Anti-DDoS/Flood**
    // **3. Cache des données**
    

    // Si le cache est vide, calculer les données
    $conn = Connexion::connect();
    if(isset($_SESSION['usersInfo']['id_env']) AND !empty($_SESSION['usersInfo']['id_env'])){
    $id_evenement = $_SESSION['usersInfo']['id_env'];

    $nbreDbInvitPartic = nbreDbInvitPartic($id_evenement,$conn);
    foreach ($nbreDbInvitPartic as $aff) {
       $alert = $aff['nbre']; 
    }
    $response = 0; // Nombre de notifications
        // Nombre d'alertes
    $message = 0;  // Nombre de messages
    unset($_SESSION['csrf_token']);

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    }
    
    $csrf_token = $_SESSION['csrf_token'];
    $data = array(
        'nbrenotification' => htmlspecialchars($alert, ENT_QUOTES, 'UTF-8'),
        'nbremessage' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'csrf_token' => $csrf_token
    );

    // Mise en cache des données pour 60 secondes
    // apcu_store($cacheKey, json_encode($data), 60);
     $conn =Connexion::disconnect();
    echo json_encode($data);
}else{
    echo json_encode(['error' => true, 'message' => 'Le token CSRF est manquant.']);
}
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
?>