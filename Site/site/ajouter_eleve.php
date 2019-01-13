<html>
<head>
	<title>Enregistrement de l’élève</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<body>
	<?php
	//récupération des informations
	$prenom = $_POST['prenom'];
	$nom = $_POST['nom'];
	$reponse = $_POST['reponse'];
	
	if ($reponse == 'non') {
		echo"<br><a href=\"ajout_eleve.html\">Ajouter un⋅e autre élève.</a>";
	}
	
	else {
		//vérification des données
		if (empty($prenom) || empty($nom)) {
			echo"Un champ n’a pas été bien rempli, veuillez retourner au formulaire.<br>";
			echo"<a href=\"ajout_eleve.html\">Ajouter un⋅e élève</a>";
		}
		
		else {
			//de même
			$jour_naissance = $_POST['jour_naissance'];
			$mois_naissance = $_POST['mois_naissance'];
			$annee_naissance = $_POST['annee_naissance'];
			if (!checkdate($mois_naissance, $jour_naissance, $annee_naissance)) {
				echo"La date de naissance saisie n’est pas valide. Veuillez retourner au formulaire.<br>";
				echo"<a href=\"ajout_eleve.html\">Ajouter un⋅e élève</a>";
			}
			
			else {
				//concaténation des informations en une date
				$date_naissance = $_POST['annee_naissance']."-".$_POST['mois_naissance']."-".$_POST['jour_naissance'];
				
				date_default_timezone_set('Europe/Paris');
				$date = date("Y\-m\-d");
				echo "Nous sommes le $date.<br>";
				
				//récapitulatif de la saisie
				echo"Vous avez saisi les données de l’élève $prenom $nom, né⋅e le $date_naissance.<br>";
				
				
				//connexion à la BDD
				$dbhost = 'tuxa.sme.utc';
				$dbuser = 'nf92p077';
				$dbpass = '37lbwDUK';
				$dbname = 'nf92p077';
				$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
				
				//on vérifie si l'élève existe déjà
				$eleve_pareil = mysqli_query($connect,"SELECT * FROM `eleves` WHERE `nom` LIKE '$nom' AND `prenom` LIKE '$prenom';");
				
				if (!($eleve_pareil->num_rows == 0) && $reponse=="blblb") { //s'il y a bien un résultat pour cette recherche
					echo "<br>Il y a déjà un⋅e élève $prenom $nom dans la BDD. Voulez-vous vraiment inscrire un⋅e homonyme&nbsp?<br>";
					
					echo "<form action='ajouter_eleve.php' method='POST'>";
					
					echo "<input type='radio' name='reponse' value='oui'>Oui<br>";
					echo "<input type='radio' name='reponse' value='non'>Non<br>";
					echo "<input type='hidden' name ='prenom' value='$prenom'>";
					echo "<input type='hidden' name ='nom' value='$nom'>";
					echo "<input type='hidden' name ='annee_naissance' value=$annee_naissance>";
					echo "<input type='hidden' name ='mois_naissance' value=$mois_naissance>";
					echo "<input type='hidden' name ='jour_naissance' value=$jour_naissance>";
					
					echo "<br><input type='submit' value='Valider l’élève'>";
				}
				
				else {
					//élaboration de la requête SQL
					$query = "INSERT INTO `$dbuser`.`eleves` VALUES (NULL, '$nom', '$prenom', '$date_naissance', '$date');";
					echo "<br>La requête d'enregistrement sera&nbsp:<br>$query<br>";
					$result = mysqli_query($connect, $query);
					
					
					//retour utilisateur
					if (!$result) {
						echo "<br>Il y a une erreur&nbsp: ".mysqli_error($connect);
					}
					else {
						echo"<br>Vous venez bien d’enregistrer $prenom $nom dans la base de données.<br>";
					}
					
					//vieux code, BDD en txt
					//$saisie = "Prenom : ".$prenom."\nNom : ".$nom."\nDate de naissance : ".$date_naissance."\nDate d'inscription : ".$date_inscription."\n\n\n";
					//$fp = fopen('eleves.txt', 'a+');
					//fwrite($fp, $saisie);
					//fclose($fp);
					
					echo"<br><a href=\"ajout_eleve.html\">Ajouter un⋅e autre élève.</a>";
				}
			}
		}
	}
	//on referme
	mysqli_close($connect);
	?>
</body>
</html>
