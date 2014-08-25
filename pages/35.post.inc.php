<?php
$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
$this->db->sqlUpdate(  "sessions",
                        array(  "id_tournoi"    =>  intval($form->getValeur("id_tournoi")),
                                "id"    =>  intval($form->getValeur("id_session"))),
                        array(  "commentaires"=>$form->getValeur("commentaires")));
header("Location: ".$form->getValeur("from"));
