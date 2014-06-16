<?php
include_once ("class/formulaire.class.php");
$form = new Formulaire();
echo $this->drawBarreBouton(null,$this->makeLinkBoutonRetour(2));

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeurs(array());
	$form->setValeur("datedeb", $form->timeToTextDate(time()));
}

echo $form->openForm("Se connecter", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeInput("identifiant", "identifiant", "Identifiant (*)", $form->getValeur("identifiant"));
echo $form->makePassword("password", "password", "Mot de passe (*)" );
echo $form->makeNoteObligatoire();
echo $form->makeButton("Se connecter");
echo $form->closeForm();
/*
echo "<pre>SESSION";
print_r($_SESSION);
echo "</pre>";

echo "<pre>GLOBALS";
print_r($GLOBALS);
echo "</pre>";
*/
?>