<!DOCTYPE html>
<html>
<head>
    <title>Nouveau Mot de Passe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Le mot de passe a été modifié</h1>
        <p>Nouveau mot de passe : {{ $motDePasse }}</p>
        <a href="/lyceens" class="btn btn-primary">Retour à la liste des lycéens</a>
    </div>
</body>
</html>
