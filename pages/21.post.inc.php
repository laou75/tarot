<?php
include_once(PATH_ROOT . '/include/joueur.form.inc.php');
if ($err=="")
{
	$this->db->sqlInsert("joueurs", $form->getValeurs());
	header("Location: ".$form->getValeur("from"));
}