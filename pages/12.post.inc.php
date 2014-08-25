<?php
$form = new Formulaire();

include_once(PATH_ROOT.'/include/partie1.form.inc.php');

if	($err=='')
{
    $this->db->sqlUpdate(  "parties",
            array(  "id" => intval($form->getValeur("id_partie")),
                "id_tournoi" => intval($form->getValeur("id_tournoi")),
                "id_session" => intval($form->getValeur("id_session"))),
            $form->getValeurs());

    $valJPar["id_tournoi"] = $form->getValeur("id_tournoi");
    $valJPar["id_session"] = $form->getValeur("id_session");
    $valJPar["id_partie"] = $form->getValeur("id_partie");

    $reqTMP =   "DELETE ".
                "from	r_parties_joueurs ".
                "where	id_tournoi = " . intval($form->getValeur("id_tournoi"))." ".
                "and	id_session = " . intval($form->getValeur("id_session"))." ".
                "and	id_partie = " . intval($form->getValeur("id_partie"));
    $this->db->sqlExecute($reqTMP);
}
include_once(PATH_ROOT . '/include/partie2.form.inc.php');
