<?php
$joueurs = new Joueur($db);
$tabJoueurs = $joueurs->getIdJoueursBySession($form->getValeur("id_tournoi"), $form->getValeur("id_session"));
/*
echo '<pre>'.print_r($_POST, true).'</pre>';
echo '<pre>'.print_r($defense, true).'</pre>';
*/
/*
$err='';
if (empty($form->getValeur("id_preneur")))
    $err .= 'Sélectionner le preneur !<br/>';
if (empty($form->getValeur("defense")))
    $err .= 'Sélectionner la défense !<br/>';
if (!empty($form->getValeur("defense")) && count($form->getValeur("defense"))<2)
    $err .= 'Sélectionner au moins 2 joueurs en défense !<br/>';
if (empty($form->getValeur("annonce")))
    $err .= 'Sélectionner l\'annonce !<br/>';
if (empty($form->getValeur("points")))
    $err .= 'Saisir les points réalisés !<br/>';
if (empty($form->getValeur("poignee")))
    $err .= 'Sélectionner le type de poignee !<br/>';
if (!empty($form->getValeur("id_preneur")) && !empty($form->getValeur("defense")))
{
    if (in_array($form->getValeur("id_preneur"), $form->getValeur("defense")))
    {
        $err .= 'Le preneur est aussi sélectionné en défense !<br/>';
    }
}
if (!empty($form->getValeur("id_second")) && !empty($form->getValeur("defense")))
{
    if (in_array($form->getValeur("id_second"), $form->getValeur("defense")))
    {
        $err .= 'L\'appelé est aussi sélectionné en défense !<br/>';
    }
}
if (!empty($form->getValeur("id_second")) && !empty($form->getValeur("id_preneur")) && $form->getValeur("id_second")==$form->getValeur("id_preneur"))
{
    $err .= 'Le preneur et l\'appelé ne doivent pas être le même joueur !<br/>';
}
*/
if ($err=='')
{
    foreach($tabJoueurs as $kJ => $rowJ)
    {
        $valJPar["id_joueur"]= $rowJ->id_joueur;
        if	($form->getValeur("id_preneur")==$rowJ->id_joueur)
        {
            $valJPar["type"]= "preneur";
            $valJPar["points"]= $form->getValeur("total")*($nbDef-1);
        }
        elseif	($form->getValeur("id_second")==$rowJ->id_joueur)
        {
            $valJPar["type"]= "called";
            $valJPar["points"]= $form->getValeur("total");
        }
        elseif	(in_array($rowJ->id_joueur, $defense))
        {
            $valJPar["type"]= "defense";
            $valJPar["points"]= $form->getValeur("total")*(-1);
        }
        else
        {
            $valJPar["type"]= "mort";
            $valJPar["points"]= 0;
        }
        $this->db->sqlInsert("r_parties_joueurs", $valJPar);
        header("Location: ".$form->getValeur("from"));
    }
}