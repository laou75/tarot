<?php
class Menu
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function getMenusByIdPere($idPere)
    {
        $res = null;
        $aTableau = array();
        $req =	"select * ".
                "from menu ".
                "where id_pere = " . intval($idPere) . " ".
                "and visible_menu = 1 ".
                "order by ordre asc";
        $this->db->sqlOpenCur($res, $req);
        while	($row=$this->db->sqlFetchCur($res))
        {
            $aTableau[$row->id] = $row;
        }
        $this->db->sqlCloseCur($res);
        return $aTableau;
    }

    function getMenuById($id)
    {
        $row = null;
        $req =	"select * ".
                "from menu ".
                "where id = " . intval($id);
        $this->db->sqlSelect($row, $req);
        return $row;
    }

} 