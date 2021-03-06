<?php
$form = new Formulaire();

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(2));

if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
    $tournois = new Tournoi($this->db);
    $row = $tournois->getArrayTournoiById($_GET["id_tournoi"]);
	$form->setValeurs($row);
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
}
echo $form->openForm("Supprimer un tournoi", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
if (isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
if (isset($warn) && strlen($warn)>0)
	echo $form->makeMsgWarning($warn);
echo $form->makeTexte("Commencé le", strftime ("%d/%m/%Y", $form->getValeur("datedeb")));
echo $form->makeTexte("Terminé le", (!empty($form->getValeur("datefin")) ? strftime ("%d/%m/%Y", $form->getValeur("datefin")) : ''));
echo $form->makeTexte("Commentaire", $form->getValeur("commentaires"));
echo $form->makeButton("Supprimer");
echo $form->closeForm();
