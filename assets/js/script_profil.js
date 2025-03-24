const btnProfile = document.querySelector(".btn-profile");
  const btnEdit = document.querySelector(".btn-edit");
  const btnPassword = document.querySelector(".btn-password");

  btnProfile.addEventListener('click', () => {
    
    btnProfile.classList.add("active");
    btnEdit.classList.remove("active");
    btnPassword.classList.remove("active");

    document.querySelector(".section-profil").classList.remove("d-none");
    document.querySelector('.section-edit').classList.add('d-none');
    document.querySelector('.section-password').classList.add('d-none');
  });

  btnEdit.addEventListener("click", () => {
    btnEdit.classList.add("active");
    btnProfile.classList.remove("active");
    btnPassword.classList.remove("active");

    document.querySelector('.section-edit').classList.remove('d-none');
    document.querySelector('.section-profil').classList.add('d-none');
    document.querySelector('.section-password').classList.add('d-none');
  });

  btnPassword.addEventListener("click", () => {
    btnPassword.classList.add("active");
    btnEdit.classList.remove("active");
    btnProfile.classList.remove("active");

    document.querySelector('.section-password').classList.remove('d-none');
    document.querySelector('.section-edit').classList.add('d-none');
    document.querySelector('.section-profil').classList.add('d-none');
    
  });

    const importButton = document.getElementById("importButton");
    const saveButton = document.getElementById("saveButton");
    const imageInput = document.getElementById("imageInput");

    importButton.addEventListener("click", () => imageInput.click());

    imageInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) {
        importButton.style.display = "none";
        saveButton.style.display = "inline-block";
      }
    });

    saveButton.addEventListener("click", () => {
  const formData = new FormData(document.querySelector(".form-img"));
  const progressBar = document.createElement('progress'); // Crée une barre de progression
  progressBar.setAttribute('max', 100); // Valeur maximale de la barre
  document.body.appendChild(progressBar); // Ajoute la barre à la page

  fetch('action_modif_profil.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
  })
  .then(data => {
    if (data.success) {
      const timestamp = Date.now();
      const imgSrc = `../assets/img/photoProfil/${data.filename}?t=${timestamp}`;
      document.getElementById('profileImage').src=imgSrc;
      importButton.style.display = "inline-block";
      saveButton.style.display = "none";
      showPopup(data.message, 'success');
      
    } else {
      showPopup('Erreur&nbsp;: ' + data.message, 'success');
    }
    document.body.removeChild(progressBar); // Supprime la barre de progression
  })
  .catch(error => {
    console.error('Erreur lors de la requête AJAX&nbsp;:', error);
    showPopup('Erreur lors de l\'enregistrement de l\'image.', 'success');
    document.body.removeChild(progressBar); // Supprime la barre de progression
  });
});

function showPopup(message, type) { 
    $('#popup').text(message).addClass(type).show(); 
    setTimeout(function() { 
    $('#popup').hide().removeClass('success').removeClass('danger'); 
  }, 5000); 
} 