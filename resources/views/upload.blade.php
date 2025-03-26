<!DOCTYPE html>
<html>
<head>
    <title>Dépôt de Fichier Excel</title>
</head>
<body>
    <h1>Déposer un Fichier Excel</h1>
    <form action="/upload-excel" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="excel_file">Choisir un fichier Excel :</label>
    <input type="file" name="excel_file" id="excel_file" required>
    <button type="submit">Envoyer</button>
    </form>

</body>
</html>
