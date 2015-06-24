<?php  
    define( 'MAIL_TO','romuald.baldy@gmail.com' );    
    define( 'MAIL_FROM', 'votre adresse mail' );  
    define( 'MAIL_OBJECT', 'objet du message' );
    define( 'MAIL_MESSAGE', 'votre message' ); 

    $mailSent = false;   
    $errors = array();  
      
    if( filter_has_var( INPUT_POST, 'send' ) )  
    {  
        $from = filter_input( INPUT_POST, 'from', FILTER_VALIDATE_EMAIL );  
        if( $from === NULL || $from === MAIL_FROM )   
        {  
            $errors[] = 'Vous devez renseigner votre adresse de courrier électronique.';  
        }  
        elseif( $from === false )  
        {  
            $errors[] = 'L\'adresse de courrier électronique n\'est pas valide.';  
            $from = filter_input( INPUT_POST, 'from', FILTER_SANITIZE_EMAIL );  
        }  

        $object = filter_input( INPUT_POST, 'object', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW );  
        if( $object === NULL OR $object === false OR empty( $object ) OR $object === MAIL_OBJECT ) 
        {  
            $errors[] = 'Vous devez renseigner l\'objet.';  
        }  

 
        $message = filter_input( INPUT_POST, 'message', FILTER_UNSAFE_RAW );  
        if( $message === NULL OR $message === false OR empty( $message ) OR $message === MAIL_MESSAGE )
        {  
            $errors[] = 'Vous devez écrire un message.';  
        }  

        if( count( $errors ) === 0 )  
        {  
            if( mail( MAIL_TO, $object, $message, "From: $from\nReply-to: $from\n" ) )   
            {  
                $mailSent = true;  
            }  
            else  
            {  
                $errors[] = 'Votre message n\'a pas &eacute;t&eacute; envoy&eacute;.';  
            }  
        }  
    }  
    else   
    {  
        $from = MAIL_FROM;  
        $object = MAIL_OBJECT;  
        $message = MAIL_MESSAGE;  
    }  
?>  
<!DOCTYPE>  
<html>  
    <head>  
        <title>Contact</title>  
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />    
        <style>  
html{ font-family:Arial; margin:0; padding:0; font-size:.88em;}  
body{ width:772px; margin:0 auto; padding:0; }  
textarea{ width:772px; }  
label{ display:block; font-weight:bold; }  
p#welcome{ padding:10px 20px; border:1px dotted #00f; color:#00f; font-weight:bold; }  
/*ul{ padding:10px 20px; border:1px dotted #f00; color:#f00; font-weight:bold; } */
p#success{ padding:10px 20px; border:1px dotted #0f0; color:#0f0; font-weight:bold; }  
p em{ display:block; font-weight:normal; }  
        </style>  
        <script type="text/javascript" src="menu.js"></script>
        <link rel="stylesheet" href="style.css" />
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
        <h1>Contact</h1>  
        <hr />  
<?php  
    if( $mailSent === true ) 
    {  
?>  
        <p id="success">Votre message a bien été envoyé.</p>  
        <p><strong>Courriel pour la r&eacute;ponse :</strong><br /><?php echo( $from ); ?></p>  
        <p><strong>Objet :</strong><br /><?php echo( $object ); ?></p>  
        <p><strong>Message :</strong><br /><?php echo( nl2br( htmlspecialchars( $message ) ) ); ?></p>  
<?php  
    }  
    else  
    {  
        if( count( $errors ) !== 0 )  
        {  
            echo( "\t\t<ul>\n" );  
            foreach( $errors as $error )  
            {  
                echo( "\t\t\t<li>$error</li>\n" );  
            }  
            echo( "\t\t</ul>\n" );  
        }  
        else  
        {  
            echo( "\t\t<p id=\"welcome\"><em>Tous les champs sont obligatoires</em></p>\n" );  
        }  
?>  
        <form id='contact' method="post" action="<?php echo( $_SERVER['REQUEST_URI'] ); ?>">  
            <p>  
                <label for="from">Courriel pour la r&eacute;ponse</label>  
                <input type="text" name="from" id="from" value="<?php echo( $from ); ?>" />  
            </p>  
            <p>  
                <label for="object">Objet</label>  
                <input type="text" name="object" id="object" value="<?php echo( $object ); ?>" />  
            </p>   
            <p>  
                <label for="message">Message (maximum 500 caractères)</label>  
                <textarea name="message" id="message" rows="20" cols="80" maxlength="500"><?php echo( $message ); ?></textarea>  
            </p>  
            <p>  
                <input type="reset" name="reset" value="Effacer" />  
                <input type="submit" name="send" value="Envoyer" />  
            </p>  
        </form>  
<?php  
    }  
?>  
    </body>  
</html>