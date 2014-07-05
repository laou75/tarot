<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$req=	"delete ".
		"from	r_parties_joueurs ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"));
$this->db->sql_execute($req);
$req=	"delete ".
		"from	parties ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"));
$this->db->sql_execute($req);

$req=	"delete ".
		"from	r_sessions_joueurs ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"));
$this->db->sql_execute($req);
$req=	"delete ".
		"from	sessions ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi"));
$this->db->sql_execute($req);

$req=	"delete ".
		"from	tournois ".
		"where	id = " . intval($form->getValeur("id_tournoi"));
$this->db->sql_execute($req);
header("Location: ".$form->getValeur("from"));