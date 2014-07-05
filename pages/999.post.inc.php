<?php
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
        $row="";
		$login = $_POST["identifiant"];
		$password = $_POST["password"];
		//	*** trou de sécurité *** Revoir le test ***
        $this->db->sql_select($row, "select nickname, mdp from joueurs where nickname = '" . mysqli::escape_string($login) ."'");

        echo " - " . strtolower($row->mdp) . " != " . strtolower($password) . "<br>";

        if (strtolower($row->mdp) != strtolower($password))
			$err .= "Identification incorrecte !";
		else
		{
			//	Creation de la session
			$sess = new Session();
			$sess->sessionConnect($login, "admin");
			$sessionTarot = $sess;
            $_SESSION["sessionTarot"] = $sess;
            header("Location: index.php");
		}
	}
}