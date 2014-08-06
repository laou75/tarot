<?php
$form = new Formulaire();
$form->setValeurs($_POST);
$err="";
$this->db->sqlUpdate(  "parties",
                        array(  "id"=>intval($form->getValeur("id_partie")),
                                "id_tournoi"=>intval($form->getValeur("id_tournoi")),
                                "id_session"=>intval($form->getValeur("id_session"))),
                        array(  "commentaires"=>$form->getValeur("commentaires")));
Header("Location: ".$form->getValeur("from"));