<?php
$id_tournoi = $_GET["id_tournoi"];

echo $this->drawBarreBouton(
	array(
		$this->makeLinkBouton(31, "id_tournoi=".$id_tournoi), 
		$this->makeLinkBouton(6, "id_tournoi=".$id_tournoi)),
	$this->makeLinkBoutonRetour(2));

$req = "select * from sessions where id_tournoi=".$id_tournoi." order by datedeb desc, datefin desc";
$this->db->sql_open_cur($res, $req);
$nb = $this->db->sql_count_cur($res);
$i=0;
echo $this->openListe(array("Date", "joueurs", "Commentaires"), true);
while	($row=$this->db->sql_fetch_cur($res))
{
	$req2 = "select	* ".
			"from	r_sessions_joueurs A, joueurs B ".
			"where	A.id_tournoi=".$id_tournoi." ".
			"and	A.id_session=".$row->id." ".
			"and	B.id = A.id_joueur ".
			"order by A.position asc";
	$this->db->sql_open_cur($res2, $req2);
	$nb2 = $this->db->sql_count_cur($res2);
	$joueurs="<table width='100%'>";
	while	($row2=$this->db->sql_fetch_cur($res2))
	{
		$portrait=strlen($row2->portrait)>0?$row2->portrait:"inconnu.gif";
		$joueurs .= "<tr valign='middle'>".
					"	<td rowspan=2>".sprintf("%01d", $row2->position)."</td>".
					"	<td rowspan=2>".$this->lienPortrait($portrait, "<nobr>".$row2->prenom." ".$row2->nom."</nobr>")."</td>".
					"	<td>".
					$this->makeLink("remonte_joueur.php?id_tournoi=".$row2->id_tournoi."&id_session=".$row2->id_session."&id_joueur=".$row2->id_joueur, $this->makeImg("haut.gif", "Monter")).
					"	</td>".
					"</tr>".
					"<tr>".
					"	<td>".
					$this->makeLink("descend_joueur.php?id_tournoi=".$row2->id_tournoi."&id_session=".$row2->id_session."&id_joueur=".$row2->id_joueur, $this->makeImg("bas.gif", "Descendre")).
					"	</td>".
					"</tr>";
	}
	$joueurs.="</table>";
	$this->db->sql_free_result($res2);
	echo $this->ligneListe(
/*
							array(	strftime("%x", $row->datedeb), 
									$joueurs,
									$row->commentaires
									),
*/
							array(	strftime("%d", $row->datedeb)."/".strftime("%m", $row->datedeb)."/".strftime("%Y", $row->datedeb), 
									$joueurs,
									$row->commentaires
									),
							array(	$this->makeLinkBouton(10, "id_session=".$row->id."&id_tournoi=".$id_tournoi),	// Parties 
									$this->makeLinkBouton(32, "id_session=".$row->id."&id_tournoi=".$id_tournoi),	// modifier 
									$this->makeLinkBouton(33, "id_session=".$row->id."&id_tournoi=".$id_tournoi),	// supprimer
									$this->makeLinkBouton(34, "id_session=".$row->id."&id_tournoi=".$id_tournoi)	// Stats
									)
							);
}
echo $this->closeListe();
$this->db->sql_free_result($res);
?>