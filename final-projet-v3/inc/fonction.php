<?php
    function verifier_utilisateur($bdd, $email, $mdp){
        $sql="SELECT * FROM emprunt_membre WHERE email='%s' AND mdp ='%s'";
        $sql = sprintf($sql, $email, $mdp);
        $resultat= mysqli_query($bdd, $sql);
        return mysqli_fetch_assoc($resultat);
    }

    function connecter_utilisateur($user)
{
    $_SESSION['nom_user'] = $user['nom'];
    $_SESSION['id_user'] = $user['id_membre'];
}

function ajout_objet($bdd, $nom_objet, $id_categorie, $chemin_img, $id_membre,$img_dflt) {
    $sql_objet = "INSERT INTO emprunt_objet(nom_objet, id_categorie, id_membre)
                  VALUES ('$nom_objet', $id_categorie, $id_membre)";
    mysqli_query($bdd, $sql_objet);

    $id_objet = mysqli_insert_id($bdd);

    if ($chemin_img !== "") {
        $sql_image = "INSERT INTO emprunt_images_objet(id_objet, nom_image)
                      VALUES ($id_objet, '$chemin_img')";
        mysqli_query($bdd, $sql_image);
    }
    else{
        $sql_image = "INSERT INTO emprunt_images_objet(id_objet, nom_image)
                      VALUES ($id_objet, '$img_dflt')";
        mysqli_query($bdd, $sql_image);
    }
}

function get_objets_empruntes_par_membre($bdd, $id_membre) {
    // Sécuriser $id_membre en le castant en entier
    $id_membre = intval($id_membre);

    $sql = "SELECT eo.id_objet, eo.nom_objet, eco.nom_categorie, ee.date_retour
            FROM emprunt_emprunt ee
            INNER JOIN emprunt_objet eo ON ee.id_objet = eo.id_objet
            INNER JOIN emprunt_categorie_objet eco ON eo.id_categorie = eco.id_categorie
            WHERE ee.id_membre = $id_membre
            ORDER BY ee.date_retour DESC";

    $result = mysqli_query($bdd, $sql);
    if (!$result) {
        return false; // Ou gérer l'erreur autrement
    }

    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }

    return $objets;
}


?>
