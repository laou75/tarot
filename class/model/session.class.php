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
        $req =	"select * ".
                "from	sessions ".
                "where	id_tournoi=" . intval($id_tournoi) . " " .
                "order by id desc";
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
        $req =  "select sum(points) as points, id_tournoi, id_session ".
                "from	r_parties_joueurs ".
                "where	id_tournoi=" . intval($id_tournoi) . " ".
                "and	id_session=" . intval($id_session) . " ".
                "and	id_joueur =" . intval($id_joueur) . " ".
                "group by id_tournoi, id_session ";
        $this->db->sqlSelect($row, $req);
        return $row;
    }


    function getArraySessionById($id_tournoi, $id_session)
    {
        $row = null;
        $req =	"select * ".
                "from sessions ".
                "where id = " . intval($id_session) . " ".
                "and id_tournoi = " . intval($id_tournoi);
        $this->db->sqlSelectArray($row, $req);
        return $row;
    }

    function getAll($limit=false)
    {
        $liste = array();
        $res=null;
        $req =  "select * ".
                "from sessions ".
                "order by datedeb desc ".
                ( (false!==$limit) ? ' limit '.$GLOBALS["Config"]["SITE"]["MAXBYLIST"].' ' : ' ');
        $this->db->sqlOpenCur($res, $req);
        while ($row=$this->db->sqlFetchCur($res)) {
            $liste[] = $row;
        }
        return $liste;
    }

    function getLast()
    {
        return $this->getAll(true);
    }
}