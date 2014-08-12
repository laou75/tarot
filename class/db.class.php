<?php

//	*******************
//	***	DATABASE	***
//	*******************

class Db
{
	var $server;
	var $login;
	var	$password;
	var $name;

    private $_db;
	
	/*
	 * 	Constructor
	 */
	function __construct()
	{
		$this->server	= $GLOBALS["Config"]["DATABASE"]["DBSERVER"];
		$this->login	= $GLOBALS["Config"]["DATABASE"]["DBUSER"];
		$this->password	= $GLOBALS["Config"]["DATABASE"]["DBPASSWORD"];
		$this->name		= $GLOBALS["Config"]["DATABASE"]["DBNAME"];
        $this->sqlConnect();
	}


    /*
     * 	Erreur SQL
     */
    function sqlError($parms="")
    {
        $err='';
        $err.="<div class=\"error\"><u>Erreur SQL</u> (" . $this->sqlErrno() . ")<br><i>" . mysql_error() . "</i><br>";
        if ($parms)
            $err.="<i>".$parms."</i>";
        $err .= "</div>";
        exit($err);
        header('Location: error.php?err=' . base64_encode($err));
    }


    /*
     * 	Erreur SQL
     */
    function sqlErrno()
    {
        return $this->_db->errno;
    }


    function sqlQuery($req)
    {
        return mysqli_query($this->_db, $req);
    }

	
	/*
	 * Connexion à la base
	 */	
	function sqlConnect()
	{
        $this->_db = mysqli_connect($this->server, $this->login, $this->password, $this->name);

        /* check connection */
        if ($this->_db->connect_errno) {
            $err = sprintf("Connect failed: %s\n", $this->_db->connect_error);
            $this->sqlError($err);
        }
        if (!$this->_db->set_charset("utf8")) {
            printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", $this->_db->error);
        }
	}


    /*
     * Connexion à la base
     */
    function sqlEscStr($string)
    {
        return mysqli_real_escape_string($this->_db, $string);
    }


        /*
         * Libérer un RES
         */
	function sqlFreeResult($res)
	{
        if (isset($res))
		    mysqli_free_result($res);
	}
	
	
	/*
	 * Faire un select : suppose ne retourner qu'une seule ligne -> sinon KO !!!!
	 */	
	function sqlSelect(&$row, $req)
	{
        $res = $this->sqlQuery($req);

        if ($this->sqlErrno())
            $this->sqlError($req);

		$nb_rows = $this->sqlCountCur($res);
		if ( $nb_rows == 1 )
		{
            $row = $this->sqlFetchCur($res);
            $this->sqlFreeResult($res);
			Return $nb_rows;
		}
		elseif ( $nb_rows == 0 )
		{
            $this->sqlFreeResult($res);
			Return -100;
		}
		else
		{
			$this->sqlError($req."<br>La requête retourne trop d'occurence");
		}
        $this->sqlFreeResult($res);
		Return 0;
	}
	
	
	/*
	 * Faire un select pour récupérer les valeurs dans un tableau (pour les COMBOs !!!!)
	 * La requête ne doit retourner deux colonnes :
	 * - ID			: la 1ere constitue la clef
	 * - LIBELLE	: la 2nde constitue le libellé
	 */
	function sqlGetArray(&$row, $req)
	{
		$res=null;
		
		$this->sqlOpenCur($res, $req);
		if ($this->sqlErrno())
            $this->sqlError($req);
		$nb_rows = $this->sqlCountCur($res);
		while ($tmp = $this->sqlFetchCur($res))
		{
			$row[$tmp->ID] = $tmp->LIBELLE;
		}
        $this->sqlFreeResult($res);
		Return $nb_rows;
	}
	
	
	/*	
	 * Faire un select pour récupérer les valeurs dans un tableau associatif
	 */
	function sqlSelectArray(&$row, $req)
	{	
        $res = $this->sqlQuery($req);
		if ($this->sqlErrno() || !$res)
            $this->sqlError($req);
		$nb_rows = $this->sqlCountCur($res);
		if ( $nb_rows == 1 )
		{	
			$row = $this->sqlFetchArray($res);
            $this->sqlFreeResult($res);
			Return 1;
		}
		elseif ( $nb_rows == 0 )
		{
            $this->sqlFreeResult($res);
			Return -100;
		}
		else
		{	
			$this->sqlError($req."<br>La requête retourne trop d'occurence");
		}
        $this->sqlFreeResult($res);
		Return 0;
	}
	
	
	/*
	 * Prepare un fetch
	 */
	function sqlOpenCur(&$res, $req)
	{
        $res = $this->sqlQuery($req);
		if ($this->sqlErrno())
            $this->sqlError($req);
	}
	
	
	/*
	 * Compte le nb d'occurence trouvée lors d'une requête
	 */
	function sqlCountCur(&$res)
	{	if (isset($res))
		    return $res->num_rows;
        else
            return 0;
	}
	
	
	/*
	 * Exécute un Fetch
	 */
	function sqlFetchCur($res)
	{
		if (!isset($res) || $this->sqlCountCur($res)<1)
            return NULL;
        return $res->fetch_object();
	}
	
	
	/*
	 * Exécute un Fetch dans un tableau
	 */
	function sqlFetchArray($res)
	{
		if (!isset($res) || $this->sqlCountCur($res)<1)
            return NULL;
		return $res->fetch_array(MYSQLI_ASSOC);
	}
	
	
	/*
	 * Fin d'un Fetch
	 */
	function sqlCloseCur($res)
	{	
		if (!isset($res) || $this->sqlCountCur($res)<1)
            return NULL;
		$this->sqlFreeResult($res);
	}
	
	
	/*
	 * Exécuter une requête (update, insert, ...)
	 */
	function sqlExecute($req)
	{
        $this->sqlQuery($req);
		if ($this->sqlErrno())
            $this->sqlError($req);
	}
	
	
	/*
	 * Faire un INSERT
	 */	
	function sqlInsert($table, $valeurs)
	{
        $this->sqlReplaceInsert("INSERT", $table, $valeurs);
	}
	
	
	/*
	 * Faire un REPLACE
	 */
	function sqlReplace($table, $valeurs)
	{
        $this->sqlReplaceInsert("REPLACE", $table, $valeurs);
	}

