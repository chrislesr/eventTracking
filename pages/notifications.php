<?php 
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: default-src \'self\';');
session_start();

include 'connexion.php';
include '../functions.php';



try {
    // **1. Vérification CSRF**
    // if (!isset($_POST['token_csrf']) || $_POST['token_csrf'] !== $_SESSION['token_csrf']) {
    //     http_response_code(403); // Forbidden
    //     echo json_encode(['error' => true, 'message' => 'Token CSRF invalide.']);
    //     exit;
    // }
    
    // **1. Vérification CSRF**
    // **2. Protection Anti-DDoS/Flood**
    // **3. Cache des données**
    

    // Si le cache est vide, calculer les données
    $conn = Connexion::connect();
    $id_evenement = $_SESSION['usersInfo']['id_env'];

    $nbreDbInvitPartic = nbreDbInvitPartic($id_evenement,$conn);
    foreach ($nbreDbInvitPartic as $aff) {
       $alert = $aff['nbre']; 
    }
    $response = 0; // Nombre de notifications
        // Nombre d'alertes
    $message = 0;  // Nombre de messages
    unset($_SESSION['token_csrf']);

    if (!isset($_SESSION['token_csrf'])) {
    $_SESSION['token_csrf'] = bin2hex(random_bytes(32));

    $token_csrf = $_SESSION['token_csrf'];
    }

    $data = array(
        'nbrenotification' => htmlspecialchars($alert, ENT_QUOTES, 'UTF-8'),
        'nbremessage' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'token_csrf' => $token_csrf
    );

    // Mise en cache des données pour 60 secondes
    // apcu_store($cacheKey, json_encode($data), 60);
     $conn =Connexion::disconnect();
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
?>