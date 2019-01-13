<html>
<head>
    <title>Notation des élèves</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <?php
    
    //récupération de la séance
    $seance = $_POST['menuChoixSeance'];
    $note = $_POST['note_el_2'];
    
    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    
    //récupération des élèves inscrits à cette séance
    $eleves = mysqli_query($connect, "SELECT * FROM `eleves` WHERE `ideleve` IN (SELECT `ideleve` FROM `inscriptions` WHERE inscriptions.idseance='$seance');");
    
    //on fait une boucle avec tous les élèves de la séance pour traiter les notes une par une
    while ($row_el = mysqli_fetch_array($eleves, MYSQL_NUM)) { //les élèves, ligne par ligne
        $result_inscr = mysqli_query($connect, "SELECT * FROM `inscriptions` WHERE `idseance` = '$seance' AND `ideleve` = '$row_el[0]';"); //ce que contient déjà la table inscriptions concernant cette séance
        while ($row_inscr = mysqli_fetch_array($result_inscr, MYSQL_NUM)) { //leur inscription à la séance
            //on construit le nom de la variable à récupérer
            $identifier = 'note_el_'.$row_el[0];
            $note = $_POST[$identifier];
            if (($note != '') && ($note >= 0) && ($note <= 40)) {
                echo "<br>L’élève $row_el[2] $row_el[1] a effectué $note fautes. Ce sera enregistré dans la BDD grâce à la requête suivante&nbsp:<br>";
                
                //construction de la requête de mise à jour de la note
                $query = "UPDATE `nf92p077`.`inscriptions` SET `note` = '$note' WHERE `inscriptions`.`ideleve` = '$row_el[0]' AND `inscriptions`.`idseance` = '$seance';";
                
                echo "$query<br>";
                
                $result = mysqli_query($connect, $query);
                
                if (!$result) {
                    echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
                }
            }
        }
    }
    
    echo "<br>Toutes les notes ont bien été rentrées dans la BDD.<br>";
    echo "<br><a href='validation_seance.php'>Valider une autre séance.</a>";
    
    mysqli_close($connect);
    ?>
</body>
