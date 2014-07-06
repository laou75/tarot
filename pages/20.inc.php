<?php
echo $this->drawBarreBouton(	
	array($this->makeLinkBouton(21)),
	$this->makeLinkBoutonRetour(1)
	);
echo $this->openListe(array("Portrait", "Prénom", "Nom"), true);
$res=null;
$req = "select * from joueurs order by nom asc, prenom asc";
$this->db->sqlOpenCur($res, $req);
$nb = $this->db->sqlCountCur($res);
$i=0;
while	($row=$this->db->sqlFetchCur($res))
{
	$portrait=strlen($row->portrait)>0 ? $row->portrait : "inconnu.gif";
	$image=$this->makePortrait("mini/".$portrait);
	echo $this->ligneListe(
							array(	"<center>".$image."</center>", 
									$row->prenom,
									$row->nom
									),
							array(	$this->makeLinkBouton(22, "id_joueur=".$row->id),	// modifier 
									$this->makeLinkBouton(23, "id_joueur=".$row->id)	// supprimer
									)
							);
}
echo $this->closeListe();