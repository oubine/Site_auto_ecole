<html>
<head>
	<title>Enregistrement de l'inscription</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
	<?php
	//récupération des informations
	$eleve = $_POST['menuChoixEleve'];
	$seance = $_POST['menuChoixSeance'];
	
	//connexion à la BDD
	$dbhost = 'tuxa.sme.utc';
	$dbuser = 'nf92p077';
	$dbpass = '37lbwDUK';
	$dbname = 'nf92p077';
	$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
	
	//on récupère le thème, la date et l'effectif de la séance
	$result_seances = mysqli_query($connect, "SELECT * FROM `seances` WHERE `idSeance` = '$seance';");
	while ($row = mysqli_fetch_array($result_seances, MYSQL_NUM)) {
		$date = $row[1];
		$eff_max = $row[2];
		$theme = $row[3];
	}
	
	$result_theme = mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE themes.idtheme='$theme';");
	$nom_theme = mysqli_fetch_array($result_theme, MYSQL_NUM)[0];
	
	//récapitulatif
	echo "Vous voulez inscrire l’élève numéro $eleve à la séance du $date sur le thème $nom_theme.<br>";
	
	//on vérifie que l'effectif n'est pas dépassé
	$inscr = mysqli_query($connect, "SELECT * FROM `inscriptions` WHERE `idSeance` = '$seance';");
	$eff_actuel = $inscr->num_rows;
	if ($eff_actuel == $eff_max) {
		echo "<br>Il n’y a plus de place dans cette séance, veuillez inscrire l’élève un autre jour.<br>";
		echo "<br><a href=\"inscription_eleve.php\">Retourner à l'inscription d'un⋅e élève.</a>";
	}
	
	else {
		//construction de la requête d'insertion
		$query = "INSERT INTO `$dbuser`.`inscriptions` (`ideleve`, `idseance`) VALUES ('$eleve', '$seance');";
		
		echo "<br>La requête d'enregistrement sera&nbsp:<br>$query<br>";
		$result = mysqli_query($connect, $query);
		
		if (!$result) {
			echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
			echo "<br><a href=\"inscription_eleve.php\">Retourner à l'inscription d'un⋅e élève.</a>";
		}
		
		else {
			echo "<br>Vous venez bien d’inscrire un élève à une séance.<br>";
			echo "<br><a href=\"inscription_eleve.php\">Inscrire un⋅e autre élève.</a>";
		}
	}
	
	//on referme
	mysqli_close($connect);
	
	?>
</body>
</html>
