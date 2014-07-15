<?php
class Partie
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getPartiesBySession($id_tournoi, $id_session)
    {
        $res = null;
        $aTableau=array();
        $req =	"select * ".
                "from	parties ".
                "where	id_tournoi=" . intval($id_tournoi) . " ".
                "and	id_session=" . intval($id_session) . " ".
                "order by id asc";
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTableau[$row->id] = $row;
        }
        $this->db->sqlCloseCur($res);
        return $aTableau;
    }

    function getStatsPartie($id_tournoi, $id_session, $id_partie, $id_joueur)
    {
        $row = null;
        $req =  "select sum(points) as CUMUL, id_tournoi, id_session, id_partie ".
                "from	r_parties_joueurs ".
                "where	id_tournoi=" . intval($id_tournoi) . " ".
                "and	id_session=" . intval($id_session) . " ".
                "and	id_partie=" . intval($id_partie) . " ".
                "and	id_joueur =" . intval($id_joueur) . " ".
                "group by id_tournoi, id_session, id_partie";
        $this->db->sqlSelect($row, $req);
        return $row;
    }

    function getPartieById($id_tournoi, $id_session, $id_partie)
    {
        $row = null;
        $req =	"select * ".
                "from parties ".
                "where id_tournoi = " . intval($id_tournoi). " ".
                "and id_session = " . intval($id_session). " ".
                "and id = " . intval($id_partie);
        $this->db->sqlSelectArray($row, $req);
        return $row;
    }
}