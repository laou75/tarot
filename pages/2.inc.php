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
			nl2br($row->commentaires)
			),
		array(	
			$this->makeLinkBouton(30, "id_tournoi=".$row->id),	// sessions
			$this->makeLinkBouton(4, "id_tournoi=".$row->id),	// modifier 
			$this->makeLinkBouton(5, "id_tournoi=".$row->id),	// supprimer
            $this->makeLinkBouton(7, "id_tournoi=".$row->id),	// commentaires
			$this->makeLinkBouton(6, "id_tournoi=".$row->id)	// Stats
			)
		);
}
echo $this->closeListe();