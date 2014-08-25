<?php
$form = new Formulaire();

if (count($_POST)>0)
{
	$form->setValeurs($_POST);
}
else
{
	$form->setValeur("id", $_GET["id_joueur"]);
    $joueurs = new Joueur($this->db);
    $row = $joueurs->getArrayJoueurById($_GET["id_joueur"]);
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
echo $form->makeInput("nickname", "nickname", "Surnom ", $form->getValeur("nickname"));
$image=(strlen($form->getValeur("portrait"))>0) ? $this->makePortrait("mini/".$form->getValeur("portrait")) : "";
echo $form->makeFileInput(	"portrait", 
							"portrait", 
							"Portrait", 
							$form->getValeur("portrait"), 
							$image);
echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
