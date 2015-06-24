<?php
include("connexion.php");


$lienImg = $_POST['lienImage'];

$requete="INSERT INTO photo VALUES('','$lienImg')";
$exec = mysqli_query($requete);

?>