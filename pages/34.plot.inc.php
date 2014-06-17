<?php
include("../jpgraph/jpgraph.php");
include("../jpgraph/jpgraph_line.php");
include("../include/config.inc.php");
include("../class/db.class.php");

$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

// Create the graph. These two calls are always required
$graph  = new Graph(700, 500,"auto");
$graph->SetScale( "textlin");
$graph->xaxis->title->Set("Partie");
$graph->yaxis->title->Set("Points");
$graph->legend->Pos(0.05,0.9,"left","bottom");
$graph->SetBackgroundGradient('blue','cyan',GRAD_HOR,BGRAD_MARGIN);
$graph->SetMargin(50,50,30,5);

//	R�cup�ration des joueurs
$req =	"select A.id_joueur, B.nom, B.prenom ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=$id_tournoi ".
		"and	A.id_session= $id_session ".
		"and	B.id=A.id_joueur ".
		"order by A.id_joueur asc";
$db->sql_open_cur($res, $req);
$nb = $db->sql_count_cur($res);
while	($row=$db->sql_fetch_cur($res))
{
	$aTabJ[$row->id_joueur] = $row;
}
$db->sql_free_result($res);


//	R�cup�ration des parties
$req0 =	"select id ".
		"from	parties ".
		"where	id_tournoi=$id_tournoi ".
		"and	id_session= $id_session ".
		"order by id asc";
$db->sql_open_cur($res0, $req0);
$nb = $db->sql_count_cur($res0);
while	($row0=$db->sql_fetch_cur($res0))
{
	$aTabPar[$row0->id] = $row0;
}
$db->sql_free_result($res0);


$i=0;
$couleur=array("blue", "red", "green", "cyan", "orange", "black", "pink", "purple", "yellow");
foreach($aTabJ as $idJ => $detJ)
{
	// Some data
	$old=0;
	$ydata=array();
	$ydata[] = 0;
	foreach($aTabPar as $idP => $detP)
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
		$old	=	$old + $truc;
		$ydata[]=	$old;
	}
	// Create the linear plot
	$lineplot[$i]=new LinePlot($ydata);
	$lineplot[$i]->value->Show();
	$lineplot[$i]->SetColor($couleur[$i]);
	$lineplot[$i]->SetLegend($detJ->prenom." ".$detJ->nom);
	$lineplot[$i]->mark->SetType(MARK_FILLEDCIRCLE);
	$lineplot[$i]->mark->SetFillColor($couleur[$i]);
	
	// Add the plot to the graph
	$graph->Add($lineplot[$i]);
	$i++;
}

// Display the graph
$graph->Stroke();