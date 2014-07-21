<?php
$partie = new Partie($db);
$joueur = new Joueur($db);

$aJPar = array();

$id_tournoi = $_GET["id_tournoi"];
$id_session = $_GET["id_session"];

echo $this->drawBarreBouton(	
	array(
		$this->makeLinkBouton(11, "id_tournoi=".$id_tournoi."&amp;id_session=".$id_session), 
		$this->makeLinkBouton(34, "id_tournoi=".$id_tournoi."&amp;id_session=".$id_session)),
		$this->makeLinkBoutonRetour(30, "id_tournoi=".$id_tournoi)
		);

//	R�cup�rer la liste des joueurs de la session
$tabJoueursSession = $joueur->getJoueursBySession($id_tournoi, $id_session);
foreach($tabJoueursSession as $k => $row) {
    $aJSess[$row->ID] = $row;
    $nick=isset($row->nickname) ? $row->nickname : $row->prenom." ".substr($row->nom, 0, 1).".";
    $entete[] = $this->lienPortrait($row->portrait, $nick, $row->prenom." ".$row->nom);
}

$entete[] = "Contrat";

echo $this->openListe($entete, true);
$tabParties = $partie->getPartiesBySession($id_tournoi, $id_session);
$cumul=array();

foreach	($tabParties as $k => $row)
{
	$petitaubout=($row->petitaubout==1) ? "oui" : "non";

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
		$infos="";
		switch ($aJPar[$idJ]->type) {
			case "preneur":
				$class="liste-joueur-preneur";
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case "called":
				$class="liste-joueur-appele";
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case "defense":
				$class="liste-joueur-defense";
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case "mort":
			default:
				$class="liste-joueur-mort";
				$pts = "-";
				break;
		}
		if ($aJPar[$idJ]->type=="mort")
			$data[]=	"<table width='100%'><tr><td class='".$class."' align='right'>&nbsp;</td></tr></table>";
		else
			$data[]=	"<table width='100%'><tr>".
						"<td class='".$class."' align='right'>".$pts."</td>".
						"</tr></table>";
	}

    $data[]= $hrefContrat;
	if ($row->commentaires)
		$data[]=$row->commentaires;

	echo $this->ligneListe(
		$data,
		array(	
				$this->makeLinkBouton(12, "id_partie=".$row->id."&amp;id_session=".$id_session."&amp;id_tournoi=".$id_tournoi),	// modifier 
				$this->makeLinkBouton(13, "id_partie=".$row->id."&amp;id_session=".$id_session."&amp;id_tournoi=".$id_tournoi),	// supprimer
				$this->makeLinkBouton(14, "id_partie=".$row->id."&amp;id_session=".$id_session."&amp;id_tournoi=".$id_tournoi)	// commentaire 
				)
		);
}

if (count($tabParties)>0)
{
	$data=array();
	foreach	($aJSess as $idJ => $detJ) {
		$data[]="<table width='100%'><tr><td class='resume-partie' align='right' width='60%'>".sprintf("%+d", $cumul[$idJ])."</td></tr></table>";
	}
	$data[]="";
	echo $this->ligneListe($data,array());
}
echo $this->closeListe();