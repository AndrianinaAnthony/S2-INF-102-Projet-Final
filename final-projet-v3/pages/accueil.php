<?php
session_start();
require('../inc/fonction.php');
require('../inc/connexion.php');
$bdd = dbconnect();

if (!isset($_SESSION['id_user']) || !isset($_SESSION['nom_user'])) {
    header('Location: login.php');
    exit();
}

$nom_user = $_SESSION['nom_user'];

$sql_categorie = "SELECT * FROM emprunt_categorie_objet";
$resultat_categories_1 = mysqli_query($bdd, $sql_categorie); 
$resultat_categories_2 = mysqli_query($bdd, $sql_categorie);

$id_categorie = "";
if (isset($_GET['categorie'])) {
    $id_categorie = intval($_GET['categorie']); // protection basique
}

$sql = "SELECT emprunt_objet.*, emprunt_categorie_objet.nom_categorie, emprunt_emprunt.date_retour 
        FROM emprunt_objet
        INNER JOIN emprunt_categorie_objet 
            ON emprunt_objet.id_categorie = emprunt_categorie_objet.id_categorie
        LEFT JOIN emprunt_emprunt 
            ON emprunt_objet.id_objet = emprunt_emprunt.id_objet";

if ($id_categorie !== "") {
    $sql .= " WHERE emprunt_objet.id_categorie = $id_categorie";
}

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

$id_membre = $_SESSION['id_user'];
$nom_user = $_SESSION['nom_user'];

$objets_empruntes = get_objets_empruntes_par_membre($bdd, $id_membre);

$resultat_objets = mysqli_query($bdd, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Liste des objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Mes emprunts, <?php echo htmlspecialchars($nom_user); ?> 👋</h3>
        <a href="accueil.php" class="btn btn-secondary">← Retour à l'accueil</a>
    </div>

    <div class="card shadow p-4">
        <h4 class="mb-3">Liste des objets que vous avez empruntés</h4>

        <?php if (!$objets_empruntes || count($objets_empruntes) === 0) : ?>
            <p class="text-muted">Vous n'avez aucun objet emprunté pour le moment.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($objets_empruntes as $objet): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($objet['nom_objet']); ?></strong><br>
                            <small class="text-muted">Catégorie : <?php echo htmlspecialchars($objet['nom_categorie']); ?></small>
                        </div>
                        <span class="badge bg-warning">
                            Retour prévu le <?php echo htmlspecialchars($objet['date_retour']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

</div>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Bienvenue, <?php echo htmlspecialchars($nom_user); ?> 👋</h3>
        <a href="../inc/deconnexion.php" class="btn btn-outline-danger">Déconnexion</a>
    </div>

    <!-- Formulaire pour entrer un nouvel objet -->
    <div class="card shadow mb-4 p-4">
        <h5 class="mb-3">Entrer un nouvel objet dans la catégorie de votre choix</h5>
        <form method="get" action="entrer_objet.php">
            <div class="row">
                <div class="col-md-6">
                    <select name="categorie" class="form-select" required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        <?php while ($cat = mysqli_fetch_assoc($resultat_categories_2)) { ?>
                            <option value="<?php echo $cat['id_categorie']; ?>">
                                <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Ajouter un objet</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Formulaire de filtrage -->
    <div class="card shadow mb-4 p-4">
        <form method="get" action="accueil.php" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="categorie" class="form-label">Filtrer les objets par catégorie :</label>
                <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Toutes les catégories --</option>
                    <?php while ($cat = mysqli_fetch_assoc($resultat_categories_1)) { ?>
                        <option value="<?php echo $cat['id_categorie']; ?>" 
                            <?php if ($id_categorie == $cat['id_categorie']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Liste des objets -->
    <div class="card shadow p-4">
        <h4 class="mb-3">Objets disponibles</h4>
        <ul class="list-group">
            <?php while ($objet = mysqli_fetch_assoc($resultat_objets)) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <a href="fiche_objet.php?id=<?php echo $objet['id_objet']; ?>">
                            <strong><?php echo htmlspecialchars($objet['nom_objet']); ?></strong>
                        </a><br>
                        <small class="text-muted">Catégorie : <?php echo htmlspecialchars($objet['nom_categorie']); ?></small>
                    </div>
                    <div>
                        <?php if (empty($objet['date_retour'])): ?>
                            <form method="get" action="emprunter.php" class="d-inline">
                                <input type="hidden" name="id_objet" value="<?php echo $objet['id_objet']; ?>">
                                <button type="submit" class="btn btn-sm btn-success">Emprunter</button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-warning">
                                Emprunté jusqu’au <?php echo $objet['date_retour']; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

</body>
</html>
