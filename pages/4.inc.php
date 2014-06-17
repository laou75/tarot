<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(2));

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeur("id", $_GET["id_tournoi"]);
	$this->db->sql_select_array($row, "select * from tournois where id=".$form->getValeur("id")." ");
	$form->setValeurs($row);
}

echo $form->openForm("Modifier un tournoi", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeInput("datedeb", "datedeb", "Date de d�but (*)", strftime ("%x", $form->getValeur("datedeb")));
echo $form->makeInput("datefin", "datefin", "Date de fin", strftime ("%x", $form->getValeur("datefin")));
echo $form->makeTexteRiche("commentaires", "commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();