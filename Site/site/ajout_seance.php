<html>
<head>
    <title>Ajout d’une séance</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <h1>Création d’une séance</h1>
    <?php
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //récupération des thèmes actifs
    $result = mysqli_query($connect,"SELECT * FROM `themes` WHERE `supprime` != 1;");
    
    //création d'un formulaire
    echo "<form action='ajouter_seance.php' method='POST'>";
    echo "<table>";
    
    //sélection du thème
    echo "<tr><td>";
    echo "Thème&nbsp:</td><td>";
    echo "<select name='menuChoixTheme' size='4' required>";
    //affichage de tous les thèmes dans une liste déroulante
    while ($row = mysqli_fetch_array($result, MYSQL_NUM)) {
        echo "<option value='$row[0]'>$row[1]</option>";
    }
    echo "</select></td></tr>";
    
    //saisie de l'effectif maximum
    echo "<tr><td>";
    echo "Effectif maximal de la séance&nbsp:</td><td>";
    echo "<input type='number' name='eff_max' min='1' required /></td></tr>";
    
    //sélection de la date
    echo "<tr><td>";
    echo "Date de la séance&nbsp:</td><td>";
    echo "<select name='jour_seance' size='1'>";
    for ($i=1; $i <= 31; $i++) {
        echo "<option value='$i'>$i</option>;";
    }
    echo "</select>";
    
    echo "<select name='mois_seance' size='1'>";
    echo "<option value='01'>Janvier</option>";
    echo "<option value='02'>Février</option>";
    echo "<option value='03'>Mars</option>";
    echo "<option value='04'>Avril</option>";
    echo "<option value='05'>Mai</option>";
    echo "<option value='06'>Juin</option>";
    echo "<option value='07'>Juillet</option>";
    echo "<option value='08'>Août</option>";
    echo "<option value='09'>Septembre</option>";
    echo "<option value='10'>Octobre</option>";
    echo "<option value='11'>Novembre</option>";
    echo "<option value='12'>Décembre</option>";
    echo "</select>";
    
    date_default_timezone_set('Europe/Paris');
    echo "<select name='annee_seance' size='1'>";
    for ($i=0; $i < 3; $i++) {
        $annee = date("Y") + $i;
        echo "<option value='$annee'>$annee</option>;";
    }
    echo "</select></td></tr>";
    
    echo "</table>";
    
    echo "<br>";
    
    echo "<input type='submit' value='Enregistrer la séance'>";
    echo "</form>";
    mysqli_close($connect);
    ?>
</body>
