<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code QR/Barre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <form action="page_action.php" method="post">
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="code_type" id="code_qr" value="qr" checked>
                <label class="form-check-label" for="code_qr">Code QR</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="code_type" id="code_barre" value="barre">
                <label class="form-check-label" for="code_barre">Code Barre</label>
            </div>
        </div>


        <div class="mb-3" id="qr_input">
            <label for="code_qr_value" class="form-label">Valeur du Code QR:</label>
            <input type="text" class="form-control" id="code_qr_value" name="code_value">
        </div>

        <div class="mb-3 d-none" id="barre_input">
            <label for="code_barre_value" class="form-label">Valeur du Code Barre:</label>
            <input type="text" class="form-control" id="code_barre_value" name="code_value">
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>

<script>
    const qrRadio = document.getElementById('code_qr');
    const barreRadio = document.getElementById('code_barre');
    const qrInput = document.getElementById('qr_input');
    const barreInput = document.getElementById('barre_input');

    function toggleInputs() {
        if (qrRadio.checked) {
            qrInput.classList.remove('d-none');
            barreInput.classList.add('d-none');

        } else if (barreRadio.checked) {
            qrInput.classList.add('d-none');
            barreInput.classList.remove('d-none');
        }
    }



    qrRadio.addEventListener('change', toggleInputs);
    barreRadio.addEventListener('change', toggleInputs);

    // Appel initial pour afficher le bon champ au chargement de la page
    toggleInputs();

</script>

</body>
</html>
