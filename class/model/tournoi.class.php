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
}