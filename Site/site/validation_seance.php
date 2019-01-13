<html>
<head>
    <title>Validation d’une séance</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Valider une séance</h1>
    <?php
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    echo "Quelle séance voulez-vous valider&nbsp?<br><br>";
    
    //récupération des séances passées
    date_default_timezone_set('Europe/Paris');
    $date = date("Y\-m\-d");
    $result_seances = mysqli_query($connect, "SELECT * FROM `seances` WHERE `dateSeance` <= '$date';");
    
    //création d'un formulaire
    echo "<form action='valider_seance.php' method='POST'>";
    echo "<table>";
    
    //sélection de la séance
    echo "<tr><td>";
    echo "Séance de code&nbsp:</td><td>";
    echo "<select name='menuChoixSeance' size='4' required>";
    
    //affichage du résultat ligne par ligne, soit toutes les séances, dans une liste déroulante
    while ($row = mysqli_fetch_array($result_seances, MYSQL_NUM)) {
        //récupération du thème de la séance, row[0] est l'id du thème de cette séance
        $result_themes = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE themes.idtheme='$row[3]';");
        while ($theme = mysqli_fetch_array($result_themes, MYSQL_NUM)) {
            echo "<option value='$row[0]'>Séance du $row[1] sur le thème $theme[0]</option>";
        }
    }
    echo "</select></td></tr>";
    
    echo "</table>";
    
    echo "<br>";
    
    echo "<input type='submit' value='Passer à la saisie des notes'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
