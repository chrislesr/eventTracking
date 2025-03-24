<?php 
          if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            if($_SESSION['message_type'] == 'success'){
              echo '<div id="popup1" class="popup1 success">'.$_SESSION["message"].'</div>';
              unset($_SESSION['message']);
              unset($_SESSION['message_type']);
            }else{
              echo '<div id="popup1" class="popup1 error">'.$_SESSION["message"].'</div>';
              unset($_SESSION['message']);
              unset($_SESSION['message_type']);
            }
          }
        ?> 