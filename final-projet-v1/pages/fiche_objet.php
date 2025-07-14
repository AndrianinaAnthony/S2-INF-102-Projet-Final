<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();

$id_objet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT 
            emprunt_objet.nom_objet,
            emprunt_objet.id_objet,
            emprunt_membre.nom AS nom_membre,
            emprunt_categorie_objet.nom_categorie,
            emprunt_images_objet.nom_image,
            emprunt_emprunt.date_retour
        FROM emprunt_objet
        INNER JOIN emprunt_membre 
            ON emprunt_objet.id_membre = emprunt_membre.id_membre
        INNER JOIN emprunt_categorie_objet 
            ON emprunt_objet.id_categorie = emprunt_categorie_objet.id_categorie
        LEFT JOIN emprunt_images_objet 
            ON emprunt_objet.id_objet = emprunt_images_objet.id_objet
        LEFT JOIN emprunt_emprunt 
            ON emprunt_objet.id_objet = emprunt_emprunt.id_objet
        WHERE emprunt_objet.id_objet = $id_objet
        LIMIT 1";

$resultat = mysqli_query($bdd, $sql);

if (!$resultat) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "❌ Erreur SQL : " . mysqli_error($bdd);
    echo "</div>";
    echo "<pre>$sql</pre>"; 
    exit();
}

$objet = mysqli_fetch_assoc($resultat);

if (!$objet) {
    echo "Objet introuvable.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de l'objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">

    <div class="card shadow mx-auto" style="max-width: 600px;">
        <?php if (!empty($objet['nom_image'])) { ?>
            <img src="../uploads/<?php echo $objet['nom_image']; ?>" class="card-img-top" alt="Image de l'objet">
        <?php } ?>

        <div class="card-body">
            <h3 class="card-title"><?php echo $objet['nom_objet']; ?></h3>

            <p class="card-text">
                <strong>Catégorie :</strong> <?php echo $objet['nom_categorie']; ?><br>
                <strong>Ajouté par :</strong> <?php echo $objet['nom_membre']; ?><br>
                <strong>Statut :</strong> 
                <?php
                    if ($objet['date_retour']) {
                        echo "<span class='text-warning'>Emprunté jusqu’au " . $objet['date_retour'] . "</span>";
                    } else {
                        echo "<span class='text-success'>Disponible</span>";
                    }
                ?>
            </p>

            <a href="accueil.php" class="btn btn-secondary mt-3">← Retour à l'accueil</a>
        </div>
    </div>

</div>
</body>
</html>
