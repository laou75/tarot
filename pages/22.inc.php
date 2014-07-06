<?php
$form = new Formulaire();

if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeur("id", $_GET["id_joueur"]);
	$this->db->sqlSelectArray($row, "select * from joueurs where id=" . intval($form->getValeur("id")));
	$form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(20));

echo $form->openForm("Modifier un joueur", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeHidden("id", "id", $form->getValeur("id"));
echo $form->makeInput("nom", "nom", "Nom (*)", $form->getValeur("nom"));
echo $form->makeInput("prenom", "prenom", "Prénom (*)", $form->getValeur("prenom"));
$image=(strlen($form->getValeur("portrait"))>0) ? $this->makePortrait("mini/".$form->getValeur("portrait")) : "";
echo $form->makeFileInput(	"portrait", 
							"portrait", 
							"Portrait", 
							$form->getValeur("portrait"), 
							$image);
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();