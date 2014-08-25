<?php
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

echo $form->makeInputDate("datedeb", "datedeb", "Date de début (*)", $form->getValeur("datedeb"), '', 'date début');
echo $form->makeInputDate("datefin", "datefin", "Date de fin", $form->getValeur("datefin"), '', 'date fin');
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();