<?php
$GLOBALS["Config"]["MENU"] = array(
	"index.php"	=>	array(
		"url"			=>	"index.php",
		"titre"			=>	"Accueil",
		"description"	=>	"Page d'accueil",
		),
		"tournois.php" => array(
			"url"			=>	"tournois.php",
			"titre"			=>	"Listes des tournois",
			"description"	=>	"Listes des tournois",
			"pere"			=>	"index.php"
			),
			"parties.php" => array(
				"url"			=>	"parties.php",
				"titre"			=>	"Listes des parties",
				"description"	=>	"Listes des parties",
				"pere"			=>	"tournois.php"
				),
				"ajouterpartie.php" => array(
					"url"			=>	"ajouterpartie.php",
					"titre"			=>	"Ajouter une partie",
					"description"	=>	"Ajouter une partie",
					"pere"			=>	"parties.php"
					),
				"modifierpartie.php" => array(
					"url"			=>	"modifierpartie.php",
					"titre"			=>	"Modifier une partie",
					"description"	=>	"Modifier une partie",
					"pere"			=>	"parties.php"
					),
				"supprimerpartie.php" => array(
					"url"			=>	"supprimerpartie.php",
					"titre"			=>	"Supprimer une partie",
					"description"	=>	"Supprimer une partie",
					"pere"			=>	"parties.php"
					),
			"joueurs.php" => array(
				"url"			=>	"joueurs.php",
				"titre"			=>	"Listes des joueurs",
				"description"	=>	"Listes des joueurs",
				"pere"			=>	"tournois.php"
				),
				"ajouterjoueur.php" => array(
					"url"			=>	"ajouterjoueur.php",
					"titre"			=>	"Ajouter un joueur",
					"description"	=>	"Ajouter un joueur",
					"pere"			=>	"joueurs.php"
					),
				"modifierjoueur.php" => array(
					"url"			=>	"modifierjoueur.php",
					"titre"			=>	"Modifier un joueur",
					"description"	=>	"Modifier un joueur",
					"pere"			=>	"joueurs.php"
					),
				"supprimerjoueur.php" => array(
					"url"			=>	"supprimerjoueur.php",
					"titre"			=>	"Supprimer un joueur",
					"description"	=>	"Supprimer un joueur",
					"pere"			=>	"joueurs.php"
					),
	);	
?>