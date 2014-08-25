<?php
include_once(PATH_ROOT . '/include/joueur.form.inc.php');
if ($err=="")
{
	$this->db->sqlUpdate("joueurs", array("id"=>$form->getValeur("id")), $form->getValeurs());
	header("Location: ".$form->getValeur("from"));
}
