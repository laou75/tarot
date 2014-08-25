<?php
class Tournoi
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getTournois($limit=false)
    {
        $liste = array();
        $res=null;
        $req =  "select * ".
                "from tournois ".
                "order by datedeb desc ".
                ( (false!==$limit) ? ' limit 10 ' : ' ');
        $this->db->sqlOpenCur($res, $req);
        while ($row=$this->db->sqlFetchCur($res)) {
            $liste[] = $row;
        }
        return $liste;
    }

    function getArrayTournoiById($id_tournoi)
    {
        $row = null;
        $req =	"select * ".
                "from tournois ".
                "where id = " . intval($id_tournoi);
        $this->db->sqlSelectArray($row, $req);
        return $row;
    }

    function getTournoiById($id_tournoi)
    {
        $row = null;
        $req =	"select * ".
                "from tournois ".
                "where id = " . intval($id_tournoi);
        $this->db->sqlSelectArray($row, $req);
        return $row;
    }

    function getLast()
    {
        return $this->getTournois(true);
    }

    function getPodium($id_tournoi)
    {
        $liste = array();
        $res=null;
        $req =  "SELECT sum(points) as cumul, id_joueur, nom, prenom, portrait, 0 as classement " .
                "FROM   r_parties_joueurs " .
                "join   joueurs on (id = id_joueur) " .
                "WHERE  id_tournoi = " . intval($id_tournoi) . " " .
                "group by id_joueur, nom, prenom, portrait ".
                "order by sum(points) desc";

        $this->db->sqlOpenCur($res, $req);
        $classement=0;
        $cumulPrec=-10000;
        $pas=1;
        while ($row=$this->db->sqlFetchCur($res))
        {
            if ($classement == 0 || $cumulPrec > intval($row->cumul))
            {
                $classement = $classement + $pas;
                $pas=1;
            }
            elseif($cumulPrec == intval($row->cumul))
            {
                $pas++;
            }
            $row->classement = $classement;
            $liste[] = $row;
            $cumulPrec = intval($row->cumul);
        }
        return $liste;
    }
}
