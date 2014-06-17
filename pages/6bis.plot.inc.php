<?php
include("../jpgraph/jpgraph.php");
include("../jpgraph/jpgraph_line.php");
include("../include/config.inc.php");
include("../class/db.class.php");

$id_tournoi=$_GET["id_tournoi"];

// Create the graph. These two calls are always required
$graph  = new Graph(700, 500,"auto");
$graph->SetScale( "textlin");
$graph->xaxis->title->Set("Sessions");
$graph->yaxis->title->Set("Points");
$graph->legend->Pos(0.05,0.9,"left","bottom");
$graph->SetBackgroundGradient('blue','cyan',GRAD_HOR,BGRAD_MARGIN);
$graph->SetMargin(50,50,30,5);

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

$i=0;
$couleur=array("blue", "red", "green", "cyan", "orange", "black", "silver", "pink", "purple", "yellow");

foreach($aTabJ as $idJ => $det)
{
	// Some data
	$old=0;
	$ydata=array();
	$ydata[] = 0;
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
		$ydata[]=$old;
	}
	// Create the linear plot
	$lineplot[$i]=new LinePlot($ydata);
	$lineplot[$i]->value->Show();
	$lineplot[$i]->SetColor($couleur[$i]);
	$lineplot[$i]->SetLegend($det->prenom." ".$det->nom);
	$lineplot[$i]->mark->SetType(MARK_FILLEDCIRCLE);
	$lineplot[$i]->mark->SetFillColor($couleur[$i]);
	// Add the plot to the graph
	$graph->Add($lineplot[$i]);
	$i++;
}

// Display the graph
$graph->Stroke();