<?php
$form->setValeurs($_POST);

$err="";

if	(strlen($form->getValeur("id_preneur"))==0)
    $err .= "Sélectionner le preneur<br>";
if	(strlen($form->getValeur("annonce"))==0)
    $err .= "Sélectionner l'annonce<br>";
if	(strlen($form->getValeur("points"))==0)
    $err .= "Renseigner les points<br>";

if	($err!="")
    return;

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