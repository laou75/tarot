<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$err="";
if (strlen($form->getValeur("datedeb"))==0)
    $err .= "Le champ 'Date de début' est obligatoire !<br>";
if ($err!="")
    return;

$form->setValeur("datedeb", $form->textToDate($form->getValeur("datedeb")));
if	(strlen($form->getValeur("datefin"))>0)
{
    $form->setValeur("datefin", $form->textToDate($form->getValeur("datefin")));
}