<?php
$form = new Formulaire();
$joueurs = new Joueur($db);

$champs = array();

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
	$this->db->sqlSelectArray($row, "select *" .
                                    " from parties" .
                                    " where id=" . intval($id) .
                                    " and id_tournoi=" . intval($id_tournoi) .
                                    " and id_session=" . intval($id_session) );
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
$aTabMorts = $joueurs->getMortsPartie($form->getValeur("id_tournoi"), $form->getValeur("id_session"), $id);

echo $form->openFieldset("Joueurs");
$aTableau = $joueurs->getArrayJoueursBySession($form->getValeur("id_tournoi"), $form->getValeur("id_session"));
echo $form->makeCombo("id_preneur", "id_preneur", "Preneur (*)", $form->getValeur("id_preneur"), $aTableau);
if (count($aTableau)>=5)
	echo $form->makeCombo("id_second", "id_second", "Appelé", $form->getValeur("id_second"), $aTableau, " onChange=\"change_appele()\"");
$i=1;
foreach($aTableau as $clef => $valeur)
{
	if ($clef!=$form->getValeur("id_preneur") && $clef!=$form->getValeur("id_second") && (!array_key_exists($clef, $aTabMorts)))
	{
		$champs[] = array("name"=>"def".$i, "type"=>"combo", "values"=>$aTableau, "value"=>$clef);
		$i++;
	}
    $der=$i;
}

echo $form->makeMulti("defense[]", "defense", "Défense", $champs);
?>
<script type="text/javascript">
function change_appele()
{
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
echo $form->makeButton("Enregistrer");
echo $form->closeForm();
?>
<script type="text/javascript">
    /*
    $(function () {
        calculePoints();
    });
    */
</script>