<?php
    $joueurs = new Joueur($db);

echo $this->drawBarreBouton(	
	array($this->makeLinkBouton(21)),
	$this->makeLinkBoutonRetour(1)
	);
echo $this->openListe(array("Joueur"), true);
$listeJoueurs = $joueurs->getAll();
foreach($listeJoueurs as $row)
{
    $image = $this->getPortrait($row->portrait);
    $stats='';
    $stats='<ul>'.
                '<li>Nombre de tournois joués : '.$joueurs->getNbTournoisById($row->id).'</li>'.
                '<li>Nombre de sessions jouées : '.$joueurs->getNbSessionsById($row->id).'</li>'.
                '<li>Nombre de parties jouées : '.$joueurs->getNbPartiesById($row->id).'</li>'.
            '</ul>';
	echo $this->ligneListe(
        array(	'<div class="row"><div class="col-sm-4">'.$image.'<br/>'.$row->prenom.' '.$row->nom.'</div><div class="col-sm-8">'.$stats.'</div></div>'),
        array(	$this->makeLinkBouton(22, "id_joueur=".$row->id),	// modifier
									$this->makeLinkBouton(23, "id_joueur=".$row->id)	// supprimer
									)
							);
}
echo $this->closeListe();
