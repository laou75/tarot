<?php
$joueur = new Joueur($db);
$session = new Session($db);

$id_tournoi=$_GET["id_tournoi"];

echo $this->drawBarreBouton(
    null,
    $this->makeLinkBoutonRetour(2, 'id_tournoi='.$id_tournoi));
/*
 * 	TABLEAU
 */
$entete = array();
$aTab = $joueur->getJoueursByTournoi($id_tournoi);
foreach($aTab as $idJoueur => $row)
{
    $popJoueur = " data-placement=\"bottom\" data-container=\"body\" data-toggle=\"popover\" data-content='".$this->getPortrait($row->portrait)."'";
    $entete[] = '<span id="joueur_'.$row->id_joueur.'" '.$popJoueur.'>'.$this->getNickname($row).'</span>';
}

$aTabSess = $session->getSessionByTournoi($id_tournoi);
?>
<div class="row">
    <h4>Statistiques</h4>
	<div class="col-sm-6">
<?php
echo $this->openListe($entete, true);
foreach($aTabSess as $idS => $detS)
{
	$data=array();
	foreach($aTab as $idJ => $detJ)
	{
        $row2 = $session->getStatsSessionByJoueur($id_tournoi, $idS, $idJ);
		if (isset($row2))
			$data[]=sprintf("%01.1f", $row2->points);
		else
			$data[]="---";
	}
	echo $this->ligneListe(	$data ,
                            array(
                                $this->makeLinkFromId(  30,
                                                        '<span class="glyphicon glyphicon-eye-open"></span>', 'id_tournoi='.$id_tournoi.'&amp;id_session='.$idS,
                                                        'Voir session '.$idS)
                            ),
                            "align='right'");
}
echo $this->closeListe();
?>
    </div>
    <div class="col-sm-6">
        <div id="container1" style="width:100%; height:400px;"></div>
    </div>
</div>

<div class="row">
    <h4>Cumul des pertes</h4>
    <div class="col-sm-6">
<?php
echo $this->openListe($entete);
$cumul=array();
foreach($aTabSess as $idS => $detS)
{
	$data=array();
	foreach($aTab as $idJ => $detJ)
	{
        $row2 = $session->getStatsSessionByJoueur($id_tournoi, $idS, $idJ);
		if	(!isset($row2->points))
			$truc=0;
		elseif ($row2->points > 0)
			$truc=0;
		else
			$truc=$row2->points;
		if	(array_key_exists($idJ, $cumul))
			$cumul[$idJ]=$cumul[$idJ]+$truc;
		else
			$cumul[$idJ]=$truc;
		$data[]=sprintf("%01.1f", $cumul[$idJ]);
	}
}
echo $this->ligneListe(	$cumul, null, "align='right'");
echo $this->closeListe();
?>
    </div>
    <div class="col-sm-6">
        <div id="container2" style="width:100%; height:400px;"></div>
    </div>
</div>

<div class="row">
    <h4>Répartition des pertes</h4>
    <div class="col-sm-12">
        <div id="container3" style="width:100%; height:400px;"></div>
    </div>
</div>
