<?php
    function dbconnect(){
        $bdd = mysqli_connect('localhost', 'ETU004108', 'Dy7ti7no', 'db_s2_ETU004108');
        return $bdd;
    }
    // function dbconnect(){
    //     $bdd = mysqli_connect('localhost', 'root', '', 'partage_objets');
    //     return $bdd;
    // }

?>