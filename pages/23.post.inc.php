<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$this->db->sql_execute("delete from joueurs where id = " . intval($form->getValeur("id")));
if	(strlen($form->getValeur("portrait"))>0)
{
	@unlink($GLOBALS["Config"]["PATH"]["PORTRAIT"].$form->getValeur("portrait"));
	@unlink($GLOBALS["Config"]["PATH"]["PORTRAIT"]."mini/".$form->getValeur("portrait"));
}
Header("Location: ".$form->getValeur("from"));