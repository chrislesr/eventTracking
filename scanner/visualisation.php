<?php
    session_start();
    include '../functions.php';
    if(!isset($_SESSION['id_user']) && !checkRole(['LEVEL_1','LEVEL_NIV4','LEVEL_NIV1'])){
    header('Location:../index');
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation</title>
     <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="assets/css/styleViews.css">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Récupérer les données depuis le localStorage
            let data = localStorage.getItem('data');
            if (data) {
                let result = JSON.parse(data);
                let imgEvenement;
                const imgsrc = '../assets/img/photoAffiche/';
                const imgBackground = result.background_evenement || 'background1.png';
                const imgProfil = result.image_part;
                const appartenance = result.sigle_cat_inviter;
                const nameParticipant = result.nom_part;
                const lastNameParticipant = result.prenom_part; 

                if(appartenance === 'fm_girls'){
                    imgEvenement = result.img_girl_evenement || result.affiche_evenemt;
                }else if(appartenance === 'fm_man'){
                    imgEvenement = result.img_man_evenement || result.affiche_evenemt;
                }else{
                    imgEvenement = result.image_part || result.affiche_evenemt;
                }

                
                
                
                let infoContent;
                let  infoContentLeft;
                let  infoContentRight;
                // Appliquer l'image de fond à toute la page
                if (imgBackground) {
                    // $('body').css('background-image', 'url(' + backgroundUrl + ')');
                    $('body').css('background-image', 'url(' + imgsrc + imgBackground +')');

                } 

                if(imgEvenement) {
                    infoContentLeft = `
                        <img src = "${imgsrc}${imgEvenement}">
                    `;

                    $('.card-left').html(infoContentLeft);
                }

                if(nameParticipant) {
                    infoContentRight = `
                        <p class="text-bv">Bienvenue Mr/Md</p>
                        <p class="text-invite">${nameParticipant} <br>${lastNameParticipant}</p>
                    `;
                    $('.card-right').html(infoContentRight);
                }


                
                

                if (imgBackground === null) {
                    console.log(imgBackground);
                    let infoContent1 = `
                        <p class="info-item">Autre info : <strong id="other_info">la famille de la fille</strong></p>
                    `;
                    $('#info_content1').html(infoContent1);
                }
                
            } else {
                $('body').css('background-image', 'url(../assets/img/photoAffiche/background.jpg)');
                const messsgeAlert = document.getElementById('card-message');
               messsgeAlert.classList.remove('d-none');
                $('#card-message').html('<p>Aucune information trouvée</p>');
            }
        });
    </script>
</head>
<body>
   
        <div id="card-message" class="d-none shadow"></div>
    
    <div class="container-fluid">

        <div class="content-vs">
            <div class="card-left"></div>
            <div class="card-right"></div>
        </div>
        <div id="loadingMessage" style="display: none;">Chargement, veuillez patienter...</div>
        <script>
        
        window.addEventListener('beforeunload', function () {
            document.getElementById('loadingMessage').style.display = 'block';
        });
    </script> 
</body>
<!-- <script>
        
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 60000);  

        
        window.addEventListener('unload', function() {
        window.location.href = 'index.php';
});

    </script> -->
</html>
