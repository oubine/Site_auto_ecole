<html>
<head>
    <title>Statistiques d’un⋅e élève</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Choisir un⋅e élève et un mode d’affichage</h1>
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
    echo "<form action='visualiser_statistiques_eleve.php' method='POST'>";
    
    echo "<table>";
    
    echo "<tr><td>Élèves&nbsp:</td>";
    //affichage de tous les élèves dans une liste déroulante
    echo "<td><select name='menuChoixEleve' size='4' required>";
    while ($row_el = mysqli_fetch_array($eleves, MYSQL_NUM)) {
        echo "<option value='$row_el[0]'>$row_el[2] $row_el[1] ($row_el[0])</option>";
    }
    echo "</select></td></tr>";
    
    echo "<tr><td>Mode d’affichage&nbsp:</td>";
    
    echo "<td><select name='menuChoixMode' required>";
    
    echo "<option value='standard'>Intégralité des séances</option>";
    echo "<option value='theme'>Thème par thème</option>";
    echo "<option value='seance'>Séance par séance</option>";
    
    echo "</select></td></tr><br>";
    
    echo "</table>";
    
    echo "<br><input type='submit' value='Afficher les résultats de cet⋅te élève'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
