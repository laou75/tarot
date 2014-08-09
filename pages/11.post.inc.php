<?php
$form = new Formulaire();

include_once(PATH_ROOT . '/include/partie1.form.inc.php');
if	($err=='')
{
    $this->db->sqlInsert("parties", $form->getValeurs());
    $idPar = $this->db->sqlLastInsert("parties", "id");

    $valJPar["id_tournoi"] = $form->getValeur("id_tournoi");
    $valJPar["id_session"] = $form->getValeur("id_session");
    $valJPar["id_partie"] = $idPar;
}
include_once(PATH_ROOT . '/include/partie2.form.inc.php');