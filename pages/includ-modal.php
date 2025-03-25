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
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nom Participant</th>
                    <th>Opérateur</th>
                    <th>Date d'enrégistrement</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                   $result = getNotifications($id_evenement,$conn);
                      foreach ($result as $aff) {
                            if(empty($aff['id_util_ops_part_fk'])){
                              $id_ops = $aff['id_cmt_osp_part_fk'];
                              $result1 = getComiteAll($id_ops,$conn);
                            }else{
                              $id_ops = $aff['id_util_ops_part_fk'];
                              $result1 = getUsersAll($id_ops,$conn);
                            }
                        foreach($result1 as $aff1){
                           echo '<tr>';
                           echo '<td>'.$aff['nom_part'].'</td>';
                           echo '<td>'.$aff1['ops'].'</td>';
                           echo '<td>'.date('d-m-Y H:i:s',strtotime($aff['date_enreg_part'])).'</td>';
                           echo '</tr>';
                          }
                      }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END Modal -->
  <?php }?>