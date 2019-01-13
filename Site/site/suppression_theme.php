<html>
<head>
    <title>Suppression d’un thème</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Choix du thème à désactiver</h1>
    <?php
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //récupération des thèmes actifs
    $themes = mysqli_query($connect,"SELECT * FROM `themes` WHERE `supprime` != 1;");
    
    //création d'un formulaire
    echo "<form action='supprimer_theme.php' method='POST'>";
    
    echo "<table>";
    
    echo "<tr><td>Thème&nbsp:</td>";
    
    //affichage de tous les thèmes dans une liste déroulante
    echo "<td><select name='menuChoixTheme' size='4' required>";
    while ($row_th = mysqli_fetch_array($themes, MYSQL_NUM)) {
        echo "<option value='$row_th[0]'>$row_th[1] (numéro $row_th[0])</option>";
    }
    echo "</select></td></tr><br><br>";
    
    echo "</table><br>";
    
    echo "<input type='submit' value='Supprimer le thème sélectionné'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
