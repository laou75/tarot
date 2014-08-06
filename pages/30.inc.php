<?php
$id_tournoi = $_GET['id_tournoi'];

echo $this->drawBarreBouton(    array(  $this->makeLinkBouton(31, 'id_tournoi=' . $id_tournoi),
                                        $this->makeLinkBouton(6, 'id_tournoi=' . $id_tournoi)),
                                $this->makeLinkBoutonRetour(2));

echo $this->openListe(array('Date', 'Joueurs', 'Commentaires'), true);

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
									$joueurs,
									$row->commentaires
									),
							array(	$this->makeLinkBouton(10, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// Parties
									$this->makeLinkBouton(32, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// modifier
									$this->makeLinkBouton(33, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi),	// supprimer
									$this->makeLinkBouton(34, 'id_session='.$row->id.'&amp;id_tournoi='.$id_tournoi)	// Stats
									)
							);
}
echo $this->closeListe();