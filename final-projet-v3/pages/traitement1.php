<?php
    require('../inc/fonction.php');
    require('../inc/connexion.php');
    
    $bdd = dbconnect();
    
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    
    $user = verifier_utilisateur($bdd,$email,$mdp);
    
    session_start();
    if ($user) {
    $_SESSION['nom_user'] = $user['nom'];
    $_SESSION['id_user'] = $user['id_membre'];
        connecter_utilisateur($user);
        header('Location: accueil.php');
        exit();
    }
    
    header('Location: accueil.php');
    exit();
    
    
?>