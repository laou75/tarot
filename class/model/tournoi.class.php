<?php
class Tournoi
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getTournois()
    {
        $liste = array();
        $res=null;
        $req =  "select * ".
                "from tournois ".
                "order by datedeb desc";
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
}