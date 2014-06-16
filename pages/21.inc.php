<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();


if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeurs(array());
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(20));

echo $form->openForm("Ajouter un joueur", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeInput("nom", "nom", "Nom (*)", $form->getValeur("nom"));
echo $form->makeInput("prenom", "prenom", "Prénom (*)", $form->getValeur("prenom"));
echo $form->makeFileInput("portrait", "portrait", "Portrait", $form->getValeur("portrait"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
/*
echo "<pre>";
print_r($GLOBALS["Config"]);
echo "</pre>";
*/
?>