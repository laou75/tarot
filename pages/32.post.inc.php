<?php
$form = new Formulaire();

$form->setValeurs($_POST);

$err="";
if (strlen($form->getValeur("datedeb"))==0)
	$err .= "Le champ 'Date de début' est obligatoire !<br>";
if ($err=="")
{
	$d = substr($form->getValeur("datedeb"), 0, 2);
	$m = substr($form->getValeur("datedeb"), 3, 2);
	$y = substr($form->getValeur("datedeb"), 6, 4);
	$form->setValeur("datedeb", mktime ( 0, 0, 0, $m, $d, $y)); 
	if	(strlen($form->getValeur("datefin"))>0)
	{
		$d = substr($form->getValeur("datefin"), 0, 2);
		$m = substr($form->getValeur("datefin"), 3, 2);
		$y = substr($form->getValeur("datefin"), 6, 4);
		$form->setValeur("datefin", mktime ( 0, 0, 0, $m, $d, $y));
	} 
	$this->db->sqlUpdate(  "sessions",
                            array("id" => intval($form->getValeur("id")),
                                  "id_tournoi"=> intval($form->getValeur("id_tournoi"))),
                                  $form->getValeurs());

	//	Traiter les joueurs
	$this->db->sqlExecute("delete from r_sessions_joueurs where id_tournoi=" . intval($form->getValeur("id_tournoi"))." and id_session=" . intval($form->getValeur("id")) );
	$i=1;
	foreach($form->getValeur("liste_joueurs") as $ii => $id)
	{
		$aTmp = array();
		$aTmp["id_tournoi"] = intval($form->getValeur("id_tournoi"));
		$aTmp["id_session"] = intval($form->getValeur("id"));
		$aTmp["id_joueur"] = intval($id);
		$aTmp["position"] = intval($i);
		$this->db->sqlInsert("r_sessions_joueurs", $aTmp);
		$i++;
	}
	header("Location: ".$form->getValeur("from"));
}