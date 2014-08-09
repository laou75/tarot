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

        $joueurs = new Joueur($this->db);
        if (!$data = $joueurs->checkMDP($login, $password))
			$err .= "Identification incorrecte !";
		else
		{
			//	Creation de la session
			$sess = new Sess();
            //$sess->sessionConnect($login, "admin");
            $sess->sessionConnect($data);
            $sessionTarot = $sess;
            $_SESSION["sessionTarot"] = $sess;
            header("Location: index.php");
		}
	}
}