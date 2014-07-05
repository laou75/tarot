<?php
$form = new Formulaire();

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
	$form->setValeur("id", $_GET["id_session"]);
	$this->db->sql_select_array($row, "select * from sessions where id=" . intval($form->getValeur("id"))." and id_tournoi=" . intval($form->getValeur("id_tournoi")) );
	$form->setValeurs($row);
	
	$this->db->sql_open_cur($res, "select * from r_sessions_joueurs where id_tournoi=" . intval($form->getValeur("id_tournoi")) . " and id_session=" . intval($form->getValeur("id")) . " order by position asc" );
	while	($row2=$this->db->sql_fetch_cur($res))
	{
		$aTmp[$row2->id_joueur] = $row2->id_joueur; 
	}
	$this->db->sql_free_result($res);	
	$form->setValeur("liste_joueurs", $aTmp);
}

$this->db->sql_open_cur($res, "select * from joueurs order by nom asc, prenom asc" );
$nb = $this->db->sql_count_cur($res);
while	($row=$this->db->sql_fetch_cur($res))
{
	$alisteJoueurs[$row->id] = $row->prenom." ".$row->nom; 
}
$this->db->sql_free_result($res);	

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(30, "id_tournoi=".$form->getValeur("id_tournoi")));

echo $form->openForm("Modifier une session", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
echo $form->makeInput("datedeb", "datedeb", "Commencée le (*)", strftime ("%x", $form->getValeur("datedeb")));
$datefin = (strlen($form->getValeur("datefin"))>0)?strftime ("%x", $form->getValeur("datefin")):"";
echo $form->makeInput("datefin", "datefin", "Terminée le", $datefin);
echo $form->makeComboMultiple("liste_joueurs[]", "liste_joueurs", "Joueurs (*)", $form->getValeur("liste_joueurs"), $alisteJoueurs);
echo $form->makeTexteRiche("commentaires", "commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();