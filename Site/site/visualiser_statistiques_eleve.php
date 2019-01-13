<html>
<head>
    <title>Statistiques d’un⋅e élève</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
    <?php
    //récupération des informations
    $eleve = $_POST['menuChoixEleve'];
    $mode = $_POST['menuChoixMode'];

    //connexion à la BDD
    $dbhost = 'tuxa.sme.utc';
    $dbuser = 'nf92p077';
    $dbpass = '37lbwDUK';
    $dbname = 'nf92p077';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

    //il faut reconstruire les informations de l'élève
    $result_eleve = mysqli_query($connect, "SELECT * FROM `eleves` WHERE `eleves`.`ideleve` = '$eleve';");
    while ($row = mysqli_fetch_array($result_eleve, MYSQL_NUM)) {
        $nom = $row[1];
        $prenom = $row[2];
    }

    echo "<h1>Résultats de $prenom $nom</h1>";

    //on sélectionne l'ensemble des séances auxquelles l'élève est inscrit⋅e
    $inscriptions = mysqli_query($connect,"SELECT * FROM `inscriptions` WHERE `inscriptions`.`ideleve` = '$eleve';");

    switch ($mode) {
        case "seance":
        echo "<table>";
        while ($row = mysqli_fetch_array($inscriptions, MYSQL_NUM)) { //chaque inscription de l'élève est récupérée
            $seance = $row[1]; //on en extrait l'id de la séance et la note
            $note = $row[2];
            if ($note > -1) { //si la note n'est pas -1, c'est que la séance a été effectuée
                $infos_seance = mysqli_query($connect, "SELECT * FROM `seances` WHERE `seances`.`idseance` = '$seance';"); //pour pouvoir l'afficher, on extrait donc ses informations
                while ($roww = mysqli_fetch_array($infos_seance, MYSQL_NUM)) { //on va forcément dans ce while et il ne s'exécute qu'une fois, par construction, il n'y a donc pas de conflit sur les variables
                    $theme = $roww[3];
                    $date = $roww[1];
                    $nom_theme = mysqli_fetch_array(mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE `themes`.`idtheme` = '$theme';"), MYSQL_NUM)[0]; //et on interroge la BDD pour avoir le nom du thème
                }
                echo "<tr><td>Séance du $date sur le thème $nom_theme</td>"; //récapitulatif dans un tableau
                echo "<td>$note faute(s)</td>";
                if ($note <= 5) {
                    echo "<td>Séance validée</td></tr>";
                }
                else {
                    echo "<td>Séance ratée</td></tr>";
                }
            }
        }
        echo "</table>";
        break;

        case "theme":
        echo "<table>";
        //on liste tous les thèmes
        $themes = mysqli_query($connect, "SELECT * FROM `themes`;");
        while ($row = mysqli_fetch_array($themes, MYSQL_NUM)) { //pour chaque thème, on va effectuer une série d'opérations
            $nom_theme = $row[1];
            $id_theme = $row[0];
            //on prépare les statistiques par des compteurs
            $nb_seances_effectuees = 0;
            $nb_seances_reussies = 0;
            $total_points = 0;
            //on veut maintenant obtenir le tableau de toutes les séances sur ce thème
            $seances = mysqli_query($connect, "SELECT * FROM `seances` WHERE `seances`.`idtheme` = '$id_theme';");
            while ($roww = mysqli_fetch_array($seances, MYSQL_NUM)) { //pour chacune de ces séances, on va effectuer une série d'opérations
                $date_seance = $roww[1];
                $id_seance = $roww[0];
                //enfin, on récupère l'inscription de cet⋅te élève à cette séance
                $inscription = mysqli_query($connect, "SELECT * FROM `inscriptions` WHERE `inscriptions`.`idseance` = '$id_seance' AND `inscriptions`.`ideleve` = '$eleve';");
                while ($rowww = mysqli_fetch_array($inscription, MYSQL_NUM)) { //et on traite l'inscription
                    $note = $rowww[2];
                    if ($note > -1) { //si la note est -1, c'est que la séance n'a pas encore été notée
                        ++$nb_seances_effectuees;
                        $total_points = $total_points + $note;
                        if ($note <= 5) {
                            ++$nb_seances_reussies; //la séance est comptée comme réussie s'il y a moins de 5 fautes
                        }
                    }
                }
            }
            //maintenant qu'on a toutes les informations, on peut les afficher dans le tableau
            echo "<tr><td>Thème $nom_theme&nbsp:</td>";
            echo "<td>$nb_seances_effectuees séance(s) effectuée(s)</td>";
            if ($nb_seances_effectuees > 0) {
                $moyenne = $total_points/$nb_seances_effectuees; //division par le compteur
                $pourcentage = ($nb_seances_reussies/$nb_seances_effectuees)*100;
                echo "<td>$moyenne faute(s) en moyenne</td>";
                echo "<td>$pourcentage&nbsp% des séance(s) réussies</tr>";
            }
            else {
                echo "</tr>";
            }
        }
        echo "</table>";
        break;

        default:
        //les cmpteurs pour faire des moyennes
        $nb_seances_effectuees = 0;
        $nb_seances_reussies = 0;
        $total_points = 0;
        while ($row = mysqli_fetch_array($inscriptions, MYSQL_NUM)) {
            $note = $row[2];
            if ($note > -1) { //si la note est -1, c'est que la séance n'a pas encore été notée
                ++$nb_seances_effectuees;
                $total_points = $total_points + $note;
                if ($note <= 5) {
                    ++$nb_seances_reussies; //la séance est comptée comme réussie s'il y a moins de 5 fautes
                }
            }
        }
        echo "<table><tr><td>Nombre de séance(s) effectuée(s)&nbsp:</td>";
        echo "<td>$nb_seances_effectuees</td></tr>";
        if ($nb_seances_effectuees > 0) {
            $moyenne = $total_points/$nb_seances_effectuees; //division par le compteur
            $pourcentage = ($nb_seances_reussies/$nb_seances_effectuees)*100;
            echo "<tr><td>Nombre moyen de fautes par séance&nbsp:</td>";
            echo "<td>$moyenne</td></tr>";
            echo "<tr><td>Pourcentage de séances réussies&nbsp:</td>";
            echo "<td>$pourcentage %</td></tr>";
        }
        echo "</table>";
        break;
    }

    echo "<br><a href=\"statistiques_eleve.php\">Voir les statistiques d’un⋅e autre élève.</a>";

    //on referme
    mysqli_close($connect);
    ?>
</body>
</html>
