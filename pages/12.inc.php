<?php
//include_once ("class/formulaire.class.php");
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
	$this->db->sql_select_array($row, "select * from parties where id=" . intval($id) . " and id_tournoi=" . intval($id_tournoi) . " and id_session=" . intval($id_session) );
	$form->setValeur("id_partie", $id);
	$form->setValeurs($row);
}

echo $this->drawBarreBouton(
	null,
	$this->makeLinkBoutonRetour(10, "id_tournoi=".$form->getValeur("id_tournoi")."&id_session=".$form->getValeur("id_session")));

echo $form->openForm("Modifier une partie", "", "multipart/form-data");
echo $form->makeHidden("id_tournoi", "id_tournoi", $form->getValeur("id_tournoi"));
echo $form->makeHidden("id_session", "id_session", $form->getValeur("id_session"));
echo $form->makeHidden("id_partie", "id_partie", $id);
echo $form->makeHidden("date", "date", time());
if	(isset($err) && strlen($err)>0)
	echo $form->makeMsgError($err);

// liste des morts
$reqMorts = "SELECT id_joueur, type ".
			"from	r_parties_joueurs ".
			"where	id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
			"and	id_session = " . intval($form->getValeur("id_session")) . " ".
			"and	id_partie = " . intval($id) . " ".
			"and	type = 'mort' ";
$this->db->sql_open_cur($resMorts, $reqMorts);
$aTabMorts= array();
while	($rowMorts=$this->db->sql_fetch_cur($resMorts))
{
	$aTabMorts[$rowMorts->id_joueur] = $rowMorts->id_joueur;
}
$this->db->sql_close_cur($resMorts);

echo $form->openFieldset("Joueurs");
$reqJ = "SELECT B.id as ID, concat(B.prenom, ' ', B.nom) as LIBELLE ".
		"from	r_sessions_joueurs A, joueurs B ".
		"where	B.id = A.id_joueur ".
		"and	A.id_tournoi = " . intval($form->getValeur("id_tournoi")) . " ".
		"and	A.id_session = " . intval($form->getValeur("id_session")) . " ".
		"order by A.position asc";

$this->db->sql_open_cur($resJ, $reqJ);
$nbJ=$this->db->sql_count_cur($resJ);
while	($rowJ=$this->db->sql_fetch_cur($resJ))
{
	$aTableau[$rowJ->ID] = $rowJ->LIBELLE;
}
$this->db->sql_close_cur($resJ);

echo $form->makeCombo("id_preneur", "id_preneur", "Preneur (*)", $form->getValeur("id_preneur"), $aTableau);
if ($nbJ>=5)
	echo $form->makeCombo("id_second", "id_second", "Appelé", $form->getValeur("id_second"), $aTableau, " onChange=\"change_appele()\"");
$i=1;
foreach($aTableau as $clef => $valeur)
{
	if ($clef!=$form->getValeur("id_preneur") && $clef!=$form->getValeur("id_second") && (!array_key_exists($clef, $aTabMorts)))
	{
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "value"=>$clef);
		$i++;
	}
}

echo $form->makeMulti("defense", "defense", "Défense", $champs);
?>
<script type="text/javascript">
function change_appele()
{
	d = document.forms[0];
	if	(d.id_second.value!="")
	{
		d.def<?=$der;?>.value=0;
		d.def<?=$der;?>.style.display="none";
	}
	else
	{
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

//echo $form->makeComboEnum("poignee", "poignee", "Poignée ", $form->getValeur("poignee"), "parties", "poignee", false, $this->db);
echo $form->makeRadioEnum("poignee", "poignee", "Poignée ?", $form->getValeur("poignee"), "parties", "poignee", false, $this->db, "onclick=\"calcule_points()\"");
echo $form->makeInput("total", "total", "Total", $form->getValeur("total"), " READONLY");
echo $form->closeFieldset();

echo $form->makeNoteObligatoire();
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
?>
<script>

calcule_points();
</script>