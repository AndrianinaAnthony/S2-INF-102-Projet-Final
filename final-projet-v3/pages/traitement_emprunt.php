<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

$id_membre = $_SESSION['id_user'];
$id_objet = isset($_POST['id_objet']) ? intval($_POST['id_objet']) : 0;
$nb_jours = isset($_POST['nb_jours']) ? intval($_POST['nb_jours']) : 0;

if ($id_objet > 0 && $nb_jours > 0) {
    $date_emprunt = date('Y-m-d');
    $date_retour = date('Y-m-d', strtotime("+$nb_jours days"));

    $sql = "INSERT INTO emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour)
            VALUES ($id_objet, $id_membre, '$date_emprunt', '$date_retour')";

    mysqli_query($bdd, $sql);
}

header('Location: accueil.php');
exit();