    /*
     * Factorisation pour sqlInsert() et sqlReplace()
     */
    private function sqlReplaceInsert($verbeSQL, $table, $valeurs)
    {
        //	Init
        $resInfos=null;
        $req = $verbeSQL . " INTO $table ";
        $i=0;
        $cols=" (";
        $values="values (";

        //	Récupérer les colonnes de la table
        $this->sqlOpenCur($resInfos, "SHOW FULL COLUMNS FROM $table");

        //	CLAUSE SET
        while ($rowInfos=$this->sqlFetchCur($resInfos))
        {
            if (array_key_exists ( $rowInfos->Field , $valeurs ) )
            {
                $i++;
                $cols .= $rowInfos->Field.", ";
                if (get_magic_quotes_gpc())
                    $values .= " '".$valeurs[$rowInfos->Field]."', ";
                else
                    $values .= " '".addslashes ($valeurs[$rowInfos->Field])."', ";
            }
        }
        // on vire le dernier ', ' de la req
        $cols = substr($cols, 0, strlen($cols) - 2 )." ) ";
        $values = substr($values, 0, strlen($values) - 2 )." ) ";

        $this->sqlCloseCur($resInfos);

        $req .= $cols." ".$values;

        $this->sqlExecute($req);
    }
	
	/* 
	 * Faire UPDATE
	 */
	function sqlUpdate($table, $keys, $valeurs)
	{	
		//	Init
		$resInfos=null;
		$req = 'update '.$table.' SET ';
		$i=0;
		$where="";
	
		//	Récupérer les colonnes de la table
		$this->sqlOpenCur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//	CLAUSE SET
		while ($rowInfos=$this->sqlFetchCur($resInfos))
		{	
			if (array_key_exists ( $rowInfos->Field , $valeurs ))
			{
				$i++;
				if ($valeurs[$rowInfos->Field]==NULL)
					$req .= " ".$rowInfos->Field." = NULL, ";
				elseif (get_magic_quotes_gpc())
					$req .= " ".$rowInfos->Field." = '".$valeurs[$rowInfos->Field]."', ";
				else
                    $req .= " ".$rowInfos->Field." = '".mysqli_real_escape_string($this->_db, $valeurs[$rowInfos->Field])."', ";
			}
		}
		// on vire le dernier ', ' de la req
		$req = substr($req, 0, strlen($req) - 2 );
		$this->sqlCloseCur($resInfos);
	
		//	CLAUSE WHERE
		while (list ($key, $val) = each ($keys)) 
		{
			if ($val)
			{
				if (strlen($where)>0)
					$where .= "AND $key = '".$val."' ";
				else
					$where .= " WHERE $key = '".$val."' ";
			}
		}
		$req .= $where;
        //exit($req);
		$this->sqlExecute($req);
	}
	
	
	/*
	 * Faire un DELETE
	 */
	function sqlDelete($table, $keys)
	{	
		//	Init
		$req = "DELETE FROM $table ";
		$where="";
	
		//	CLAUSE WHERE
		while (list ($key, $val) = each ($keys)) 
		{
			if ($val)
			{
				if (strlen($where)>0)
					$where .= "AND $key = '".$val."' ";
				else
					$where .= " WHERE $key = '".$val."' ";
			}
		}
		$req .= $where;
		$this->sqlExecute($req);
	}
	
	
	/*
	 * Renvoyer la dernière valeur de la clef insérer dans la table...
	 */	
	function sqlLastInsert($table, $key)
	{
		$row=null;
		$req=	"SELECT	$key ".
				"FROM	$table ".
				"WHERE	$key = LAST_INSERT_ID()";
		$this->sqlSelectArray($row, $req);
	
		return $row[$key];
	}
}