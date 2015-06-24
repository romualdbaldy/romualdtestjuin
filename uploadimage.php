<?php
include("connect.php");

?>
<center>
<form action="addImage.php" method="POST">
    <label>Lien de l'image</label>
    <input type="text" name="lienImage"/>
    <input type="submit" value="Envoyer l'image"/>   
</form>
</center>

<?php
$requete="SELECT * FROM photo";
$exec=  mysqli_query($requete);

while($ligne=mysqli_fetch_array($exec))
        {
        ?>
        <img src="<?php echo $ligne['lien'];?>"/>
        <?php
        }
        ?>
?>