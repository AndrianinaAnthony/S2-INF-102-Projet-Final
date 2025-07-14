<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();

// Récupération des catégories
$sql = "SELECT * FROM emprunt_categorie_objet";
$resultat_cat = mysqli_query($bdd, $sql);
$_SESSION['categ']= $_POST['id_categorie'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow p-4" style="max-width: 600px; margin: auto;">
        <h3 class="mb-4 text-center">Ajouter un objet</h3>

        <form method="post" action="traitement3.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet</label>
                <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
            </div>

            <div class="mb-3">
                <label for="image_objet" class="form-label">Image de l'objet</label>
                <input type="file" class="form-control" id="image_objet" name="fichier">
            </div>

            <button type="submit" class="btn btn-primary w-100">Ajouter l'objet</button>
        </form>

        <div class="mt-3 text-center">
            <a href="accueil.php" class="text-decoration-none">← Retour à l'accueil</a>
        </div>
    </div>
</div>

</body>
</html>
