<?php
$joueurs = new Joueur($db);
$parties = new Partie($db);

$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

echo $this->drawBarreBouton(
    null,
    $this->makeLinkBoutonRetour(10, 'id_tournoi='.$id_tournoi.'&id_session='.$id_session));

//	Récupération des joueurs
$tabJoueurs = $joueurs->getJoueursBySession($id_tournoi, $id_session);
if (count($tabJoueurs)<1)
	echo("Pas de joueurs !");
else
{
    foreach($tabJoueurs as $k => $row)
    {
		$aTabJ[$row->id_joueur] = $row;
        $popJoueur = " data-placement=\"bottom\" data-container=\"body\" data-toggle=\"popover\" data-content='".$this->getPortrait($row->portrait)."'";
        $entete[] = '<span id="joueur_'.$row->ID.'" '.$popJoueur.'>'.$this->getNickname($row).'</span>';
	}
    $aTabPar = $parties->getPartiesBySession($id_tournoi, $id_session);
    if (count($aTabPar)<1)
        echo("Pas de parties!");
    else
    {
?>
<div class="row">
    <h4>Statistiques</h4>
    <div class="col-sm-6">
<?php
        $entete[] = '<span class="glyphicon glyphicon-comment"></span>';
		echo $this->openListe($entete);
		$cumul=array();
		foreach($aTabPar as $idP => $detP)
		{
			$data=array();
			foreach($aTabJ as $idJ => $detJ)
			{
				$req2 = "select sum(points) as CUMUL, id_tournoi, id_session, id_partie ".
						"from	r_parties_joueurs ".
                        "where	id_tournoi=" . intval($id_tournoi) . " ".
                        "and	id_session=" . intval($id_session) . " ".
						"and	id_partie= $idP ".
						"and	id_joueur = $idJ ".
						"group by id_tournoi, id_session, id_partie";
				if	($db->sqlSelect($row2, $req2)==-100)
					$truc=0;
				else
					$truc=$row2->CUMUL;
				if	(array_key_exists($idJ, $cumul))
					$cumul[$idJ]=$cumul[$idJ]+$truc;
				else
					$cumul[$idJ]=$truc;

				$data[]=sprintf("%01.1f", $cumul[$idJ]);
			}
            if(!empty($detP->commentaires))
            {
                $popP = " data-placement=\"bottom\" data-container=\"body\" data-toggle=\"popover\" data-content='" . htmlspecialchars($detP->commentaires, ENT_QUOTES) . "'";
                $data[] = '<span class="glyphicon glyphicon-comment text-primary"  id="partie_' . $idP . '"  ' . $popP . '"></span>';
            }
            else
            {
                $data[] = '<span class="glyphicon glyphicon-comment text-muted"></span>';
            }


			echo $this->ligneListe(	$data , null, "align='right'");
		}
		echo $this->closeListe();
?>
    </div>
    <div class="col-sm-6">
        <div id="container1" style="width:100%; height:400px;"></div>
    </div>
</div>
<?php
	}
}
