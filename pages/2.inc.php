<?php
echo $this->drawBarreBouton(	
	array($this->makeLinkBouton(3)),
	$this->makeLinkBoutonRetour(1)
	);

echo $this->openListe(array("Commenc� le", "Fini le", "Commentaires"), true);
$req = "select * from tournois order by datedeb desc";
$this->db->sql_open_cur($res, $req);
$nb = $this->db->sql_count_cur($res);
$i=0;
while	($row=$this->db->sql_fetch_cur($res)) {
	echo $this->ligneListe(
		array(	
			strftime("%d", $row->datedeb)."/".strftime("%m", $row->datedeb)."/".strftime("%Y", $row->datedeb), 
			(isset($row->datefin))?strftime("%d", $row->datefin)."/".strftime("%m", $row->datefin)."/".strftime("%Y", $row->datefin):"en cours", 
			nl2br($row->commentaires)
			),
		array(	
			$this->makeLinkBouton(30, "id_tournoi=".$row->id),	// modifier 
			$this->makeLinkBouton(4, "id_tournoi=".$row->id),	// modifier 
			$this->makeLinkBouton(5, "id_tournoi=".$row->id),	// supprimer
			$this->makeLinkBouton(6, "id_tournoi=".$row->id)	// Stats
			)
		);
}
echo $this->closeListe();