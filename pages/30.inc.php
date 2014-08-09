<?php
$id_tournoi = $_GET['id_tournoi'];

echo $this->drawBarreBouton(    array(  $this->makeLinkBouton(4, 'id_tournoi=' . $id_tournoi),
                                        $this->makeLinkBouton(5, 'id_tournoi=' . $id_tournoi),
                                        $this->makeLinkBouton(7, 'id_tournoi=' . $id_tournoi),
                                        $this->makeLinkBouton(6, 'id_tournoi=' . $id_tournoi),
                                        '&nbsp;&nbsp;&nbsp;',
                                        $this->makeLinkBouton(31, 'id_tournoi=' . $id_tournoi)),
                                $this->makeLinkBoutonRetour(2));


$tournoi = new Tournoi($db);

$podium = $tournoi->getPodium($id_tournoi);
if(count($podium)>0)
{
    echo '<div class="row"><h4>Classement</h4>';
    foreach($podium as $infoJ)
    {
        if ($infoJ->classement>3)
            break;
        $classJ = 'label-default';
        switch($infoJ->classement)
        {
            case 1:
                $classJ = 'label-success';
                break;
            case 2:
                $classJ = 'label-info';
                break;
            case 3:
                $classJ = 'label-warning';
                break;
        }
        echo '<div class="col-md-4">
             <div class="panel panel-default">
                <div class="panel-heading">' . $infoJ->prenom . ' ' . $infoJ->nom . ' <span class="label ' . $classJ . '">' . $this->getLibClassement($infoJ->classement) . '</span></div>
                <div class="panel-body">'.
            $this->getPortrait($infoJ->portrait, 'Portrait de ' . $infoJ->prenom . ' ' . $infoJ->nom, ' class="img-circle" align="left"').
            ' <span class="badge">' . $infoJ->cumul . '</span>' .
            '</div></div>
        </div>';
    }
    echo '</div>';
}

echo '<div class="row"><h4>Commentaires</h4>';
$row = $tournoi->getTournoiById($id_tournoi);
echo '<div class="panel panel-default"><div class="panel-body">'.(!empty($row['commentaires']) ? nl2br($row['commentaires']) : 'Pas de commentaires').'</div></div></div>';

echo '<div class="row"><h4>Liste des sessions</h4>'.$this->openListe(array('Commencée le', 'Finie le', 'Joueurs', 'Commentaires'), true);
$sessions = new Session($this->db);
$aTabSession = $sessions->getSessionByTournoi($id_tournoi);
foreach($aTabSession as $kS => $row)
{
    $res2=null;
	$req2 = "select	* ".
			"from	r_sessions_joueurs A, joueurs B ".
			"where	A.id_tournoi=" . intval($id_tournoi) . " ".
			"and	A.id_session=" . intval($row->id) . " ".
			"and	B.id = A.id_joueur ".
			"order by A.position asc";
	$this->db->sqlOpenCur($res2, $req2);
	$nb2 = $this->db->sqlCountCur($res2);
    $joueurs='<div>';
    while	($row2=$this->db->sqlFetchCur($res2))
	{
        $popJoueur = " data-placement=\"bottom\" data-container=\"body\" data-toggle=\"popover\" data-content='".$this->getPortrait($row2->portrait)."'";
        $joueurs .= '<div>'.
                    $this->makeLink('remonte_joueur.php?id_tournoi=' . $row2->id_tournoi . '&amp;id_session=' . $row2->id_session . '&amp;id_joueur=' . $row2->id_joueur,
                                    '<span class="glyphicon glyphicon-chevron-up text-primary"></span>',
                                    'Monte').
                    $this->makeLink('descend_joueur.php?id_tournoi=' . $row2->id_tournoi . '&amp;id_session=' . $row2->id_session . '&amp;id_joueur=' . $row2->id_joueur,
                                    '<span class="glyphicon glyphicon-chevron-down text-primary"></span>',
                                    'Descend').
                    '&nbsp;'.
                    '<span id="joueur_' . $row2->id_session . '_' . $row2->id_joueur . '" ' . $popJoueur . '>' . $this->getNickname($row2) . '</span>' .
                    '</div>';
	}
	$joueurs .= '</div>';
	$this->db->sqlFreeResult($res2);
	echo $this->ligneListe( array(	strftime("%d", $row->datedeb) . '/' . strftime("%m", $row->datedeb) . '/' . strftime("%Y", $row->datedeb),
                                    (!empty($row->datefin))  ? strftime("%d", $row->datefin)."/".strftime("%m", $row->datefin)."/".strftime("%Y", $row->datefin) : "en cours",
									$joueurs,
									nl2br($row->commentaires)
									),
							array(	$this->makeLinkBouton(10, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// Parties
									$this->makeLinkBouton(32, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// modifier
									$this->makeLinkBouton(33, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// supprimer
                                    $this->makeLinkBouton(34, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),
									$this->makeLinkBouton(35, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi)	// Stats
									)
							);
}
echo $this->closeListe().'</div>';