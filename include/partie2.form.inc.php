<?php
$joueurs = new Joueur($db);
$tabJoueurs = $joueurs->getIdJoueursBySession($form->getValeur("id_tournoi"), $form->getValeur("id_session"));
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