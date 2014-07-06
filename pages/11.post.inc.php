<?php
$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
if	(strlen($form->getValeur("id_preneur"))==0)
	$err .= "Sélectionner le preneur<br>";
if	(strlen($form->getValeur("annonce"))==0)
	$err .= "Sélectionner l'annonce<br>";
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
	}

	if	($nbAttaquant==1) $nbDef++;

	$form->setValeur("annonce_reussie", ($form->getValeur("total")>0)?1:0);
	$this->db->sqlInsert("parties", $form->getValeurs());

	$idPar = $this->db->sqlLastInsert("parties", "id");

	$valJPar["id_tournoi"] = $form->getValeur("id_tournoi");
	$valJPar["id_session"] = $form->getValeur("id_session");
	$valJPar["id_partie"] = $idPar;

	$reqJ = "SELECT id_joueur ".
			"from	r_sessions_joueurs ".
			"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
			"and	id_session = " . intval($form->getValeur("id_session"));
	$this->db->sqlOpenCur($resJ, $reqJ);
	$nbJ=$this->db->sqlCountCur($resJ);

	while	($rowJ=$this->db->sqlFetchCur($resJ))
	{
		$valJPar["id_joueur"]= $rowJ->id_joueur;
		if	($form->getValeur("id_preneur")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "preneur";
			$valJPar["points"]= $form->getValeur("total")*($nbDef-1);
		}
		elseif	($form->getValeur("id_second")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "appelé";
			$valJPar["points"]= $form->getValeur("total");
		}
		elseif	($form->getValeur("def1")==$rowJ->id_joueur || $form->getValeur("def2")==$rowJ->id_joueur || $form->getValeur("def3")==$rowJ->id_joueur || $form->getValeur("def4")==$rowJ->id_joueur || $form->getValeur("def5")==$rowJ->id_joueur || $form->getValeur("def6")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "défense";
			$valJPar["points"]= $form->getValeur("total")*(-1);
		}
		else
		{
			$valJPar["type"]= "mort";
			$valJPar["points"]= 0;
		}
		$this->db->sqlInsert("r_parties_joueurs", $valJPar);
	}
	$this->db->sqlCloseCur($resJ);

	header("Location: ".$form->getValeur("from"));
}