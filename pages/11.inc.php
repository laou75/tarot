<?php
$form = new Formulaire();
$joueurs = new Joueur($db);

if	(count($_POST)>0)
	$form->setValeurs($_POST);
else
{
	$form->setValeurs(array());
	$form->setValeur("id_tournoi", $_GET["id_tournoi"]);
	$form->setValeur("id_session", $_GET["id_session"]);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$form->getValeur("id_tournoi")."&amp;id_session=".$form->getValeur("id_session")));

echo $form->openForm("Ajouter une partie", '', "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
echo $form->makeHidden("id_session", "id_session", $form->getValeur("id_session"));
echo $form->makeHidden("date", "date", time());
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);

echo $form->openFieldset("Joueurs");
$aTableau = $joueurs->getArrayJoueursBySession($form->getValeur("id_tournoi"), $form->getValeur("id_session"));
echo $form->makeCombo("id_preneur", "id_preneur", "Preneur (*)", $form->getValeur("id_preneur"), $aTableau, " onChange=\"checkSelectJoueur('id_preneur')\"");
if (count($aTableau)>=5)
	echo $form->makeCombo("id_second", "id_second", "Appelé", $form->getValeur("id_second"), $aTableau, " onChange=\"checkSelectJoueur('id_second'); change_appele();\"");
$i=1;
$defense = $form->getValeur("defense");
while($i<count($aTableau)) {
	if	(count($_POST)>0)
    {
        $champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "value"=>$defense[$i-1], "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
    }
	else
    {
        $champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
    }
	$der=$i;
	$i++;
}
include(PATH_ROOT.'/include/partie.form.inc.php');
?>
<script type="text/javascript">
change_appele();
</script>
