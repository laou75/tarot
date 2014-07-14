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

echo $form->openForm("Ajouter une partie", "", "multipart/form-data");
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
while($i<count($aTableau)) {
	if	(count($_POST)>0)
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "value"=>$form->getValeur("def".$i), "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
	else
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
	$der=$i;
	$i++;
}
echo $form->makeMulti("defense[]", "defense", "Défense", $champs);
?>
<script type="text/javascript">
function change_appele() {
    if (document.getElementById("id_second") && document.getElementById("id_second").value!="")
    {
        document.getElementById("def<?php echo $der;?>").value=0;
        document.getElementById("def<?php echo $der;?>").style.display="none";
    }
<?php if ($der>3) {?>
    else
    {
        document.getElementById("defd.def<?php echo $der;?>").style.display="block";
    }
<?php }?>
}
</script>
<?php
echo $form->closeFieldset();

echo $form->openFieldset("Contrat");
echo $form->makeRadioEnum("annonce", "annonce", "Annonce (*)", $form->getValeur("annonce"), "parties", "annonce", false, $this->db, "onclick=\"calculePoints()\"");
echo $form->makeInput("points", "points", "Points réalisés (*)", $form->getValeur("points"), "onchange=\"calculePoints()\"");
echo $form->makeRadio("nombre_bouts", "nombre_bouts", "Nombre de bouts", $form->getValeur("nombre_bouts"), array(0=>"0", 1=>"1", 2=>"2", 3=>"3"), "onclick=\"calculePoints()\"");
echo $form->makeRadio("petitaubout", "petitaubout", "Petit au bout ?", $form->getValeur("petitaubout"), array(0=>"non", 1=>"oui"), "onclick=\"calculePoints()\"");
echo $form->makeRadioEnum("poignee", "poignee", "Poignée ?", $form->getValeur("poignee"), "parties", "poignee", false, $this->db, "onclick=\"calculePoints()\"");
echo $form->makeInput("total", "total", "Total", $form->getValeur("total"), " READONLY");
echo $form->closeFieldset();

echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer", " onclick=\"return controleFormulaire();\"");
echo $form->closeForm();
?>
<script type="text/javascript">
change_appele();
</script>