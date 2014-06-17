<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
$this->db->sql_update("parties", array("id"=>$form->getValeur("id_partie"), "id_tournoi"=>$form->getValeur("id_tournoi"), "id_session"=>$form->getValeur("id_session")), array("commentaires"=>$form->getValeur("commentaires")));
Header("Location: ".$form->getValeur("from"));