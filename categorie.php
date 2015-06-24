<?php
session_start();

require_once 'config.php';
require_once 'connect.php';
require_once 'fonctions.php';

// si tentative de connexion
if (isset($_POST['lelogin'])) {
    $lelogin = traite_chaine($_POST['lelogin']);
    $lemdp = traite_chaine($_POST['lemdp']);

    // vérification de l'utilisateur dans la db
    $sql = "SELECT  u.id, u.lemail, u.lenom, 
            d.lenom AS nom_perm, d.laperm 
            FROM utilisateur u
            INNER JOIN droit d ON u.droit_id = d.id
            WHERE u.lelogin='$lelogin' AND u.lepass = '$lemdp';";
    $requete = mysqli_query($mysqli, $sql)or die(mysqli_error($mysqli));
    $recup_user = mysqli_fetch_assoc($requete);

    // vérifier si on a récupèré un utilisateur
    if (mysqli_num_rows($requete)) { // vaut true si 1 résultat (ou plus), false si 0
        // si l'utilisateur est bien connecté
        $_SESSION = $recup_user; // transformation des résultats de la requête en variable de session
        $_SESSION['sid'] = session_id(); // récupération de la clef de session
        $_SESSION['lelogin'] = $lelogin; // récupération du login (du POST après traitement)
        // var_dump($_SESSION);
        // redirection vers la page d'accueil (pour éviter les doubles connexions par F5)
        header('location: ' . CHEMIN_RACINE);
    }
}



// récupérations des images dans la table photo
$sql = "SELECT p.lenom,p.lextension,p.letitre,p.ladesc, 
        GROUP_CONCAT(r.id), 
        GROUP_CONCAT(r.lintitule SEPARATOR '~~') AS lintitule 
        FROM photo p
        INNER JOIN utilisateur u ON u.id = p.utilisateur_id
        LEFT JOIN photo_has_rubriques h ON h.photo_id = p.id
        LEFT JOIN rubriques r ON h.rubriques_id = r.id
        GROUP BY p.id
        ORDER BY p.id DESC; 
        ";
$recup_sql = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Telepro-photos.fr</title>
        <link rel="stylesheet" href="style.css" />
        <script src="monjs.js"></script>
        <script type="text/javascript" src="menu.js"></script>


    </head>
    <body>
        <div id="content">
            <div id="haut"><h1>Telepro-photos.fr</h1> 
            </div>

            <div>
                <ul id="menu">

                    <li>
                        <a href="index.php">Accueil</a>
                    </li>

                    <li>
                        <a href="categorie.php">Catégories</a>
                        <ul>
                            <li><a href="#">Animaux</a></li>
                            <li><a href="#">Architectures</a></li>
                            <li><a href="#">Artistiques</a></li>
                            <li><a href="#">Personnes</a></li>
                            <li><a href="#">Paysages</a></li>
                            <li><a href="#">Sports</a></li>
                            <li><a href="#">Technologies</a></li>
                            <li><a href="#">Divers</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="contact.php">Nous contacter</a>
                    </li>

                    <li>
                        <a href="#">Espace Client</a>
                    </li>
                </ul>
                <div/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>





                <div id="milieu">
                    <?php
// affichez les miniatures de chaques photos dans la db par id Desc, avec le titre au dessus et la description en dessous, et affichage de la grande photo dans une nouvelle fenêtre lors du clic, Bonus : afficher lelogin de l'auteur de l'image
                    while ($ligne = mysqli_fetch_assoc($recup_sql)) {
                        echo "<div class='miniatures'>";
                        echo "<h4>" . $ligne['letitre'] . "</h4>";
                        echo "<a href='" . CHEMIN_RACINE . $dossier_gd . $ligne['lenom'] . ".jpg' target='_blank'><img src='" . CHEMIN_RACINE . $dossier_mini . $ligne['lenom'] . ".jpg' alt='' /></a><br/>";
                        $explose_rub = explode('~~', $ligne['lintitule']);
                        $explose_id = explode(',', $ligne['rubid']);
                        foreach ($explose_rub AS $clef => $valeur) {
                            echo "<a href='?section=" . $explose_id[$clef] . "'>";
                            echo $valeur . "</a><br/>";
                        }
                        echo "<p>" . $ligne['ladesc'] . "<br /> par <strong>" . $ligne['lelogin'] . "</strong></p>";
                        echo "</div>";
                    }
                    ?> 
                </div>
                
                
                
                
                <div id="bas"></div>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <div id="connect">
                <?php
// si on est pas (ou plus) connecté
                if (!isset($_SESSION['sid']) || $_SESSION['sid'] != session_id()) {
                    echo "<h3>Bienvenu sur la catégorie</h3>"
                    ?>
                    <form action="" name="connection" method="POST">
                        <input type="text" name="lelogin" required />
                        <input type="password" name="lemdp" required />
                        <input type="submit" value="Connexion" />
                    </form>
                    <a href="mdp.php">Mot de passe oublié?</a>
                    <a href="inscription.php">Inscription</a>
                    
                    <?php
                    // sinon on est connecté
                } else {

                    // texte d'accueil
                    echo "<h3>Bienvenu " . $_SESSION['lenom'] . '</h3>';
                    echo "<p>Vous êtes connecté en tant que <span title='" . $_SESSION['lenom'] . "'>" . $_SESSION['nom_perm'] . "</span></p>";
                    echo "<h5><a href='deconnect.php'>Déconnexion</a></h5>";

                    // liens  suivant la permission utilisateur
                    switch ($_SESSION['laperm']) {
                        // si on est l'admin
                        case 0 :
                            echo "<a href='admin.php'>Administrer le site</a> - <a href='membre.php'>Espace membre</a>";
                            break;
                        // si on est modérateur
                        case 1:
                            echo "<a href='modere.php'>Modérer le site</a> - <a href='membre.php'>Espace membre</a>";
                            break;
                        // si autre droit (ici simple utilisateur)
                        default :
                            echo "<a href='membre.php'>Espace membre</a>";
                    }
                }
                ?>
            </div>
    </body>
</html>



