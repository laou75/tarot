<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$req=	"delete ".
		"from	r_parties_joueurs ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"))." ".
		"and	id_session = " . intval($form->getValeur("id_session"))." ".
		"and	id_partie = " . intval($form->getValeur("id"));
$this->db->sql_execute($req);
$req=	"delete ".
		"from	parties ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"))." ".
		"and	id_session = " . intval($form->getValeur("id_session"))." ".
		"and	id = " . intval($form->getValeur("id"));
$this->db->sql_execute($req);
Header("Location: ".$form->getValeur("from"));