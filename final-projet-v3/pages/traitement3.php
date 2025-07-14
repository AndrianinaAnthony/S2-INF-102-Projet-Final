<?php
session_start();
require('../inc/fonction.php');
require('../inc/connexion.php');

$bdd = dbconnect();
$_SESSION['img_dflt'] = "dflt.png";

$uploadDir = __DIR__ . '/../assets/uploads/';
$maxSize = 20 * 1024 * 1024;
$allowedMimeTypes = ['image/jpg', 'image/jpeg', 'image/png', 'video/mp4', 'video/mp3'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = $originalName . '_' . uniqid() . '.' . $extension;
    $fullPath = $uploadDir . $newName;

    if (move_uploaded_file($file['tmp_name'], $fullPath)) {
        $nom_objet = $_POST['nom_objet'];
        $id_categorie = $_SESSION['categ'];
        $nom_image = $newName;
        $id_membre = $_SESSION['id_user'];

        ajout_objet($bdd, $nom_objet, $id_categorie, $nom_image, $id_membre);
        header('Location: accueil.php');
        exit();
    } else {
        echo "Erreur : Impossible de déplacer le fichier.";
    }
} else {
    header('Location: entrer_objet.php?erro5=0');
    exit();
}
