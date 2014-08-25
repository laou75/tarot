<?php
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
