<div class="row">
    <div class="col-sm-4 text-center">
<?php
echo $this->makeIllustration("tarot.png", '', 'class="img-responsive"');
?>
    </div>
    <div class="col-sm-8">
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

echo $form->makeInputLogin("identifiant", "identifiant", "Identifiant (*)", 'login', '');
echo $form->makeInputMDP("password", "password", 'Mot de passe (*)', 'password', '');
echo $form->makeNoteObligatoire();
echo $form->makeButton("Se connecter");
echo $form->closeForm();
?>
    </div>
</div>
