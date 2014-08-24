<?php
$form = new Formulaire();

$form->setValeurs($_POST);

$err="";
if	(strlen($form->getValeur("datedeb"))==0)
	$err .= "Le champ 'Date de début' est obligatoire !<br>";
if	(count($form->getValeur("liste_joueurs"))<4)
	$err .= "Il faut sélectionner au moins 4 joueurs !<br>";
if	($err=="")
{
	$d = substr($form->getValeur("datedeb"), 0, 2);
	$m = substr($form->getValeur("datedeb"), 3, 2);
	$y = substr($form->getValeur("datedeb"), 6, 4);
	$form->setValeur("datedeb", mktime ( 0, 0, 0, $m, $d, $y));
    if(!empty($form->getValeur("datefin")))
    {
        $d = substr($form->getValeur("datefin"), 0, 2);
        $m = substr($form->getValeur("datefin"), 3, 2);
        $y = substr($form->getValeur("datefin"), 6, 4);
        $form->setValeur("datefin", mktime ( 0, 0, 0, $m, $d, $y));
    }
    $this->db->sqlInsert("sessions", $form->getValeurs());

	//	Traiter les joueurs
	$id_session = $this->db->sqlLastInsert("sessions", "id");
	$i=1;
	foreach($form->getValeur("liste_joueurs") as $ii => $id)
	{
		$aTmp = array();
		$aTmp["id_tournoi"] = intval($form->getValeur("id_tournoi"));
		$aTmp["id_session"] = intval($id_session);
		$aTmp["id_joueur"] = intval($id);
		$aTmp["position"] = intval($i);
		$this->db->sqlInsert("r_sessions_joueurs", $aTmp);
		$i++;
	}
	header("Location: ".$form->getValeur("from"));
}