<?php
    function verifier_utilisateur($bdd, $email, $mdp){
        $sql="SELECT * FROM emprunt_membre WHERE email='%s' AND mdp ='%s'";
        $sql = sprintf($sql, $email, $mdp);
        $resultat= mysqli_query($bdd, $sql);
        return mysqli_fetch_assoc($resultat);
    }

    function connecter_utilisateur($user)
{
    $_SESSION['nom_user'] = $user['Nom'];
    $_SESSION['id_user'] = $user['id_membre'];
}
function ajout_objet($bdd, $nom_objet, $id_categorie, $chemin_img, $id_membre,$img_dflt) {
    $sql_objet = "INSERT INTO objet(nom_objet, id_categorie, id_membre)
                  VALUES ('$nom_objet', $id_categorie, $id_membre)";
    mysqli_query($bdd, $sql_objet);

    $id_objet = mysqli_insert_id($bdd);

    if ($chemin_img !== "") {
        $sql_image = "INSERT INTO images_objet(id_objet, nom_image)
                      VALUES ($id_objet, '$chemin_img')";
        mysqli_query($bdd, $sql_image);
    }
    else{
        $sql_image = "INSERT INTO images_objet(id_objet, nom_image)
                      VALUES ($id_objet, '$img_dflt')";
        mysqli_query($bdd, $sql_image);
    }
}

?>
