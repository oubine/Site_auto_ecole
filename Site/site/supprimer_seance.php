<html>
<head>
    <title>Suppression de cette inscription</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <?php
    //récupération des informations
    $seance = $_POST['menuChoixSeance'];
    
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //il faut reconstruire les informations de la séance
    $result_seance = mysqli_query($connect, "SELECT * FROM `seances` WHERE `idseance` = '$seance';");
    while ($row = mysqli_fetch_array($result_seance, MYSQL_NUM)) {
        $date_seance = $row[1];
        $theme = $row[3];
    }
    
    //puis du thème
    $result_theme = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE `themes`.`idtheme`='$theme';");
    $nom_theme = mysqli_fetch_array($result_theme, MYSQL_NUM)[0];
    
    //récapitulatif
    echo "Vous voulez supprimer la séance du $date_seance sur le thème $nom_theme.<br>";
    
    //désinscription des élèves
    echo "<br>Pour cela, il faut d’abord supprimer toutes les inscriptions.<br>";
    $query = "DELETE FROM `$dbuser`.`inscriptions` WHERE `inscriptions`.`idseance` = '$seance';";
    echo "Cela sera fait à l’aide de la requête&nbsp:<p>$query</p>";

    $result = mysqli_query($connect, $query);
    if (!$result) {
        echo "Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
        echo "<br><a href=\"suppression_seance.php\">Retourner à l’annulation d’une séance.</a>";
    }
    else {
        echo "<br>Toutes les inscriptions ont bien été supprimées.<br>";
    
        //suppression du tuple de la séance
        $query = "DELETE FROM `$dbuser`.`seances` WHERE `seances`.`idseance` = '$seance';";

        echo "<br>Comme aucune inscription ne dépend de cette séance, elle peut être supprimée grâce à la requête&nbsp:<p>$query</p>";
        $result = mysqli_query($connect, $query);
    
        if (!$result) {
            echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
            echo "<br><a href=\"suppression_seance.php\">Retourner à l’annulation d’une séance.</a>";
        }
        else {
            echo "<br>Vous venez bien d’annuler la séance du $date_seance sur le thème $nom_theme.<br>";
            echo "<br><a href=\"suppression_seance.php\">Supprimer une autre séance.</a>";
        }
    }
    
    //on referme
    mysqli_close($connect);
    ?>
</body>
</html>
