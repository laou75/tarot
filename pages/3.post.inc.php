<?php
include_once(PATH_ROOT . '/include/tournoi.form.inc.php');

$this->db->sqlInsert("tournois", $form->getValeurs());
header("Location: ".$form->getValeur("from"));