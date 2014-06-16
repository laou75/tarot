<?
echo $this->drawBarreBouton(
	null,
	$this->makeBouton($_SERVER["HTTP_REFERER"], $this->makeImg("retour.gif")."&nbsp;Retour", "Retour"));
$id_tournoi=$_GET["id_tournoi"];

/*
 * 	TABLEAU
 */
$req =	"select distinct A.id_joueur, B.nom, B.nickname, B.prenom, B.portrait ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=$id_tournoi ".
		"and	B.id=A.id_joueur ".
		"order by A.id_joueur asc";
$this->db->sql_open_cur($res, $req);
$nb = $this->db->sql_count_cur($res);
while	($row=$this->db->sql_fetch_cur($res))
{
	$aTab[$row->id_joueur] = $row;
//	$entete[] = $this->lienPortrait($row->portrait, substr($row->prenom,0,1).". ".substr($row->nom,0,1).".", $row->prenom." ".$row->nom);
	$nick=isset($row->nickname)?$row->nickname:$row->prenom." ".substr($row->nom,0,1).".";
	$entete[] = $this->lienPortrait($row->portrait, $nick, $row->prenom." ".$row->nom);
}
$this->db->sql_free_result($res);

$req0 =	"select id ".
		"from	sessions ".
		"where	id_tournoi=$id_tournoi ".
		"order by id asc";
$this->db->sql_open_cur($res0, $req0);
$nb = $this->db->sql_count_cur($res0);
while	($row0=$this->db->sql_fetch_cur($res0))
{
	$aTabSess[$row0->id] = $row0;
}
$this->db->sql_free_result($res0);
?>
<center>
	<table>
		<tr><th>Statistiques</th></tr>
		<tr>
			<td align='center'>
<?
echo $this->openListe($entete);
foreach($aTabSess as $idS => $detS)
{
	$data=array();
	foreach($aTab as $idJ => $detJ)
	{
		$req2 = "select sum(points) as points, id_tournoi, id_session ".
				"from	r_parties_joueurs ".
				"where	id_tournoi= $id_tournoi ".
				"and	id_session= $idS ".
				"and	id_joueur = $idJ ".
				"group by id_tournoi, id_session";
/*
 		$db->sql_select($row2, $req2);
		$data[]=$row2->points;
*/
		$ret=$db->sql_select($row2, $req2);
		if ($ret>0)
			$data[]=sprintf("%01.1f", $row2->points);
		elseif ($ret==-100)
			$data[]="---";
	}
	echo $this->ligneListe(	$data , null, "align='right'");
}
echo $this->closeListe();
/*
echo"<pre>";
print_r($data);
echo"</pre>";
*/
/*
 * 	COURBE
 */
?>
			</td>
		</tr>
		<tr>
			<td>
				<img src="pages/6.plot.inc.php?<?=$_SERVER["QUERY_STRING"]; ?>">
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr><th>Cumul des pertes</th></tr>
		<tr>
			<td align='center'>
<?
echo $this->openListe($entete);

$cumul=array();
foreach($aTabSess as $idS => $detS)
{
	$data=array();
	foreach($aTab as $idJ => $detJ)
	{
		$req2 = "select sum(points) as CUMUL, id_tournoi, id_session ".
				"from	r_parties_joueurs ".
				"where	id_tournoi= $id_tournoi ".
				"and	id_session= $idS ".
				"and	id_joueur = $idJ ".
				"group by id_tournoi, id_session";
		if	($db->sql_select($row2, $req2)==-100)
			$truc=0;
		elseif ($row2->CUMUL>0)
			$truc=0;
		else
			$truc=$row2->CUMUL;
		if	(array_key_exists($idJ, $cumul))
			$cumul[$idJ]=$cumul[$idJ]+$truc;
		else
			$cumul[$idJ]=$truc;
//		$data[]=$cumul[$idJ];
		$data[]=sprintf("%01.1f", $cumul[$idJ]);
	}
	echo $this->ligneListe(	$data , null, "align='right'");
}
echo $this->closeListe();
?>
			</td>
		</tr>
		<tr>
			<td>
				<img src="pages/6bis.plot.inc.php?<?=$_SERVER["QUERY_STRING"]; ?>">
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr><th>Répartition des pertes</th></tr>
		<tr>
			<td align='center'>
				<img src="pages/6ter.plot.inc.php?<?=$_SERVER["QUERY_STRING"]; ?>">
			</td>
		</tr>
	</table>
</center>