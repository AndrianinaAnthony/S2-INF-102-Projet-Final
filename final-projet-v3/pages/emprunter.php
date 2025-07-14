<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

$id_objet = isset($_GET['id_objet']) ? intval($_GET['id_objet']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jours = intval($_POST['jours']);
    if ($jours > 0) {
        $id_user = $_SESSION['id_user'];
        $date_emprunt = date('Y-m-d');
        $date_retour = date('Y-m-d', strtotime("+$jours days"));

        $sql = "INSERT INTO emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour)
                VALUES ($id_objet, $id_user, '$date_emprunt', '$date_retour')";
        mysqli_query($bdd, $sql);

        header('Location: accueil.php');
        exit();
    } else {
        $erreur = "Veuillez saisir un nombre de jours valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter l'objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h3 class="mb-3">Emprunter l'objet</h3>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="jours" class="form-label">Nombre de jours à emprunter</label>
            <input type="number" min="1" id="jours" name="jours" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Valider l'emprunt</button>
    </form>

    <a href="accueil.php" class="btn btn-link mt-3">Annuler</a>
</div>

</body>
</html>
