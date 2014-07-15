<?php
$form = new Formulaire();
$form->setValeurs($_POST);

$err="";
if (strlen($form->getValeur("datedeb"))==0)
    $err .= "Le champ 'Date de début' est obligatoire !<br>";
if ($err!="")
    return;

$d = substr($form->getValeur("datedeb"), 0, 2);
$m = substr($form->getValeur("datedeb"), 3, 2);
$y = substr($form->getValeur("datedeb"), 6, 4);
$form->setValeur("datedeb", mktime ( 0, 0, 0, $m, $d, $y));
if	(strlen($form->getValeur("datefin"))>0)
{
    $d = substr($form->getValeur("datefin"), 0, 2);
    $m = substr($form->getValeur("datefin"), 3, 2);
    $y = substr($form->getValeur("datefin"), 6, 4);
    $form->setValeur("datefin", mktime ( 0, 0, 0, $m, $d, $y));
}