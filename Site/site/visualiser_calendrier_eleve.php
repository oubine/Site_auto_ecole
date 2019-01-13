<html>
<head>
    <title>Informations de l’élève</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <?php
    //récupération de l'élève
    $eleve = $_POST['menuChoixEleve'];

    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

    //on récupère les informations de l'élève
    $result_el = mysqli_query($connect, "SELECT * FROM `eleves` WHERE `ideleve` = '$eleve';");
    while ($row = mysqli_fetch_array($result_el, MYSQL_NUM)) {
        $prenom = $row[2];
        $nom = $row[1];
        $date_nais = $row[3];
        $date_inscr = $row[4];
    }

    //on récupère les informations des séances futures auxquelles l'élève est inscrit⋅e
    date_default_timezone_set('Europe/Paris');
    $date = date("Y\-m\-d");

    //deux conditions : la date est supérieure ou égale à aujourd'hui et l'id de la séance est l'id d'une des séances auxquelles l'élève est inscrit⋅e
    //il faut donc faire une requête dans la requête, pour récupérer cette liste de séances
    $result_se = mysqli_query($connect, "SELECT * FROM `seances` WHERE seances.idseance IN ( SELECT inscriptions.idseance FROM `inscriptions` WHERE inscriptions.ideleve = $eleve ) AND seances.dateSeance >= '$date' ORDER BY `dateSeance`;");

    //pour garder toutes les informations, on indice les dates et thèmes
    $i = 0;
    while ($row = mysqli_fetch_array($result_se, MYSQL_NUM)) {
        $nom_var_date = 'date_se_'.$i;
        $$nom_var_date = $row[1]; //utilisation d'une variable dynamique
        $nom_var_theme = 'theme_'.$i;
        $$nom_var_theme = $row[3];
        ++$i;
    }

    echo "<h1>Calendrier de $prenom $nom</h1>";

    //on construit un tableau avec les dates des séances et les thèmes
    echo "<table>";

    for ($j = 0; $j < $i; $j++) {
        $nom_var_date = 'date_se_'.$j; //encore des variables dynamiques
        echo "<tr><td>Séance du ${$nom_var_date}</td>";
        //on récupère le nom du thème numéro j
        $nom_var_theme = 'theme_'.$j; //même principe
        $idtheme = $$nom_var_theme;
        $result_th = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE `idtheme` = '$idtheme';");
        $nom_th = mysqli_fetch_array($result_th, MYSQL_NUM)[0];
        echo "<td>Sur le thème $nom_th</td></tr>";
    }

    echo "</table>";

    echo "<br><a href='visualisation_calendrier_eleve.php'>Consulter le calendrier d’un⋅e autre élève.</a>";

    mysqli_close($connect);
    ?>
</body>
