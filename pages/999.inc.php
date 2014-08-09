<div class="row">
    <div class="col-md-4 text-center">
<?php
echo $this->makeIllustration("tarot.png", '', 'class="img-responsive"');
?>
    </div>
    <div class="col-md-8">
<?php
$form = new Formulaire();

if (count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeurs(array());
	$form->setValeur("datedeb", $form->timeToTextDate(time()));
}

echo $form->openForm("Se connecter", "", "multipart/form-data");
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);
echo $form->makeInput("identifiant", "identifiant", "Identifiant (*)", $form->getValeur("identifiant"), '', 'text', 'login', 'Saisir votre identifiant');
echo $form->makePassword("password", "password", "Mot de passe (*)", '', '', 'password', 'Saisir votre mot de passe');
echo $form->makeNoteObligatoire();
echo $form->makeButton("Se connecter");
echo $form->closeForm();
?>
    </div>
</div>