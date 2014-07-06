<?php
$form = new Formulaire();

if	(count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeurs(array());
	$form->setValeur("id_tournoi", intval($_GET["id_tournoi"]));
	$form->setValeur("liste_joueurs", array());
	$form->setValeur("datedeb", $form->timeToTextDate(time()));
}
$res=null;
$this->db->sqlOpenCur($res, "select * from joueurs order by nom asc, prenom asc" );
$nb = $this->db->sqlCountCur($res);
while	($row=$this->db->sqlFetchCur($res))
{
	$alisteJoueurs[$row->id] = $row->prenom." ".$row->nom; 
}
$this->db->sqlFreeResult($res);

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(30, "id_tournoi=".$form->getValeur("id_tournoi")));

echo $form->openForm("Ajouter une session", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeInput("datedeb", "datedeb", "Date de début (*)", $form->getValeur("datedeb"));
echo $form->makeInput("datefin", "datefin", "Date de fin", $form->getValeur("datefin"));
echo $form->makeComboMultiple("liste_joueurs[]", "liste_joueurs", "Joueurs (*)", $form->getValeur("liste_joueurs"), $alisteJoueurs);
echo $form->makeTexteRiche("commentaires", "commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();