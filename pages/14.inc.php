<?php
$form = new Formulaire();

if (count($_POST)>0)
{
	$id = $_POST["id_partie"];
	$id_tournoi = $_POST["id_tournoi"];
	$id_session=$_POST["id_session"];
	$form->setValeurs($_POST);
}
else
{
	$id = $_GET["id_partie"];
	$id_tournoi = $_GET["id_tournoi"];
	$id_session=$_GET["id_session"];
    $parties = new Partie($this->db);
    $row = $parties->getPartieById($_GET["id_tournoi"], $_GET["id_session"], $_GET["id_partie"]);
    $form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$form->getValeur("id_tournoi")."&id_session=".$form->getValeur("id_session")));

echo $form->openForm("Commentaire sur la partie", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
echo $form->makeHidden("id_session", "id_session", $form->getValeur("id_session"));
echo $form->makeHidden("id_partie", "id_partie", $id);
echo $form->makeHidden("date", "date", time());
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeButton("Enregistrer");
echo $form->closeForm();