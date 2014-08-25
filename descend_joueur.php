<?php
define('PATH_ROOT', __DIR__);
include("include/includes.inc.php");

$req =	"SELECT * ".
		"FROM   r_sessions_joueurs ".
		"where  id_tournoi=".$_GET["id_tournoi"]." ".
		"and    id_session=".$_GET["id_session"]." ".
		"and    id_joueur=".$_GET["id_joueur"];
$db->sqlSelect($row, $req);

$req =	"SELECT * ".
		"FROM   r_sessions_joueurs ".
		"where  id_tournoi=".$_GET["id_tournoi"]." ".
		"and    id_session=".$_GET["id_session"]." ".
		"AND    position > ".$row->position." ".
		"ORDER BY position ASC LIMIT 1";
if	($db->sqlSelect($row2, $req) == 1)
{
	$req =	"UPDATE r_sessions_joueurs ".
			"SET    position = ".$row->position." ".
			"where  id_tournoi=".$_GET["id_tournoi"]." ".
			"and    id_session=".$_GET["id_session"]." ".
			"and    id_joueur=".$row2->id_joueur;
	$db->sqlExecute($req);
	$req =	"UPDATE r_sessions_joueurs ".
			"SET    position = ".$row2->position." ".
			"where  id_tournoi=".$_GET["id_tournoi"]." ".
			"and    id_session=".$_GET["id_session"]." ".
			"and    id_joueur=".$row->id_joueur;
	$db->sqlExecute($req);
}
header("Location: ".$_SERVER["HTTP_REFERER"]);
