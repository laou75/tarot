<?php
echo $this->drawBarreBouton(
	null,
	$this->makeBouton($_SERVER["HTTP_REFERER"], $this->makeImg("retour.gif")."&nbsp;Retour", "Retour"));

$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

//	R�cup�ration des joueurs
$req =	"select A.id_joueur, B.nom, B.prenom, B.nickname, B.portrait ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=".$id_tournoi." ".
		"and	A.id_session= $id_session ".
		"and	B.id=A.id_joueur ".
		"order by A.id_joueur asc";
$db->sql_open_cur($res, $req);
if ($this->db->sql_count_cur($res)<1)
	echo("Pas de joueurs !");
else
{
	while	($row=$db->sql_fetch_cur($res))
	{
		$aTabJ[$row->id_joueur] = $row;
		$nick=isset($row->nickname)?$row->nickname:$row->prenom." ".substr($row->nom,0,1).".";
		$entete[] = $this->lienPortrait($row->portrait, $nick, $row->prenom." ".$row->nom);
	}
	$db->sql_free_result($res);

	$req0 =	"select id ".
			"from	parties ".
			"where	id_tournoi=$id_tournoi ".
			"and	id_session=".$id_session." ".
			"order by id asc";
	$db->sql_open_cur($res0, $req0);
	if ($this->db->sql_count_cur($res0)<1)
		echo("Pas de parties!");
	else
	{
		while	($row0=$db->sql_fetch_cur($res0))
		{
			$aTabPar[$row0->id] = $row0;
		}
		$db->sql_free_result($res0);
		
?>
<div  class="text-center">
	<table>
		<tr><th>Statistiques</th></tr>
		<tr>
			<td align='center'>
<?php
		echo $this->openListe($entete);
		$cumul=array();
		foreach($aTabPar as $idP => $detP)
		{
			$data=array();
			foreach($aTabJ as $idJ => $detJ)
			{
				$req2 = "select sum(points) as CUMUL, id_tournoi, id_session, id_partie ".
						"from	r_parties_joueurs ".
						"where	id_tournoi= $id_tournoi ".
						"and	id_session= $id_session ".
						"and	id_partie= $idP ".
						"and	id_joueur = $idJ ".
						"group by id_tournoi, id_session, id_partie";
				if	($db->sql_select($row2, $req2)==-100)
					$truc=0;
				else
					$truc=$row2->CUMUL;
				if	(array_key_exists($idJ, $cumul))
					$cumul[$idJ]=$cumul[$idJ]+$truc;
				else
					$cumul[$idJ]=$truc;

				$data[]=sprintf("%01.1f", $cumul[$idJ]);
			}
			echo $this->ligneListe(	$data , null, "align='right'");
		}
		echo $this->closeListe();
?>
			</td>
		</tr>
		<tr>
			<td><img src="pages/34.plot.inc.php?<?=$_SERVER["QUERY_STRING"]; ?>"></td>
		</tr>
	</table>
</div>
<?php
	}
}