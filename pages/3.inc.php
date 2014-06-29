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
	$form->setValeurs(array());
	$form->setValeur("datedeb", $form->timeToTextDate(time()));
}

echo $form->openForm("Ajouter un tournoi", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeInput("datedeb", "datedeb", "Date de début (*)", $form->getValeur("datedeb"));
echo $form->makeInput("datefin", "datefin", "Date de fin", $form->getValeur("datefin"));
echo $form->makeTexteRiche("commentaires", "commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();