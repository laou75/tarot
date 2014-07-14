<?php
class Session
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getSessionByTournoi($id_tournoi)
    {
        $res=null;
        $aTab = array();
        $req =	"select id, datedeb ".
                "from	sessions ".
                "where	id_tournoi=" . intval($id_tournoi) . " " .
                "order by id asc";
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTab[$row->id] = $row;
        }
        $this->db->sqlFreeResult($res);
        return $aTab;
    }

    function getStatsSessionByJoueur($id_tournoi, $id_session, $id_joueur)
    {
        $row = null;
        $req = "select sum(points) as points, id_tournoi, id_session ".
                "from	r_parties_joueurs ".
                "where	id_tournoi=" . intval($id_tournoi) . " ".
                "and	id_session=" . intval($id_session) . " ".
                "and	id_joueur =" . intval($id_joueur) . " ".
                "group by id_tournoi, id_session";
        $this->db->sqlSelect($row, $req);
        return $row;
    }
}