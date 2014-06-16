<?php
$id_tournoi = $_GET["id_tournoi"];
$id_session = $_GET["id_session"];

echo $this->drawBarreBouton(	
	array(
		$this->makeLinkBouton(11, "id_tournoi=".$id_tournoi."&id_session=".$id_session), 
		$this->makeLinkBouton(34, "id_tournoi=".$id_tournoi."&id_session=".$id_session)),
	$this->makeLinkBoutonRetour(30, "id_tournoi=".$id_tournoi)
		);

//	Récupérer la liste des joueurs de la session
$req =	"select B.id, B.nom, B.prenom, B.portrait, A.position ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	A.id_tournoi=".$id_tournoi." ".
		"and	A.id_session=".$id_session." ".
		"and	B.id=A.id_joueur ".
		"order by A.position asc";
$this->db->sql_open_cur($res, $req);
$nbJSess = $this->db->sql_count_cur($res);
while	($row=$this->db->sql_fetch_cur($res))
{
	$aJSess[$row->id]=$row;
	$entete[] = $this->lienPortrait($row->portrait, substr($row->prenom,0,1).". ".substr($row->nom,0,1).".", $row->prenom." ".$row->nom);
}
$this->db->sql_close_cur($res);

$entete[] = "Contrat";
//$entete[] = "Commentaires";

//echo $this->openListe(array("Joueurs", "Contrat", "Commentaires"), true);
echo $this->openListe($entete, true);
$req =	"select * ".
		"from	parties ".
		"where	id_tournoi=".$id_tournoi." ".
		"and	id_session=".$id_session." ".
		"order by id asc";
$this->db->sql_open_cur($res, $req);
$nbParties = $this->db->sql_count_cur($res);
$cumul=array();
while	($row=$this->db->sql_fetch_cur($res))
{
	$petitaubout=($row->petitaubout==1)?"oui":"non";
	$contratreussi=($row->annonce_reussie==1)?$this->makeImg("reussie.gif"):$this->makeImg("ratee.gif");
	$lContrat="<table cellpadding=0 cellspacing=0 border=0><tr valign='top'><td>".$row->annonce."&nbsp;</td><td>".$contratreussi."</td></tr></table>";
//	$htmlContrat=	"<table class='resume-partie'>".
	$htmlContrat="<table>".
				"	<tr valign=\"top\">".
				"		<td class='resume-partie'>Contrat</td>".
				"		<td class='resume-partie'>".$lContrat."</td>".
				"	</tr>".
				"	<tr>".
				"		<td class='resume-partie'>Bouts</td>".
				"		<td class='resume-partie'>".$row->nombre_bouts."</td>".
				"	</tr>".
				"	<tr>".
				"		<td class='resume-partie'>Petit au bout</td>".
				"		<td class='resume-partie'>".$petitaubout."</td>".
				"	</tr>";
	if	($row->poignee!="aucune")
		$htmlContrat.=	"	<tr>".
					"		<td class='resume-partie'>Poignée</td>".
					"		<td class='resume-partie'>".$row->poignee."</td>".
					"	</tr>";
	$htmlContrat.=	"	<tr>".
				"		<td class='resume-partie'>Points</td>".
				"		<td class='resume-partie'>".$row->points."</td>".
				"	</tr>".
				"	<tr>".
				"		<td class='resume-partie'>Commentaires</td>".
				"		<td class='resume-partie'>".nl2br($row->commentaires)."&nbsp;</td>".
				"	</tr>".
				"</table>";
	$htmlContrat=$this->openCadre().$htmlContrat.$this->closeCadre();

	//	Récupérer la liste des joueurs de la partie
	$req2 = "select id_joueur, type, points ".
			"from	r_parties_joueurs ".
			"where	id_tournoi=".$id_tournoi." ".
			"and	id_session=".$id_session." ".
			"and	id_partie=".$row->id." ". 
			"order by id_joueur asc";
	$this->db->sql_open_cur($res2, $req2);
	$nbJPar = $this->db->sql_count_cur($res2);
	$aJPar=array();
	while	($row2=$this->db->sql_fetch_cur($res2))
	{
		$aJPar[$row2->id_joueur]=$row2;
		if	(array_key_exists($row2->id_joueur, $cumul))
			$cumul[$row2->id_joueur] = $cumul[$row2->id_joueur] + $row2->points; 
		else
			$cumul[$row2->id_joueur] = $row2->points; 
		
	}
	$this->db->sql_close_cur($res2);

/*
	$this->db->sql_open_cur($res2, $req2);
	$nb2 = $this->db->sql_count_cur($res2);
*/
	$data=array();
	foreach	($aJSess as $idJ => $detJ)
	{
		$infos="";
		switch ($aJPar[$idJ]->type)
		{
			case "preneur":
				$class="liste-joueur-preneur";
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case "appelé":
				$class="liste-joueur-appele";
				$pts = sprintf("%+d", $aJPar[$idJ]->points);
				break;
			case "défense":
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

	$data[]=$this->lienPopup($lContrat,$htmlContrat);
//	$data[]=$row->commentaires;

	echo $this->ligneListe(
							$data,
							array(	
//									$this->makeLinkBouton(12, "id_partie=".$row->id."&id_session=".$id_session."&id_tournoi=".$id_tournoi),	// modifier 
									$this->makeLinkBouton(13, "id_partie=".$row->id."&id_session=".$id_session."&id_tournoi=".$id_tournoi)	// supprimer
									)
							);
}

$this->db->sql_close_cur($res);

if ($nbParties>0)
{
	$data=array();
	foreach	($aJSess as $idJ => $detJ)
	{
		$data[]="<table width='100%'><tr><td class='resume-partie' align='right' width='60%'>".sprintf("%+d", $cumul[$idJ])."</td></tr></table>";
	}
	$data[]="";
	echo $this->ligneListe($data,array());
}
echo $this->closeListe();
?>