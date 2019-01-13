<html>
<head>
	<title>Enregistrement de la séance</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
	<?php
	//récupération des informations
	$theme = $_POST['menuChoixTheme'];
	$eff_max = $_POST['eff_max'];
	
	//vérification de la date avec des méthodes préexistantes
	date_default_timezone_set('Europe/Paris');
	$aujourdhui = new DateTime(date("Y\-m\-d"));
	$date_seance = new DateTime($_POST['annee_seance']."-".$_POST['mois_seance']."-".$_POST['jour_seance']);
	
	if ((!checkdate($_POST['mois_seance'], $_POST['jour_seance'], $_POST['annee_seance'])) || ($date_seance < $aujourdhui)) {
		echo "La date saisie est invalide. Une séance ne peut pas être créée avant aujourd'hui. Veuillez retourner au formulaire.<br>";
		echo "<br><a href=\"ajout_seance.php\">Ajouter une séance</a>";
	}
	
	
	else {
		$date_seance = $_POST['annee_seance']."-".$_POST['mois_seance']."-".$_POST['jour_seance'];
		
		//connexion à la BDD
		$dbhost = 'tuxa.sme.utc';
		$dbuser = 'nf92p077';
		$dbpass = '37lbwDUK';
		$dbname = 'nf92p077';
		$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
		
		
		//on extrait le nom du thème
		$nom_theme = mysqli_fetch_array(mysqli_query($connect, "SELECT `nom` FROM `themes` WHERE themes.idtheme='$theme';"), MYSQL_NUM)[0];
		
		//récapitulatif
		echo "Vous voulez créer une séance sur le thème $nom_theme à la date du $date_seance.<br>";
		
		//on vérifie si une séance sur ce thème existe déjà ce jour
		$seance_pareille = mysqli_query($connect, "SELECT * FROM `seances` WHERE `dateSeance`='$date_seance' AND `idtheme` LIKE '$theme';");
		//il aurait été plus simple de fixer comme clef primaire le couple date-thème
		if (!($seance_pareille->num_rows == 0)) {
			echo "<br>Le $date_seance, il y aura déjà une séance sur le thème $nom_theme.<br>";
			echo "<br><a href=\"ajout_seance.php\">Retourner à la saisie de séance.</a>";
		}
		
		else {
			//construction de la requête d'insertion
			$query = "INSERT INTO `$dbuser`.`seances` VALUES (NULL, '$date_seance', '$eff_max', '$theme');";
			
			echo "<br>La requête d'enregistrement sera&nbsp:<br>$query<br>";
			$result = mysqli_query($connect, $query);
			
			if (!$result) {
				echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect);
				echo "<br><br><a href=\"ajout_seance.php\">Retourner à la saisie de séance.</a>";
			}
			
			else {
				echo "<br>Vous venez bien d’enregistrer une séance dans la base de données.";
				echo "<br><br><a href=\"ajout_seance.php\">Ajouter une autre séance.</a>";
			}
		}
		//on referme
		mysqli_close($connect);
	}
	?>
</body>
</html>
