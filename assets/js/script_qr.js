 $(document).ready(function() {
        let isScanning = false;
        let html5QrCode;

        $('#btn-search-clear').on('click', function() {
            $('#search_text').val('');
            $('#result').html('');
            $('#result').hide();
            $('#results').hide();
        });

$('#btn-search-qr').on('click', function() {
    $('#alert_camera').hide().removeClass('success').removeClass('danger'); 
    if (!isScanning) {
        $('#qr-reader').show();
        html5QrCode = new Html5Qrcode("qr-reader");

        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        html5QrCode.start(
            isMobile ? { facingMode: { exact: "environment"} } : { facingMode: "environment"},
            {
                fps: 10,
                qrbox: 250
            },
            qrCodeMessage => {
                $('#search_text').val(qrCodeMessage);
                $('#qr-reader').hide();
                html5QrCode.stop();
                isScanning = false;
                $('#search_text').trigger('input');
                $('#alert_camera').hide().removeClass('success').removeClass('danger'); 

            },
            errorMessage => {
                showPopup1("Le QR Code n'est plus devant la caméra.", 'danger');
            }
        ).catch(err => {
            showPopup1(`Impossible de démarrer la numérisation, error: ${err}`,'danger');
        });
        isScanning = true;
    } else {
        html5QrCode.stop().then(() => {
                $('#qr-reader').hide();
                isScanning = false;
            }).catch(err => {
               showPopup1(`Impossible d'arrêter la numérisation, error: ${err}`,'danger');
            });
        }
    });
function showPopup1(message, type) { 
    $('#alert_camera').text(message).addClass(type).show(); 
     
}

    });

    function clearSearch() {
    $('#search_text').val('');
    $('#result').html('');
    $('#result').hide(); 
    $('#results').hide();
}