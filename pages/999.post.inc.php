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
		//	*** trou de sécurité *** Revoir le test ***
//		$ret = sql_select($row, "select login, password, role from users where login = '$login'");
		$this->db->sql_select($row, "select nickname, mdp from joueurs where nickname = '$login'");
		

/*
var_dump($row);
exit();
echo "<pre>row	";
print_r($row);
echo "</pre>";
exit();
*/

		
		if (strtolower($row->mdp) != strtolower($password))
			$err .= "Identification incorrecte !";
		else
		{
			//	Creation de la session
			$sess = new session();
			$sess->sessionConnect($login, "admin");
			//$_SESSION["sessionTarot"] = $sess;
			$sessionTarot = $sess;
			session_register("sessionTarot");
			header("Location: index.php");
			exit();
/*
	echo "$basehrefAdmin<pre>";
	print_r($_SESSION);
	echo "</pre>";
	echo "$basehrefAdmin<pre>";
	print_r($GLOBALS["config"]);
	echo "</pre>";
*/
/*
	echo "$basehrefAdmin<pre>";
	print_r($_SESSION);
	echo "</pre>";
	exit();
*/
		}
	}
}
?>