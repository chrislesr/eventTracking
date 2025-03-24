// $(document).ready(function () {
//     $("#btnSCAnning").on('click', function() {

//         $('#search_text_rfid').load("../DatacontentUID.php");
//         setInterval(function() {
//             $('#search_text_rfid').load("../DatacontentUID.php");
//         },500);
//     });
//     })
   // $(document).ready(function() {
   //  $("#btnSCAnning").on('click', function() {
   //     setInterval(function() {
   //         $.get("../DatacontentUID.php", function(data) {
   //             $('#search_text_rfid').val(data);
   //         });
   //     }, 500);
   // });
   // });

$(document).ready(function() {
    let intervalID;
    $("#btnSCAnning").on('click', function() {
        if (intervalID) {
            clearInterval(intervalID);
            intervalID = null;
            $('#search_text_rfid').val(''); 
        } else {
            intervalID = setInterval(function() {
                $.get("../DatacontentUID.php", function(data) {
                    $('#search_text_rfid').val(data);
                });
            }, 500);
        }
    });
});



   
