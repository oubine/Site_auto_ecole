<html>
<head>
    <title>Désinscription d’un⋅e élève</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Choisir une inscription à annuler</h1>
    <?php
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //récupération des élèves
    $eleves = mysqli_query($connect, "SELECT * FROM `eleves`;");
    
    //récupération des séances futures
    date_default_timezone_set('Europe/Paris');
    $date = date("Y\-m\-d");
    $seances = mysqli_query($connect, "SELECT * FROM `seances` WHERE `dateSeance` >= '$date';");
    
    //création d'un formulaire
    echo "<form action='desinscrire_seance.php' method='POST'>";
    
    echo "<table>";
    
    //sélection de la séance
    echo "<tr><td>Séances&nbsp:</td><td>";
    echo "<select name='menuChoixSeance' size='4' required>";
    
    //affichage du tableau des seances futures dans une liste déroulante, ligne par ligne
    while ($row = mysqli_fetch_array($seances, MYSQL_NUM)) {
        //récupération du thème de la séance, row[0] est l'id du thème de cette séance
        $result_themes = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE themes.idtheme='$row[3]';");
        while ($theme = mysqli_fetch_array($result_themes, MYSQL_NUM)) {
            echo "<option value='$row[0]'>Séance du $row[1] sur le thème $theme[0]</option>";
        }
    }
    
    echo "</td></tr>";
    
    echo "<tr><td>Élèves&nbsp:</td>";
    //affichage de tous les élèves dans une liste déroulante
    echo "<td><select name='menuChoixEleve' size='4' required>";
    while ($row_el = mysqli_fetch_array($eleves, MYSQL_NUM)) {
        echo "<option value='$row_el[0]'>$row_el[2] $row_el[1] ($row_el[0])</option>";
    }
    echo "</select></td></tr><br>";
    
    echo "</table>";
    
    echo "<br><input type='submit' value='Désinscrire cet⋅te élève de cette séance'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
