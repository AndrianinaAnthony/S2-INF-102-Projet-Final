<?php
    function dbconnect(){
        $bdd = mysqli_connect('localhost', 'root', '', 'partage_objets');
        return $bdd;
    }
?>