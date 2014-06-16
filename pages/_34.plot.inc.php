<?php
include("../jpgraph/jpgraph.php");
include("../jpgraph/jpgraph_line.php");
include("../include/config.inc.php");
include("../class/db.class.php");

$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

// Create the graph. These two calls are always required
//$graph  = new Graph(350, 250,"auto");    
$graph  = new Graph(700, 500,"auto");
$graph->SetScale( "textlin");
$graph->xaxis->title->Set("Partie");
$graph->yaxis->title->Set("Points");
$graph->legend->Pos(0.05,0.9,"left","bottom");
$graph->SetBackgroundGradient('blue','cyan',GRAD_HOR,BGRAD_MARGIN);
$graph->SetMargin(50,50,30,5);

$req = "select A.id_joueur, B.nom, B.prenom from r_sessions_joueurs A, joueurs B where A.id_tournoi=$id_tournoi and A.id_session= $id_session and B.id=A.id_joueur order by A.id_joueur asc";
$db->sql_open_cur($res, $req);
$nb = $db->sql_count_cur($res);
$i=0;
$couleur=array("blue", "red", "green", "cyan", "orange", "black", "pink", "purple", "yellow");
while	($row=$db->sql_fetch_cur($res))
{
	// Some data
	$req2 = "select points from r_parties_joueurs where id_tournoi=$id_tournoi and id_session= $id_session and id_joueur= $row->id_joueur order by id_partie asc";
	$db->sql_open_cur($res2, $req2);
	$nb2 = $db->sql_count_cur($res2);
	$old=0;
	$ydata=array();
	$ydata[] = 0;
	while($row2=$db->sql_fetch_cur($res2))
	{

		$old	=	$old + $row2->points;
		$ydata[]=	$old;

	}
	$db->sql_free_result($res2);
	// Create the linear plot
	$lineplot[$i]=new LinePlot($ydata);
	$lineplot[$i]->value->Show();
	$lineplot[$i]->SetColor($couleur[$i]);
//	$lineplot[$i]->SetFillGradient($couleur[$i],'white');
	
	$lineplot[$i]->SetLegend($row->prenom." ".$row->nom);
	
	$lineplot[$i]->mark->SetType(MARK_FILLEDCIRCLE);
	$lineplot[$i]->mark->SetFillColor($couleur[$i]);
	
	// Add the plot to the graph
	$graph->Add($lineplot[$i]);
	$i++;
}
$db->sql_free_result($res);

// Display the graph
$graph->Stroke();
?> 