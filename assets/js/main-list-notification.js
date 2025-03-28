var currentPage = 1;
        var rowsPerPage = 10;
        var totalPages = 0;

        function fetchData(page) {
            var search = document.getElementById("search_notif").value;
            var controlNotif = document.getElementById("control-notif").value;
            console.log(search);
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_data.php?page=" + page + "&rowsPerPage=" + rowsPerPage + "&search=" + search + "&control=" + controlNotif, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    totalPages = response.totalPages;
                    renderTableNotif(response.data);
                    document.getElementById("pageNumber").textContent = "Page " + currentPage + " sur " + totalPages;
                }
            };
            xhr.send();
        }

        function renderTableNotif(data) {
          var tableBody = document.getElementById("myTables").getElementsByTagName('tbody')[0];
          tableBody.innerHTML = "";
          if (data.length === 0) {
              document.getElementById("noResultsRow").style.display = "";
              return; // Quitter la fonction s'il n'y a pas de données
          }
          document.getElementById("noResultsRow").style.display = "none";
          var dataContent = Object.keys(data[0]);
          for (var i = 0; i < data.length; i++) {
              var row = tableBody.insertRow();
               row.classList.add("centered-row");

              dataContent.forEach(function(content) {
                  var cell = row.insertCell();
                  var view = document.getElementById("control-notif").value;
                  cell.classList.add("centered-cell"); 
                  if (content === 'code_invitation' || content === 'code_qr_invitation') {
                      var qrDiv = document.createElement("div");
                      new QRCode(qrDiv, {
                          text: data[i][content],
                          width: 100,
                          height: 100
                      });
                      cell.appendChild(qrDiv);
                  }else if(content === 'id_edite'){
                    var Id_edite = data[i][content];
                    var linkEdit = document.createElement("a");
                      linkEdit.href = 'form-modification?view=' + view + '&verif=' + Id_edite;
                      linkEdit.className = "btn btn-outline-secondary btn-sm mr-2";

                    var iconModifier = document.createElement('i');
                        iconModifier.className = 'bi bi-pencil';
                        linkEdit.appendChild(iconModifier);

                        // linkEdit.appendChild(document.createTextNode(' Modifier'));
                        linkEdit.style.display = "inline-block";
                        linkEdit.style.marginRight = "10px";
                        cell.appendChild(linkEdit);
                          
                  }else if(content == 'id_delete'){
                    var Id_delete = data[i][content];
                    var linkDelete = document.createElement("a");
                      // linkDelete.href = 'form-suppression.php?view =' + view + ' &verif = ' + Id_delete;
                    linkDelete.href = "#";
                      linkDelete.className = "btn btn-outline-danger btn-sm mr-2";
                      
                      var iconDelete = document.createElement('i');
                          iconDelete.className = 'bi bi-trash';
                          linkDelete.appendChild(iconDelete);
                          linkDelete.dataset.id = Id_delete;
                          // linkDelete.appendChild(document.createTextNode(' Supprimer'));

                          linkDelete.style.display = "inline-block";
                    linkDelete.addEventListener('click', function(event){
                      event.preventDefault();

                      const id = this.dataset.id;
                       const myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                       myModal.show();

                      document.getElementById('confirmerSuppression').addEventListener('click', () => {
                        myModal.hide();
                      fetch('action_delete.php?view=' + view + '&verif=' + Id_delete,{method :'DELETE'})
                      .then(response => response.json())
                      .then(data => { 
                        if(data.success) {
                          this.parentNode.parentNode.remove();
                          showPopup(data.message,'danger');
                        }else {
                          showPopup("Erreur lors de la suppression&nbsp;: " + data.message);
                        }
                      })
                      .catch(error => {
                        console.error("Erreur AJAX&nbsp;:", error);
                        alert("Erreur lors de la suppression&nbsp;: Veuillez réessayer.");
                      });
                    });
                  });
                  cell.appendChild(linkDelete);

                          
                  }else if (content == 'img_profil') {
                    var imgsrc = data[i][content];

                    var img = document.createElement("img");
                        if (imgsrc === null || imgsrc === undefined || imgsrc === "") {
                              img.src = "../assets/img/photoProfil/dafault.png";
                              img.alt = "profil";
                              img.style.width = "100px";
                              console.log(imgsrc);
                          } else {
                              img.src =  "../assets/img/photoProfil/" + imgsrc;
                              img.alt = "profil";
                              img.style.width = "100px";
                          }
                      cell.appendChild(img);
                  }else if(content == 'autre_img') {
                    var imgsrc = data[i][content];
                    var img = document.createElement("img");
                    if (imgsrc === null || imgsrc === undefined || imgsrc === "") {
                              img.src = "../assets/img/photoAffiche/dafault.png";
                              img.alt = "profil";
                              img.style.width = "100px";
                          } else {
                              img.src =  "../assets/img/photoAffiche/" + imgsrc;
                              img.alt = "profil";
                              img.style.width = "100px";
                          }
                          cell.appendChild(img);
                  }
                  else {
                      cell.innerHTML = data[i][content];
                  }
              });
          }

          var style = document.createElement('style');
              style.innerHTML = `
                  .centered-row {
                      text-align: center; /* Centre le contenu horizontalement dans la ligne */
                  }
                  .centered-cell {
                      text-align: center; /* Centre le contenu horizontalement dans la cellule */
                      vertical-align: middle; /* Centre le contenu verticalement dans la cellule */
                  }
                  .centered-cell img {
                      vertical-align: middle; /* Centre l'image verticalement */
                      display: block; /* Important pour le centrage */
                      margin: 0 auto; /* Centre l'image horizontalement */
                      max-width: 50px; /* Empêche l'image de dépasser la largeur de la cellule */
                      height: auto;     /* Maintient le ratio d'aspect */
                  }
              `;
              document.head.appendChild(style);
      }



        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                fetchData(currentPage);
            }
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                fetchData(currentPage);
            }
        }

        document.getElementById("search_notif").addEventListener("keyup", function() {
            currentPage = 1; // Reset to the first page
            fetchData(currentPage);
        });

        function showPopup(message, type) { 
            $('#popup').text(message).addClass(type).show(); 
            setTimeout(function() { 
            $('#popup').hide().removeClass('success').removeClass('danger'); 
          }, 5000); 
        }
        fetchData(currentPage);