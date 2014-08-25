<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$joueurs = new Joueur($this->db);
$joueurs->deleteJoueur($_POST['id']);
if	(strlen($form->getValeur("portrait"))>0)
{
	@unlink($GLOBALS["Config"]["PATH"]["PORTRAIT"].$form->getValeur("portrait"));
	@unlink($GLOBALS["Config"]["PATH"]["PORTRAIT"]."mini/".$form->getValeur("portrait"));
}
header("Location: ".$form->getValeur("from"));
