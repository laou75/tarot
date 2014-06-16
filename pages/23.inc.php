<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();


if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeur("id", $_GET["id_joueur"]);
	$this->db->sql_select_array($row, "select * from joueurs where id=".$form->getValeur("id")." ");
	$form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(20));

echo $form->openForm("Supprimer un joueur", "", "multipart/form-data");
echo $form->makeHidden("id", "id", $form->getValeur("id"));
if	(strlen($form->getValeur("portrait"))>0)
	echo $form->makeHidden("portrait", "portrait", $form->getValeur("portrait"));
if (isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
if (isset($warn) && strlen($warn)>0)
	echo $form->makeMsgWarning($warn);
echo $form->makeTexte("Nom", $form->getValeur("nom"));
echo $form->makeTexte("Prénom", $form->getValeur("prenom"));
$image=(strlen($form->getValeur("portrait"))>0)?$this->makePortrait("mini/".$form->getValeur("portrait")):"";
if ($image!="")
	echo $form->makeTexte("Portrait", $image);
echo $form->makeButton("Supprimer");
echo $form->closeForm();
?>