<?php
$form = new Formulaire();

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
	$form->setValeur("id", $_GET["id_session"]);

    $sessions = new Session($this->db);
    $row = $sessions->getArraySessionById($_GET["id_tournoi"], $_GET["id_session"]);
	$form->setValeurs($row);

    $joueurs = new Joueur($this->db);
    $aTmp = $joueurs->getJoueursBySession($_GET["id_tournoi"], $_GET["id_session"]);
	$form->setValeur("liste_joueurs", $aTmp);
}

$alisteJoueurs = array();
foreach($aTmp as $k => $row2)
{
    $alisteJoueurs[$row2->id] = $row2->prenom." ".$row2->nom;
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(30, "id_tournoi=".$form->getValeur("id_tournoi")));

echo $form->openForm("Modifier une session", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));

echo $form->makeInputDate("datedeb", "datedeb", "Date de début (*)", strftime ("%d/%m/%Y", $form->getValeur("datedeb")), '', 'date début');
$datefin = !empty($form->getValeur("datefin")) ? strftime ("%d/%m/%Y", $form->getValeur("datefin")) : '';
echo $form->makeInputDate("datefin", "datefin", "Date de fin", $datefin, '', 'date fin');

echo $form->makeComboMultiple("liste_joueurs[]", "liste_joueurs", "Joueurs (*)", $form->getValeur("liste_joueurs"), $alisteJoueurs);
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
