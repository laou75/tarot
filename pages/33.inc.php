<?php
$form = new Formulaire();
if	(count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
	$form->setValeur("id", $_GET["id_session"]);
    $session = new Session($this->db);
    $row = $session->getArraySessionById($_GET["id_tournoi"], $_GET["id_session"]);
	$form->setValeurs($row);
}
echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(30, "id_tournoi=".$form->getValeur("id_tournoi")));
echo $form->openForm("Supprimer une session", "", "multipart/form-data");
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
if	(isset($warn) && strlen($warn)>0)
	echo $form->makeMsgWarning($warn);
echo $form->makeTexte("Commencée le", strftime ("%x", $form->getValeur("datedeb")));
echo $form->makeTexte("Commentaire", $form->getValeur("commentaires"));
echo $form->makeButton("Supprimer");
echo $form->closeForm();