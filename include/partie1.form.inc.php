<?php
$form->setValeurs($_POST);
/*
$err="";

if	(strlen($form->getValeur("id_preneur"))==0)
    $err .= "Sélectionner le preneur<br>";
if	(strlen($form->getValeur("annonce"))==0)
    $err .= "Sélectionner l'annonce<br>";
if	(strlen($form->getValeur("points"))==0)
    $err .= "Renseigner les points<br>";

if	($err!="")
    return;
*/

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
    $err .= 'Le preneur et l\'appelé ne peuvent pas être le même joueur !<br/>';
}


if	(strlen($form->getValeur("id_second"))==0)
{
    $nbAttaquant = 1;
    $nbDef=0;
}
else
{
    $nbAttaquant = 2;
    $nbDef=0;
}

$defense = $form->getValeur("defense");
foreach($defense as $k => $v)
{
    if	(strlen($v)>0)
        $nbDef++;
}
if	($nbAttaquant==1)
    $nbDef++;

$form->setValeur("annonce_reussie", ($form->getValeur("total")>0) ? 1 : 0);