<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$req=	"delete ".
		"from	r_parties_joueurs ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
		"and	id_session = " . intval($form->getValeur("id"));
$this->db->sqlExecute($req);
$req=	"delete ".
		"from	parties ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
		"and	id_session = " . intval($form->getValeur("id"));
$this->db->sqlExecute($req);
$req=	"delete ".
		"from	r_sessions_joueurs ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
		"and	id_session = " . intval($form->getValeur("id"));
$this->db->sqlExecute($req);
$req=	"delete ".
		"from	sessions ".
		"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
		"and	id = " . intval($form->getValeur("id"));
$this->db->sqlExecute($req);
header("Location: ".$form->getValeur("from"));