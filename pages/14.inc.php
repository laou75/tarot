<?php
include_once ("class/formulaire.class.php");
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
	$this->db->sql_select_array($row, "select * from parties where id=".$id." and id_tournoi=".$id_tournoi." and id_session=".$id_session." ");
	$form->setValeur("id_partie", $id);
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

echo $form->openFieldset("Commentaires");
echo $form->makeTexteRiche("commentaires", "commentaires", $form->getValeur("commentaires"));
echo $form->closeFieldset();

echo $form->makeButton("Enregistrer");
echo $form->closeForm();
?>