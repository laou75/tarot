<?php
$form = new Formulaire();
$joueurs = new Joueur($db);

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

    $defense = $form->getValeur("defense");
    foreach($defense as $k => $v)
    {
        if	(strlen($v)>0)
            $nbDef++;
    }
	if	($nbAttaquant==1) $nbDef++;

	$form->setValeur("annonce_reussie", ($form->getValeur("total")>0) ? 1 : 0);

    $this->db->sqlUpdate(  "parties",
                            array(  "id" => intval($form->getValeur("id_partie")),
                                    "id_tournoi" => intval($form->getValeur("id_tournoi")),
                                    "id_session" => intval($form->getValeur("id_session"))),
                                    $form->getValeurs());

	$valJPar["id_tournoi"] = $form->getValeur("id_tournoi");
	$valJPar["id_session"] = $form->getValeur("id_session");
	$valJPar["id_partie"] = $form->getValeur("id_partie");

    $reqTMP = "DELETE ".
                "from	r_parties_joueurs ".
                "where	id_tournoi = " . intval($form->getValeur("id_tournoi"))." ".
                "and	id_session = " . intval($form->getValeur("id_session"))." ".
                "and	id_partie = " . intval($form->getValeur("id_partie"));
	$this->db->sqlExecute($reqTMP);

    $tabJoueurs = $joueurs->getIdJoueursBySession($form->getValeur("id_tournoi"), $form->getValeur("id_session"));
    foreach($tabJoueurs as $kJ => $rowJ)
    {
		$valJPar["id_joueur"]= $rowJ->id_joueur;
		if	($form->getValeur("id_preneur")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "preneur";
			$valJPar["points"]= $form->getValeur("total")*($nbDef-1);
		}
		elseif	($form->getValeur("id_second")==$rowJ->id_joueur)
		{
			$valJPar["type"]= "called";
			$valJPar["points"]= $form->getValeur("total");
		}
        elseif	(in_array($rowJ->id_joueur, $defense))
		{
			$valJPar["type"]= "defense";
			$valJPar["points"]= $form->getValeur("total")*(-1);
		}
		else
		{
			$valJPar["type"]= "mort";
			$valJPar["points"]= 0;
		}
		$this->db->sqlInsert("r_parties_joueurs", $valJPar);
	}

	header("Location: ".$form->getValeur("from"));
}