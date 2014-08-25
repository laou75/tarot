<?php
$form = new Formulaire();

if (count($_POST)>0)
{
    $id_tournoi = $_POST["id_tournoi"];
    $id_session = $_POST["id_session"];
	$form->setValeurs($_POST);
}
else
{
    $id_tournoi = $_GET["id_tournoi"];
    $id_session = $_GET["id_session"];
    $session = new Session($this->db);
    $row = $session->getSessionById($id_tournoi, $id_session);
    $form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$id_tournoi."&amp;id_session=".$id_session));

echo $form->openForm("Commentaire sur la session", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $id_tournoi);
echo $form->makeHidden("id_session", "id_session", $id_session);
echo $form->makeHidden("date", "date", time());
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
