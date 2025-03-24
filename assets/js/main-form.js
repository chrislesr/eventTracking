$(document).ready(function() {
      const nameTable = document.getElementById('verif').value;
      const control = document.getElementById('control').value;
      const token = document.getElementById('token').value;

    // Récupération du dernier client au chargement de la page
    $.ajax({
        url: 'action_register.php',
        type: 'POST',
        data: { tableName: nameTable, action: 'get_last', control: control, token: token },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.lastItem) {
                displayLastClientInfo(response.lastItem);
            } else {
                $('#lastClientInfo').html("<p>Aucune information trouvée.</p>");
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération du dernier ;::", error);
        }
    });

// Fonction pour afficher les infos du client (inchangée)
    
    function displayLastClientInfo(info) {
        if(nameTable === 'comite'){
        $('#lastName').text(info.nom_inviter );
        $('#lastEmail').text(info.email_inviter || 'Non spécifié');
        $('#lastPhone').text(info.tel_inviter || 'Non spécifié');
         $('#lastImage').attr('src', info.image_inviter ? '../assets/img/photoProfil/' + info.image_inviter : '../assets/img/photoProfil/default.jpg');
        $('#lastClientInfo').removeClass('d-none');
    }else if (nameTable === 'invitation' || nameTable === 'affectationCode') {
        $('#lastID').text('Carte validée');
        $('#lastImage').attr('src', info.image_inviter ? '../assets/img/photoProfil/' + info.image_inviter : '../assets/img/photoProfil/default.jpg');
        $('#lastClientInfo').removeClass('d-none');
    }
    else if (nameTable === 'participant') {
        $('#lastName').text(info.nom_part);
        $('#lastEmail').text(info.email || 'Non spécifié');
        $('#lastPhone').text(info.tel_part || 'Non spécifié');
        $('#lastImage').attr('src', info.image_part ? '../assets/img/photoProfil/' + info.image_part : '../assets/img/photoProfil/default.jpg');
    }

}

    $('#myForm_add').submit(function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'action_register.php',
            type: 'POST',
            data:  formData, // Inclure les données du formulaire
            contentType: false, 
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showPopup(response.message, 'success');
                    $('#myForm_add')[0].reset();
                    // Raffraîchir l'affichage du dernier client
                    $.ajax({
                        url: 'action_register.php',
                        type: 'POST',
                        data: { tableName: nameTable, action: 'get_last', control: control, token: token },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.lastItem) {
                                displayLastClientInfo(response.lastItem);
                            } else {
                                $('#lastClientInfo').html("<p>Aucune information trouvée.</p>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur lors de la récupération du dernier ;::", error);
                        }
                    });
                    
                } else {
                    showPopup(response.message, 'danger');
                }
            },
            error: function() {
                showPopup("Erreur lors de la requête AJAX.", 'danger');
            }
        });
    });

    

  function showPopup(message, type) { 
    $('#popup').text(message).addClass(type).show(); 
    setTimeout(function() { 
    $('#popup').hide().removeClass('success').removeClass('danger'); 
  }, 5000); 
} 

});   