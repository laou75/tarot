<?php
echo $this->drawBarreBouton(	
	array($this->makeLinkBouton(21)),
	$this->makeLinkBoutonRetour(1)
	);
echo $this->openListe(array("Joueur"), true);
$res=null;
$req = "select * from joueurs order by nom asc, prenom asc";
$this->db->sqlOpenCur($res, $req);
$nb = $this->db->sqlCountCur($res);
while	($row=$this->db->sqlFetchCur($res))
{
    //$image = strlen($row->portrait)>0 ? $this->makePortrait("mini/".$row->portrait) : '<span class="glyphicon glyphicon-user" style="font-size: 600%;"></span>';
    $image = $this->getPortrait($row->portrait);
	echo $this->ligneListe(
        array(	'<div class="row"><div class="col-md-2 text-center">'.$image.'</div><div class="col-md-10">'.$row->prenom.' '.$row->nom.'</div></div>'),
        array(	$this->makeLinkBouton(22, "id_joueur=".$row->id),	// modifier
									$this->makeLinkBouton(23, "id_joueur=".$row->id)	// supprimer
									)
							);
}
echo $this->closeListe();