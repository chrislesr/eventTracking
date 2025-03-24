<?php
if(isset($_POST['code_value']) && isset($_POST['code_type'])){
    $codeValue = $_POST['code_value'];
    $codeType = $_POST['code_type'];

    echo "Type de code : " . $codeType . "<br>";
    echo "Valeur du code : " . $codeValue;

    // ... Traitement du code ...
} else {
   echo "Erreur: code_value ou code_type non défini.";
}
?>
