<html>
<head>
	<title>Désactivation </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
	<?php
	//récupération des informations
	$idtheme = $_POST['menuChoixTheme'];
	
	//connexion à la BDD
	$dbhost = 'tuxa.sme.utc';
	$dbuser = 'nf92p077';
	$dbpass = '37lbwDUK';
	$dbname = 'nf92p077';
	$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
	
	//recherche dans la base des thèmes actifs
	$result_th = mysqli_query($connect,"SELECT * FROM `themes` WHERE `idtheme` = '$idtheme' AND `supprime` = '0';");
	while ($row = mysqli_fetch_array($result_th, MYSQL_NUM)) {
		$nom = $row[1];
		$descriptif = $row[3];
	}
	
	//récapitulatif
	echo"Vous voulez désactiver le thème $nom, dont la description est&nbsp:<br>$descriptif<br>";
	
	$query = "UPDATE `$dbuser`.`themes` SET `supprime` = '1' WHERE `themes`.`idtheme` = $idtheme;"; //mise à jour du thème, on passe supprime à 1 pour le désactiver
	echo "<br>Le thème numéro $idtheme était actif, il sera supprimé avec la requête&nbsp:<br>$query<br>";
	echo "<br>Pour le réactiver, saisissez-le à nouveau à la page <a href=\"ajout_theme.html\">Ajouter un thème</a>.<br>";
	
	$result = mysqli_query($connect, $query);
	
	if (!$result) {
		echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect)."<br>";
		echo "<br><a href=\"suppression_theme.php\">Retourner à la suppression d’un thème.</a>";
	}
	else {
		echo "<br>Vous venez bien de désactiver le thème $nom.<br>";
		echo "<br><a href=\"suppression_theme.php\">Désactiver un autre thème.</a>";
	}
	
	//on referme
	mysqli_close($connect);
	?>
</body>
</html>
