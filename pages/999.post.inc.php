<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
if (strlen($form->getValeur("identifiant"))==0)
	$err .= "Le champ 'Identifiant' est obligatoire !<br>";
if (strlen($form->getValeur("password"))==0)
	$err .= "Le champ 'Mot de passe' est obligatoire !<br>";

if ($err=="")
{
	if (true)
	{
		$login = $_POST["identifiant"];
		$password = $_POST["password"];
		//	*** trou de s�curit� *** Revoir le test ***
		$this->db->sql_select($row, "select nickname, mdp from joueurs where nickname = '$login'");

		if (strtolower($row->mdp) != strtolower($password))
			$err .= "Identification incorrecte !";
		else
		{
			//	Creation de la session
			$sess = new session();
			$sess->sessionConnect($login, "admin");
			$sessionTarot = $sess;
			session_register("sessionTarot");
			header("Location: index.php");
			exit();
		}
	}
}