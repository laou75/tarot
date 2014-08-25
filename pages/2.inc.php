<?php
$tournoi = new Tournoi($db);
echo $this->drawBarreBouton(array(  $this->makeLinkBouton(3)),
                                    $this->makeLinkBoutonRetour(1)
                                    );
echo $this->openListe(array("Commencé le", "Fini le", "Commentaires"), true);
$liste = $tournoi->getTournois();
foreach($liste as $k => $row)
{
	echo $this->ligneListe(
		array(
			strftime("%d", $row->datedeb)."/".strftime("%m", $row->datedeb)."/".strftime("%Y", $row->datedeb), 
            (!empty($row->datefin))  ? strftime("%d", $row->datefin)."/".strftime("%m", $row->datefin)."/".strftime("%Y", $row->datefin) : "en cours",
            '<div id="comment_'.$row->id.'">'.nl2br($row->commentaires).'</div>'
			),
		array(	
			$this->makeLinkBouton(30, "id_tournoi=".$row->id),	// sessions
			$this->makeLinkBouton(4, "id_tournoi=".$row->id),	// modifier 
			$this->makeLinkBouton(5, "id_tournoi=".$row->id),	// supprimer
            '<a class="btn btn-default btn-sm" data-toggle="modal"  data-target="#myModal" href="ajax/ajaxGetComment.php?id_tournoi='.$row->id.'"><span class="glyphicon glyphicon-comment"></span> Commentaire</a>',
            $this->makeLinkBouton(6, "id_tournoi=".$row->id)	// Stats
			)
		);
}
echo $this->closeListe();
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
