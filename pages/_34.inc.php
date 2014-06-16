<?
echo $this->drawBarreBouton(
	null,
	$this->makeBouton($_SERVER["HTTP_REFERER"], $this->makeImg("retour.gif")."&nbsp;Retour", "Retour"));

$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

?>
<center>
<table>
	<tr><th>Statistiques</th></tr>
	<tr>
		<td align='center'>
<?
//	Récupération des joueurs
$req =	"select A.id_joueur, B.nom, B.prenom, B.portrait ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=$id_tournoi ".
		"and	A.id_session= $id_session ".
		"and	B.id=A.id_joueur ".
		"order by A.id_joueur asc";
$db->sql_open_cur($res, $req);
while	($row=$db->sql_fetch_cur($res))
{
	$aTabJ[$row->id_joueur] = $row;
	$entete[] = $this->lienPortrait($row->portrait, substr($row->prenom,0,1).". ".substr($row->nom,0,1).".", $row->prenom." ".$row->nom);
}
$db->sql_free_result($res);
//	Récupération des parties
$req0 =	"select id ".
		"from	parties ".
		"where	id_tournoi=$id_tournoi ".
		"and	id_session= $id_session ".
		"order by id asc";
$db->sql_open_cur($res0, $req0);
while	($row0=$db->sql_fetch_cur($res0))
{
	$aTabPar[$row0->id] = $row0;
}
$db->sql_free_result($res0);

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
			
		$data[]=sprintf("%+d", $cumul[$idJ]);
//		$data[]="<table width='100%'><tr><td width='50%'>".sprintf("%+d", $truc)."</td><td width='50%'>".sprintf("%+d", $cumul[$idJ])."</td></tr></table>";
	}
	echo $this->ligneListe(	$data );
}
echo $this->closeListe();

?>
		</td>
	</tr>
	<tr>
		<td><img src="pages/34.plot.inc.php?<?=$_SERVER["QUERY_STRING"]; ?>"></td>
	</tr>
</table>
</center>