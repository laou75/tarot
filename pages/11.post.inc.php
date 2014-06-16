<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
if	(strlen($form->getValeur("id_preneur"))==0)
	$err .= "S�lectionner le preneur<br>";
if	(strlen($form->getValeur("annonce"))==0)
	$err .= "S�lectionner l'annonce<br>";
if	(strlen($form->getValeur("points"))==0)
	$err .= "Renseigner les points<br>";

if	($err=="")
{
	if	(strlen($form->getValeur("id_second"))==0)
	{
		$nbAttaquant = 1;
		$nbDef=0;
	}
	else
	{
		$nbAttaquant = 2;
		$nbDef=0;
	}

	for($i=1;$i<10;$i++)
	{
		if	(strlen($form->getValeur("def".$i))>0)
			$nbDef++;
//		echo "$i ($nbDef): ".$form->getValeur("def".$i)."<br>";
	}

	if	($nbAttaquant==1) $nbDef++;

/*
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	echo "nbDef= ".$nbDef."<br>";
	echo "points = ".$form->getValeur("total")."<br>";
	echo "preneur = ".$form->getValeur("total")*($nbDef)."<br>";
	if	($nbAttaquant == 2)
		echo "second = ".$form->getValeur("total")."<br>";
	echo "d�fense = ".$form->getValeur("total")*(-1)."<br>";
	exit();
*/

	$form->setValeur("annonce_reussie", ($form->getValeur("total")>0)?1:0);
	$this->db->sql_insert("parties", $form->getValeurs());

	$idPar = $this->db->sql_last_insert("parties", "id");

	$valJPar["id_tournoi"] = $form->getValeur("id_tournoi");
	$valJPar["id_session"] = $form->getValeur("id_session");
	$valJPar["id_partie"] = $idPar;

	$reqJ = "SELECT id_joueur ".
			"from	r_sessions_joueurs ".
			"where	id_tournoi = ".$form->getValeur("id_tournoi")." ".
			"and	id_session = ".$form->getValeur("id_session");
	$this->db->sql_open_cur($resJ, $reqJ);
	$nbJ=$this->db->sql_count_cur($resJ);

	while	($rowJ=$this->db->sql_fetch_cur($resJ))
	{
		$valJPar["id_joueur"]= $rowJ->id_joueur;
		if	($form->getValeur("id_preneur")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "preneur";
			$valJPar["points"]= $form->getValeur("total")*($nbDef-1);
		}
		elseif	($form->getValeur("id_second")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "appel�";
			$valJPar["points"]= $form->getValeur("total");
		}
		elseif	($form->getValeur("def1")==$rowJ->id_joueur || $form->getValeur("def2")==$rowJ->id_joueur || $form->getValeur("def3")==$rowJ->id_joueur || $form->getValeur("def4")==$rowJ->id_joueur || $form->getValeur("def5")==$rowJ->id_joueur || $form->getValeur("def6")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "d�fense";
			$valJPar["points"]= $form->getValeur("total")*(-1);
		}
		else
		{
			$valJPar["type"]= "mort";
			$valJPar["points"]= 0;
		}
		$this->db->sql_insert("r_parties_joueurs", $valJPar);
	}
	$this->db->sql_close_cur($resJ);

	Header("Location: ".$form->getValeur("from"));
	exit();
}
?>