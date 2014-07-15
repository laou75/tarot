<?php
class Joueur
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getJoueursByTournoi($id_tournoi)
    {
        $res = null;
        $aTableau = array();
        $req =	"select distinct A.id_joueur, B.nom, B.nickname, B.prenom, B.portrait ".
                "from	r_sessions_joueurs A, joueurs B ".
                "where	A.id_tournoi=" . intval($id_tournoi) . " " .
                "and	B.id=A.id_joueur ".
                "order by A.id_joueur asc";
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTableau[$row->id_joueur] = $row;
        }
        $this->db->sqlCloseCur($res);
        return $aTableau;
    }

    function getIdJoueursBySession($id_tournoi, $id_session)
    {
        $res = null;
        $aTableau = array();
        $req =	"SELECT id_joueur ".
                "from	r_sessions_joueurs ".
                "where	id_tournoi = " . intval($id_tournoi)." ".
                "and	id_session = " . intval($id_session);
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTableau[$row->id_joueur] = $row;
        }
        $this->db->sqlCloseCur($res);
        return $aTableau;
    }

    function getJoueursBySession($id_tournoi, $id_session)
    {
        $resJ = null;
        $aTableau = array();
        $reqJ = "SELECT A.id_joueur, B.id as ID, concat(B.prenom, ' ', B.nom) as LIBELLE, B.id, B.nom, B.prenom, B.nickname, B.portrait, A.position ".
                "from	r_sessions_joueurs A, joueurs B ".
                "where	B.id = A.id_joueur ".
                "and	A.id_tournoi = " . intval($id_tournoi) . " ".
                "and	A.id_session = " . intval($id_session) . " ".
                "order by A.position asc";
        $this->db->sqlOpenCur($resJ, $reqJ);
        while	($rowJ=$this->db->sqlFetchCur($resJ))
        {
            $aTableau[$rowJ->ID] = $rowJ;
        }
        $this->db->sqlCloseCur($resJ);
        return $aTableau;
    }

    function getArrayJoueursBySession($id_tournoi, $id_session)
    {
        $resJ = null;
        $aTableau = array();
        $reqJ = "SELECT B.id as ID, concat(B.prenom, ' ', B.nom) as LIBELLE, B.id, B.nom, B.prenom, B.nickname, B.portrait, A.position ".
                "from	r_sessions_joueurs A, joueurs B ".
                "where	B.id = A.id_joueur ".
                "and	A.id_tournoi = " . intval($id_tournoi) . " ".
                "and	A.id_session = " . intval($id_session) . " ".
                "order by A.position asc";
        $this->db->sqlOpenCur($resJ, $reqJ);
        while	($rowJ=$this->db->sqlFetchCur($resJ))
        {
            $aTableau[$rowJ->ID] = $rowJ->LIBELLE;
        }
        $this->db->sqlCloseCur($resJ);
        return $aTableau;
    }

    function getJoueursByPartie($id_tournoi, $id_session, $id_partie)
    {
        $res=null;
        $aTab = array();
        $req =  "select id_joueur, type, points ".
                "from	r_parties_joueurs ".
                "where	id_tournoi=" . intval($id_tournoi) . " ".
                "and	id_session=" . intval($id_session) . " ".
                "and	id_partie=" . intval($id_partie) . " ".
                "order by id_joueur asc";
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTab[$row->id_joueur] = $row;
        }
        $this->db->sqlCloseCur($res);
        return $aTab;
    }

    function getMortsPartie($id_tournoi, $id_session, $id_partie)
    {
        $resMorts=null;
        $aTabMorts= array();
        $reqMorts = "SELECT id_joueur, type ".
                    "from	r_parties_joueurs ".
                    "where	id_tournoi = " . intval($id_tournoi) . " ".
                    "and	id_session = " . intval($id_session) . " ".
                    "and	id_partie = " . intval($id_partie) . " ".
                    "and	type = 'mort' ";
        $this->db->sqlOpenCur($resMorts, $reqMorts);
        while	($rowMorts=$this->db->sqlFetchCur($resMorts))
        {
            $aTabMorts[$rowMorts->id_joueur] = $rowMorts->id_joueur;
        }
        $this->db->sqlCloseCur($resMorts);
        return $aTabMorts;
    }

    function getJoueurById($id)
    {
        $row = null;
        $req =	"select * ".
                "from joueurs ".
                "where id = " . intval($id);
        $this->db->sqlSelect($row, $req);
        return $row;
    }

    function getArrayJoueurById($id)
    {
        $row = null;
        $req =	"select * ".
                "from joueurs ".
                "where id = " . intval($id);
        $this->db->sqlSelectArray($row, $req);
        return $row;
    }

    function deleteJoueur($id)
    {
        return $this->db->sqlExecute("delete from joueurs where id = " . intval($id));

    }
}