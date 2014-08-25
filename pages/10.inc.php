<?php
$partie = new Partie($db);
$session = new Session($db);
$joueur = new Joueur($db);

$aJPar = array();

$id_tournoi = $_GET['id_tournoi'];
$id_session = $_GET['id_session'];

echo $this->drawBarreBouton(	
	array(
        $this->makeLinkBouton(32, 'id_tournoi=' . $id_tournoi . '&amp;id_session=' . $id_session),
        $this->makeLinkBouton(33, 'id_tournoi=' . $id_tournoi . '&amp;id_session=' . $id_session),
        '<a class="btn btn-default btn-sm" data-toggle="modal"  data-target="#myModal" href="ajax/ajaxGetComment.php?id_session='.$id_session.'&amp;id_tournoi='.$id_tournoi.'"><span class="glyphicon glyphicon-comment"></span> Commentaire</a>',
        $this->makeLinkBouton(34, 'id_tournoi=' . $id_tournoi . '&amp;id_session=' . $id_session),
        '&nbsp;&nbsp;&nbsp;',
        $this->makeLinkBouton(11, 'id_tournoi=' . $id_tournoi . '&amp;id_session=' . $id_session)),
		$this->makeLinkBoutonRetour(30, 'id_tournoi=' . $id_tournoi)
		);

$podium = $session->getPodium($id_tournoi, $id_session);
if(count($podium)>0)
{
    echo '<div class="row"><h4>Classement</h4>';
    foreach($podium as $infoJ)
    {
        if ($infoJ->classement >3)
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
        echo '<div class="col-xs-4">
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
$row = $session->getSessionById($id_tournoi, $id_session);
echo '<div class="panel panel-default"><div class="panel-body" id="comment_'.$id_tournoi.'_'.$id_session.'">' . (!empty($row['commentaires']) ? nl2br($row['commentaires']) : 'Pas de commentaires') . '</div></div></div>';

//	R�cup�rer la liste des joueurs de la session
$tabJoueursSession = $joueur->getJoueursBySession($id_tournoi, $id_session);
foreach($tabJoueursSession as $k => $row) {
    $aJSess[$row->ID] = $row;
    $popJoueur = " data-placement=\"bottom\" data-container=\"body\" data-toggle=\"popover\" data-content='".$this->getPortrait($row->portrait)."'";
    $entete[] = '<span id="joueur_'.$row->ID.'" '.$popJoueur.'>'.$this->getNickname($row).'</span>';
}

$entete[] = 'Contrat';
$entete[] = 'Commentaire';

echo '<div class="row"><h4>Liste des parties</h4>'.$this->openListe($entete, true);
$tabParties = $partie->getPartiesBySession($id_tournoi, $id_session);
$cumul=array();
foreach	($tabParties as $k => $row)
{
	$petitaubout=($row->petitaubout==1) ? 'oui' : 'non';

    $contratreussi =    ($row->annonce_reussie==1)
                        ? '<span class=\'glyphicon glyphicon-thumbs-up btn btn-sm btn-success\'></span>'
                        : '<span class=\'glyphicon glyphicon-thumbs-down btn btn-sm btn-danger\'></span>';
    $idPartieJoueur='info_'.$id_tournoi.'_'.$id_session.'_'.$row->id;

    $htmlContrat=   '<div>'.$row->annonce.' '.$contratreussi.' </div>'.
                    '<div>Bouts : '.$row->nombre_bouts.'</div>'.
                    '<div>Petit au bout : '.$petitaubout.'</div>'.
                    (($row->poignee!="aucune") ? '<div>Poignée : '.$row->poignee.'</div>' : '').
                    '<div>Points : '.$row->points.'</div>';

    $hrefContrat = "<a href=\"#\" id=\"".$idPartieJoueur."\" data-placement=\"left\" data-container=\"body\" data-toggle=\"popover\" data-content=\"".$htmlContrat."\">" .
        $row->annonce ."</a>";

	//	R�cup�rer la liste des joueurs de la partie
    $tabJoueurs = $joueur->getJoueursByPartie($id_tournoi, $id_session, $row->id);

    foreach($tabJoueurs as $kJ => $row2)
    {
        $aJPar[$row2->id_joueur]=$row2;
        if	(array_key_exists($row2->id_joueur, $cumul))
            $cumul[$row2->id_joueur] = $cumul[$row2->id_joueur] + $row2->points;
        else
            $cumul[$row2->id_joueur] = $row2->points;
    }

	$data=array();
	foreach	($aJSess as $idJ => $detJ) {
        $class='';
		switch ($aJPar[$idJ]->type) {
			case 'preneur':
                $class = $aJPar[$idJ]->points >0 ? 'bg-success' : 'bg-danger';
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case 'called':
                $class = $aJPar[$idJ]->points >0 ? 'bg-info' : 'bg-warning';
                $pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case 'defense':
				$class='';
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case 'mort':
			default:
				$class='';
				$pts = '---';
				break;
		}
        $data[] =	'<div class="text-right ' . $class . '">' . $pts . '</div>';
	}

    $data[] = $hrefContrat;
	if ($row->commentaires)
		$data[]='<div id="comment_'.$id_tournoi.'_'.$id_session.'_'.$row->id.'">'.nl2br($row->commentaires).'</div>';

        $params = 'id_partie='.$row->id.'&amp;id_session='.$id_session.'&amp;id_tournoi='.$id_tournoi;

        echo $this->ligneListe(
            $data,
            array(
                    $this->makeLinkBouton(12, $params),	// modifier
                    $this->makeLinkBouton(13, $params),	// supprimer
                    '<a class="btn btn-default btn-sm" data-toggle="modal"  data-target="#myModal" href="ajax/ajaxGetComment.php?'.$params.'"><span class="glyphicon glyphicon-comment"></span> Commentaire</a>',
                    )
            );
}
if (count($tabParties)>0)
{
	$data=array();
	foreach	($aJSess as $idJ => $detJ) {
        $data[]='<div class="text-right"><strong>'.sprintf("%+d", $cumul[$idJ]).'</strong></div>';
	}
	$data[]='';
	echo $this->ligneListe($data, array('<strong>Cumul</strong>'), null, 'info');
}
echo $this->closeListe().'</div>';
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>