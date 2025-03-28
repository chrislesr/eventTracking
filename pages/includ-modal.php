<!-- Modal -->
    <?php if($_SESSION['niveau']=='LEVEL_NIV4'){?>
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Notifications</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="col-lg-6" hidden>
                <input type="text" id="search_notif" class="form-control"  placeholder="Rechercher..."><br>
                <input type="hidden" id="control-notif" value="notification">
              </div>
              
              <table id="myTableNotification" class="table table-scrollable" width="100%">
                <thead class="bg-light text-center">
                  <tr>
                    <th>Image</th>
                    <th>Nom Participant</th>
                    <th>Etat</th>
                    <th>Ops Comite</th>
                    <th>Ops Organisateur</th>
                    <th>Date d'enrégistrement</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div id="noResultsRow" style="display:none;" class="no-results mt-3">Aucun élément trouvé</div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END Modal -->
  <?php }?>