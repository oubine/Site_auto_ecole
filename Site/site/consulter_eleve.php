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
    
    echo "<h1>Données de $prenom $nom</h1>";
    
    //récapitulatif
    echo "L’élève que vous avez sélectionné⋅e s’appelle $prenom $nom, iel est né⋅e le $date_nais et est inscrit⋅e depuis le $date_inscr. Son identifiant dans la base de données est le numéro $eleve.<br>";
    
    echo "<br><a href='consultation_eleve.php'>Consulter les données d’un⋅e autre élève.</a>";
    
    mysqli_close($connect);
    ?>
</body>
