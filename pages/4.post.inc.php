<?php
include_once(PATH_ROOT . '/include/tournoi.form.inc.php');

$this->db->sqlUpdate("tournois", array("id"=>$form->getValeur("id")), $form->getValeurs());
header("Location: ".$form->getValeur("from"));
