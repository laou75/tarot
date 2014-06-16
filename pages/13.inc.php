<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();


if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeur("id", $_GET["id_partie"]);
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
	$this->db->sql_select_array($row, "select * from parties where id=".$form->getValeur("id")." and id_tournoi=".$form->getValeur("id_tournoi")." ");
	$form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$form->getValeur("id_tournoi")."&id_session=".$form->getValeur("id_session")));

echo $form->openForm("Supprimer une partie", "", "multipart/form-data");
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
echo $form->makeHidden("id_session", "id_session", $form->getValeur("id_session"));
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
if	(isset($warn) && strlen($warn)>0)
	echo $form->makeMsgWarning($warn);
echo $form->makeTexte("Du", strftime ("%x", $form->getValeur("date")));
echo $form->makeTexte("Prise par", $this->getJoueur($form->getValeur("id_preneur")));
if	(strlen($form->getValeur("id_second"))>0)
	echo $form->makeTexte("Appelé", $this->getJoueur($form->getValeur("id_second")));
echo $form->makeTexte("Commentaire", $form->getValeur("commentaires"));
echo $form->makeButton("Supprimer");
echo $form->closeForm();
?>