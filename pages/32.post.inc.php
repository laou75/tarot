<?php
$form = new Formulaire();

$form->setValeurs($_POST);

$err="";
if  (strlen($form->getValeur("datedeb"))==0)
	$err .= "Le champ 'Date de début' est obligatoire !<br>";
if	(count($form->getValeur("liste_joueurs"))<4)
    $err .= "Il faut sélectionner au moins 4 joueurs !<br>";
if  ($err=="")
{
    $form->setValeur("datedeb", $form->textToDate($form->getValeur("datedeb")));
	if	(strlen($form->getValeur("datefin"))>0)
	{
        $form->setValeur("datefin", $form->textToDate($form->getValeur("datefin")));
	} 
	$this->db->sqlUpdate(  "sessions",
                            array("id" => intval($form->getValeur("id")),
                                  "id_tournoi"=> intval($form->getValeur("id_tournoi"))),
                                  $form->getValeurs());

	//	Traiter les joueurs
    $this->db->sqlDelete('r_sessions_joueurs', array(   'id_tournoi' => $form->getValeur('id_tournoi'),
                                                        'id_session' => $form->getValeur('id') ) );
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
