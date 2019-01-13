<html>
<head>
    <title>Choix d’un⋅e élève</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Consulter le calendrier de cet⋅te élève</h1>
    <?php
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //récupération des élèves
    $eleves = mysqli_query($connect, "SELECT * FROM `eleves`;");
    
    //création d'un formulaire
    echo "<form action='visualiser_calendrier_eleve.php' method='POST'>";
    
    echo "<table>";
    echo "<tr><td>Élève&nbsp:</td>";
    
    //affichage de tous les élèves dans une liste déroulante
    echo "<td><select name='menuChoixEleve' size='4' required>";
    while ($row_el = mysqli_fetch_array($eleves, MYSQL_NUM)) {
        echo "<option value='$row_el[0]'>$row_el[2] $row_el[1] ($row_el[0])</option>";
    }
    echo "</select></td></tr><br>";
    
    echo "</table>";
    
    echo "<br><input type='submit' value='Afficher les séances futures de cet⋅te élève'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
