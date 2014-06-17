<?php
include("../jpgraph/jpgraph.php");
include("../jpgraph/jpgraph_pie.php");
include("../jpgraph/jpgraph_pie3d.php");
include("../include/config.inc.php");
include("../class/db.class.php");

$id_tournoi=$_GET["id_tournoi"];

// Create the graph. These two calls are always required
$graph  = new PieGraph(700, 500,"auto");
$graph->SetShadow();
$graph->title->Set("R�partition des pertes");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->SetBackgroundGradient('blue','cyan',GRAD_HOR,BGRAD_MARGIN);

$req =	"select distinct A.id_joueur, A.id_session, B.nom, B.prenom ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=$id_tournoi ".
		"and	B.id=A.id_joueur ".
		"order by A.id_joueur asc, A.id_session asc";
$db->sql_open_cur($res, $req);
$nb = $db->sql_count_cur($res);
while	($row=$db->sql_fetch_cur($res))
{
	$aTabJ[$row->id_joueur] = $row;
}
$db->sql_free_result($res);


$req0 =	"select id ".
		"from	sessions ".
		"where	id_tournoi=$id_tournoi ".
		"order by id asc";
$db->sql_open_cur($res0, $req0);
$nb = $db->sql_count_cur($res0);
while	($row0=$db->sql_fetch_cur($res0))
{
	$aTabSess[$row0->id] = $row0;
}
$db->sql_free_result($res0);


$couleur=array("blue", "red", "green", "cyan", "orange", "black", "silver", "pink", "purple", "yellow");

$donnees=array();
$aJoueurs=array();
$total=0;
foreach($aTabJ as $idJ => $det)
{
	$old=0;
	foreach($aTabSess as $idS => $detS)
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
		$old=$old+$truc;
	}
	$total+=$old;
	$donnees[]=$old;
	$aJoueurs[]=$det->prenom." ".$det->nom;
}

foreach($donnees as $id => $valeur)
{
	if ($valeur!=0)
		$donnees[$id]=$valeur/$total*100;
	else
		$donnees[$id]=0;
}

$p1 = new PiePlot3D($donnees);
$p1->SetSliceColors($couleur);
$p1->SetSize(0.5);
$p1->SetCenter(0.45);
$p1->SetLegends($aJoueurs);

$graph->Add($p1);

// Display the graph
$graph->Stroke();