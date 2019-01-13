<html>
<head>
    <title>Suppression de cette inscription</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <?php
    //récupération des informations
    $eleve = $_POST['menuChoixEleve'];
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
    
    //et de l'élève
    $result_eleve = mysqli_query($connect, "SELECT * FROM `eleves` WHERE `eleves`.`ideleve` = '$eleve';");
    while ($row = mysqli_fetch_array($result_eleve, MYSQL_NUM)) {
        $nom = $row[1];
        $prenom = $row[2];
    }
    
    //on vérifie si cette inscription existait
    $inscription = mysqli_query($connect,"SELECT * FROM `inscriptions` WHERE `inscriptions`.`ideleve` = '$eleve' AND `inscriptions`.`idseance` = '$seance';");
    
    if (!($inscription->num_rows == 0)) { //cette inscription existait
        $query = "DELETE FROM `$dbuser`.`inscriptions` WHERE `inscriptions`.`ideleve` = '$eleve' AND `inscriptions`.`idseance` = '$seance';";
        echo "L’élève $prenom $nom était inscrit⋅e à la séance du $date_seance sur le thème $nom_theme. Iel sera désinscrit⋅e avec la requête&nbsp:<p>$query</p>";
        
        $result = mysqli_query($connect, $query);
        if (!$result) {
            echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
            echo "<br><a href=\"desinscription_seance.php\">Retourner à la désinscription d’un⋅e élève.</a>";
        }
        else {
            echo "<br>Vous venez bien de désinscrire $prenom $nom.<br>";
            echo "<br><a href=\"desinscription_seance.php\">Désinscrire un⋅e autre élève.</a>";
        }
    }
    
    else {
        echo "<br>L’élève $prenom $nom n'était pas inscrit⋅e à la séance du $date_seance sur le thème $nom_theme. Il n’y a rien à faire.<br>";
        echo "<br><a href=\"desinscription_seance.php\">Désinscrire un⋅e autre élève.</a>";
    }
    
    //on referme
    mysqli_close($connect);
    ?>
</body>
</html>
