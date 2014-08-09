<?php
$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
$this->db->sqlUpdate(  "tournois",
                        array(  "id"=>intval($form->getValeur("id_tournoi"))),
                        array(  "commentaires"=>$form->getValeur("commentaires")));
header("Location: ".$form->getValeur("from"));