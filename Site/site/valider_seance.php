<html>
<head>
    <title>Notation des élèves</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Saisie des nombres de fautes effectuées par chaque élève lors de cette séance</h1>
    <?php
    
    //récupération de la séance
    $seance = $_POST['menuChoixSeance'];
    
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //on récupère le thème et la date de la séance
    $result_seances = mysqli_query($connect, "SELECT * FROM `seances` WHERE `idSeance` = '$seance';");
    while ($row = mysqli_fetch_array($result_seances, MYSQL_NUM)) {
        $date = $row[1];
        $theme = $row[3];
    }
    
    $result_theme = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE themes.idtheme='$theme';");
    $nom_theme = mysqli_fetch_array($result_theme, MYSQL_NUM)[0];
    
    //récapitulatif
    echo "Vous remplissez les résultats obtenus à la séance du $date sur le thème $nom_theme.<br>";
    
    //récupération des élèves inscrits à cette séance, on sélectionne les élèves dont l'id est dans le tableau des résultats de la imbriquée
    $eleves = mysqli_query($connect, "SELECT * FROM `eleves` WHERE `ideleve` IN (SELECT `ideleve` FROM `inscriptions` WHERE inscriptions.idseance='$seance');");
    
    if ($eleves->num_rows == 0) {
        echo "<br>Aucun⋅e élève n’était inscrit⋅e, il n’y a rien à faire.<br>";
        echo "<br><a href=\"validation_seance.php\">Valider une autre séance.";
    }
    
    else {
        //création d'un formulaire
        echo "<form action='noter_eleves.php' method='POST'>";
        echo "<table>";
        
        //affichage de tous les élèves dans un tableau avec des champs pour le nombre de fautes
        while ($row_el = mysqli_fetch_array($eleves, MYSQL_NUM)) {
            echo "<tr><td>$row_el[2] $row_el[1] ($row_el[0])</td>";
            //récupération d'une inscription existante
            $result_inscr = mysqli_query($connect, "SELECT * FROM `inscriptions` WHERE `idseance` = '$seance' AND `ideleve` = '$row_el[0]';");
            while ($row_inscr = mysqli_fetch_array($result_inscr, MYSQL_NUM)) {
                if ($row_inscr[2] == -1) {
                    $note = '';
                }
                else {
                    $note = $row_inscr[2];
                }
                echo "<td><input type='number' name='note_el_$row_el[0]' value='$note' min='0' max='40' /></td></tr>";
            }
        }
        
        echo "</table>";
        
        echo "<input type='hidden' name='menuChoixSeance' value='$seance' />";
        
        echo "<br><input type='submit' value='Enregistrer les notes'>";
        echo "</form>";
    }
    
    mysqli_close($connect);
    ?>
</body>
