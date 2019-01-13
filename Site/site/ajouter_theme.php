<html>
<head>
	<title>Enregistrement du thème</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
	<?php
	//récupération des informations
	$nom = $_POST['theme'];
	$descriptif = $_POST['descriptif'];
	
	//récapitulatif
	echo"Vous voulez créer le thème $nom, dont la description est&nbsp:<p>$descriptif</p>";
	
	
	//connexion à la BDD
	$dbhost = 'tuxa.sme.utc';
	$dbuser = 'nf92p077';
	$dbpass = '37lbwDUK';
	$dbname = 'nf92p077';
	$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
	
	
	//on vérifie si le thème existe déjà
	$theme_pareil = mysqli_query($connect,"SELECT * FROM `themes` WHERE `nom` LIKE '$nom';");
	
	if (!($theme_pareil->num_rows == 0)) { //un peu laid mais ça marche, on vérifie qu'il y a au moins une ligne qui correspond
		echo "<br>Le thème $nom est déjà dans la BDD.";
		$theme = mysqli_fetch_array($theme_pareil, MYSQL_NUM);
		
		//affichage des informations à propos du thème
		echo " Il porte le numéro $theme[0] et est décrit ainsi&nbsp:<p>$theme[3]</p>";
		
		if ($theme[2]) { //on vérifie s'il avait été supprimé
			$query = "UPDATE `$dbuser`.`themes` SET `supprime` = '0', `descriptif` = '$descriptif' WHERE `themes`.`idtheme` = $theme[0];"; //mise à jour du thème, on passe supprime à 0 pour le réactiver
			
			echo "<br>Le thème était supprimé, il sera donc réactivé avec la requête&nbsp:<br>$query<br>";
			echo "<br>Le descriptif a été mis à jour pour correspondre à votre saisie.<br>";
			$result = mysqli_query($connect, $query);
			
			if (!$result) {
				echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
				echo "<br><a href=\"ajout_theme.html\">Retourner à la saisie de thème.</a>";
			}
			else {
				echo "<br>Vous venez bien de réactiver le thème $nom dans la base de données.<br>";
				echo "<br><a href=\"ajout_theme.html\">Ajouter un autre thème.</a>";
			}
		}
		
		else {
			echo "<br><a href=\"ajout_theme.html\">Saisir un autre thème.</a>";
		}
	}
	
	else {
		//construction de la requête d'insertion
		$query = "INSERT INTO `$dbuser`.`themes` VALUES (NULL, '$nom', '0', '$descriptif');"; //par défaut, le thème n'est pas supprimé
		echo "<br>La requête d'enregistrement sera&nbsp:<br>$query<br>";
		
		$result = mysqli_query($connect, $query);
		
		if (!$result) {
			echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
			echo "<br><br><a href=\"ajout_theme.html\">Retourner à la saisie de thème.</a>";
		}
		else {
			echo "<br>Vous venez bien d’enregistrer le thème $nom dans la base de données.<br>";
			echo "<br><a href=\"ajout_theme.html\">Ajouter un autre thème.</a>";
		}
		
	}
	//on referme
	mysqli_close($connect);
	?>
</body>
</html>
