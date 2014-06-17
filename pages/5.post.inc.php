<?php
include_once ("class/formulaire.class.php");

$form = new Formulaire();
$form->setValeurs($_POST);

$req=	"delete ".
		"from	r_parties_joueurs ".
		"where	id_tournoi = ".$form->getValeur("id_tournoi");
$this->db->sql_execute($req);
$req=	"delete ".
		"from	parties ".
		"where	id_tournoi = ".$form->getValeur("id_tournoi");
$this->db->sql_execute($req);

$req=	"delete ".
		"from	r_sessions_joueurs ".
		"where	id_tournoi = ".$form->getValeur("id_tournoi");
$this->db->sql_execute($req);
$req=	"delete ".
		"from	sessions ".
		"where	id_tournoi = ".$form->getValeur("id_tournoi");
$this->db->sql_execute($req);

$req=	"delete ".
		"from	tournois ".
		"where	id = ".$form->getValeur("id_tournoi");
$this->db->sql_execute($req);
Header("Location: ".$form->getValeur("from"));