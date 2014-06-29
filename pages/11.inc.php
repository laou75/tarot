<?php
include_once ("class/formulaire.class.php");
$form = new Formulaire();

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
$reqJ = "SELECT B.id as ID, concat(B.prenom, ' ', B.nom) as LIBELLE ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	B.id = A.id_joueur ".
		"and	A.id_tournoi = ".$form->getValeur("id_tournoi")." ".
		"and	A.id_session = ".$form->getValeur("id_session")." ".
		"order by A.position asc";
		
$this->db->sql_open_cur($resJ, $reqJ);
$nbJ=$this->db->sql_count_cur($resJ);
while	($rowJ=$this->db->sql_fetch_cur($resJ))
{
	$aTableau[$rowJ->ID] = $rowJ->LIBELLE;
}
$this->db->sql_close_cur($resJ);

echo $form->makeCombo("id_preneur", "id_preneur", "Preneur (*)", $form->getValeur("id_preneur"), $aTableau, " onChange=\"checkSelectJoueur('id_preneur')\"");
if ($nbJ>=5)
	echo $form->makeCombo("id_second", "id_second", "Appel�", $form->getValeur("id_second"), $aTableau, " onChange=\"checkSelectJoueur('id_second'); change_appele();\"");

$i=1;
while($i<$nbJ) {
	if	(count($_POST)>0)
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "value"=>$form->getValeur("def".$i), "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
	else
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "options"=>" onChange=\"checkSelectJoueur('def".$i."')\"");
	$der=$i;
	$i++;
}
echo $form->makeMulti("defense", "defense", "Défense", $champs);
?>
<script type="text/javascript">
function change_appele() {
	d = document.forms[0];
	if	(d.id_second.value!="") {
		d.def<?=$der;?>.value=0;
		d.def<?=$der;?>.style.display="none";
	} else {
		d.def<?=$der;?>.style.display="block";
	}
}
</script>
<?php
echo $form->closeFieldset();

echo $form->openFieldset("Contrat");
echo $form->makeRadioEnum("annonce", "annonce", "Annonce (*)", $form->getValeur("annonce"), "parties", "annonce", false, $this->db, "onclick=\"calcule_points()\"");
echo $form->makeInput("points", "points", "Points réalisés (*)", $form->getValeur("points"), "onchange=\"calcule_points()\"");
echo $form->makeRadio("nombre_bouts", "nombre_bouts", "Nombre de bouts", $form->getValeur("nombre_bouts"), array(0=>"0", 1=>"1", 2=>"2", 3=>"3"), "onclick=\"calcule_points()\"");
echo $form->makeRadio("petitaubout", "petitaubout", "Petit au bout ?", $form->getValeur("petitaubout"), array(0=>"non", 1=>"oui"), "onclick=\"calcule_points()\"");

echo $form->makeRadioEnum("poignee", "poignee", "Poignée ?", $form->getValeur("poignee"), "parties", "poignee", false, $this->db, "onclick=\"calcule_points()\"");
echo $form->makeInput("total", "total", "Total", $form->getValeur("total"), " READONLY");
echo $form->closeFieldset();

echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer", " onclick=\"return controleFormulaire();\"");
echo $form->closeForm();
?>
<script>
change_appele();
</script>