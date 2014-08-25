<?php
$form = new Formulaire();

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(2));

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeur("id", $_GET["id_tournoi"]);
    $tournois = new Tournoi($this->db);
    $row = $tournois->getArrayTournoiById($form->getValeur('id'));
	$form->setValeurs($row);
}

echo $form->openForm("Modifier un tournoi", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeInputDate("datedeb", "datedeb", "Date de début (*)", strftime ("%d/%m/%Y", $form->getValeur("datedeb")), '', 'date début');
echo $form->makeInputDate("datefin", "datefin", "Date de fin", (!empty($form->getValeur("datefin")) ? strftime ("%d/%m/%Y", $form->getValeur("datefin")) : ''), '', 'date fin');
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
