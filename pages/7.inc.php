<?php
$form = new Formulaire();

if (count($_POST)>0)
{
	$id_tournoi = $_POST["id_tournoi"];
	$form->setValeurs($_POST);
}
else
{
	$id_tournoi = $_GET["id_tournoi"];
    $tournoi = new Tournoi($this->db);
    $row = $tournoi->getTournoiById($_GET["id_tournoi"]);
    $form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$id_tournoi));

echo $form->openForm("Commentaire sur la partie", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $id_tournoi);
echo $form->makeHidden("date", "date", time());
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeTexteRiche("commentaires", "commentaires", "Commentaires", $form->getValeur("commentaires"));
echo $form->makeButton("Enregistrer");
echo $form->closeForm();