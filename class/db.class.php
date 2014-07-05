<?php

//	*******************
//	***	DATABASE	***
//	*******************

class db
{
	var $server;
	var $login;
	var	$password;
	var $name;

    private $_db;
	
	/*
	 * 	Constructor
	 */
	function db()
	{
		$this->server	= $GLOBALS["Config"]["DATABASE"]["DBSERVER"];
		$this->login	= $GLOBALS["Config"]["DATABASE"]["DBUSER"];
		$this->password	= $GLOBALS["Config"]["DATABASE"]["DBPASSWORD"];
		$this->name		= $GLOBALS["Config"]["DATABASE"]["DBNAME"];
        $this->sql_connect();
	}


    /*
     * 	Erreur SQL
     */
    function sql_error($parms="")
    {
        echo "<div class=\"error\"><u>Erreur SQL</u> (".$this->sql_errno().")<br><i>".mysql_error()."</i><br>";
        if ($parms)
            echo "<i>".$parms."</i>";
        echo "</div>";
        exit();
    }


    /*
     * 	Erreur SQL
     */
    function sql_errno()
    {
        return $this->_db->errno;
    }

    function sql_query($req)
    {
        return mysqli_query($this->_db, $req);
    }

	
	/*
	 * Connexion à la base
	 */	
	function sql_connect()
	{
        $this->_db = mysqli_connect($this->server, $this->login, $this->password, $this->name);

        /* check connection */
        if ($this->_db->connect_errno) {
            printf("Connect failed: %s\n", $this->_db->connect_error);
            exit();
        }
	}
	
	
	/*
	 * Libérer un RES
	 */	
	function sql_free_result($res)
	{
        if (isset($res))
		    mysqli_free_result($res);
	}
	
	
	/*
	 * Faire un select : suppose ne retourner qu'une seule ligne -> sinon KO !!!!
	 */	
	function sql_select(&$row, $req)
	{
        $res = $this->sql_query($req);

        if ($this->sql_errno())
            $this->sql_error($req);

		$nb_rows = $this->sql_count_cur($res);
		if ( $nb_rows == 1 )
		{
            $row = $this->sql_fetch_cur($res);
            $this->sql_free_result($res);
			Return $nb_rows;
		}
		elseif ( $nb_rows == 0 )
		{
            $this->sql_free_result($res);
			Return -100;
		}
		else
		{
			$this->sql_error($req."<br>La requête retourne trop d'occurence");
		}
        $this->sql_free_result($res);
		Return 0;
	}
	
	
	/*
	 * Faire un select pour récupérer les valeurs dans un tableau (pour les COMBOs !!!!)
	 * La requête ne doit retourner deux colonnes :
	 * - ID			: la 1ere constitue la clef
	 * - LIBELLE	: la 2nde constitue le libellé
	 */
	function sql_get_array(&$row, $req)
	{
		$res=null;
		
		$this->sql_open_cur($res, $req);
		if ($this->sql_errno())
            $this->sql_error($req);
		$nb_rows = $this->sql_count_cur($res);
		while ($tmp = $this->sql_fetch_cur($res)) 
		{
			$row[$tmp->ID] = $tmp->LIBELLE;
		}
        $this->sql_free_result($res);
		Return $nb_rows;
	}
	
	
	/*	
	 * Faire un select pour récupérer les valeurs dans un tableau associatif
	 */
	function sql_select_array(&$row, $req)
	{	
        $res = $this->sql_query($req);
		if ($this->sql_errno() || !$res)
            $this->sql_error($req);
		$nb_rows = $this->sql_count_cur($res);
		if ( $nb_rows == 1 )
		{	
			$row = $this->sql_fetch_array($res);
            $this->sql_free_result($res);
			Return 1;
		}
		elseif ( $nb_rows == 0 )
		{
            $this->sql_free_result($res);
			Return -100;
		}
		else
		{	
			$this->sql_error($req."<br>La requête retourne trop d'occurence");
		}
        $this->sql_free_result($res);
		Return 0;
	}
	
	
	/*
	 * Prepare un fetch
	 */
	function sql_open_cur(&$res, $req)
	{
        $res = $this->sql_query($req);
		if ($this->sql_errno())
            $this->sql_error($req);
	}
	
	
	/*
	 * Compte le nb d'occurence trouvée lors d'une requête
	 */
	function sql_count_cur(&$res)
	{	if (isset($res))
		    return $res->num_rows;
        else
            return 0;
	}
	
	
	/*
	 * Exécute un Fetch
	 */
	function sql_fetch_cur($res)
	{
		if (!isset($res) || $this->sql_count_cur($res)<1)
            return NULL;
        return $res->fetch_object();
	}
	
	
	/*
	 * Exécute un Fetch dans un tableau
	 */
	function sql_fetch_array($res)
	{
		if (!isset($res) || $this->sql_count_cur($res)<1)
            return NULL;
		return $res->fetch_array(MYSQLI_ASSOC);
	}
	
	
	/*
	 * Fin d'un Fetch
	 */
	function sql_close_cur($res)
	{	
		if (!isset($res) || $this->sql_count_cur($res)<1)
            return NULL;
		$this->sql_free_result($res);
	}
	
	
	/*
	 * Exécuter une requête (update, insert, ...)
	 */
	function sql_execute($req)
	{
        $this->sql_query($req);
		if ($this->sql_errno())
            $this->sql_error($req);
	}
	
	
	/*
	 * Faire un INSERT
	 */	
	function sql_insert($table, $valeurs)
	{	
		//	Init
		$resInfos=null;
		$req = "INSERT INTO $table ";
		$i=0;
		$cols=" (";
		$values="values (";
	
		//	Récupérer les colonnes de la table
		$this->sql_open_cur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//$count=count($valeurs);
	
		//	CLAUSE SET
		while ($rowInfos=$this->sql_fetch_cur($resInfos)) 
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
		
		$this->sql_close_cur($resInfos);
		$req .= $cols." ".$values;

		$this->sql_execute($req);
	}
	
	
	/*
	 * Faire un REPLACE
	 */
	function sql_replace($table, $valeurs, $keys=NULL)
	{	
		//	Init
		$resInfos=null;
		$req = "REPLACE INTO $table ";
		$i=0;
		$cols=" (";
		$values="values (";
	
		//	Récupérer les colonnes de la table
		$this->sql_open_cur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//	CLAUSE SET
		while ($rowInfos=$this->sql_fetch_cur($resInfos)) 
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
	
		$this->sql_close_cur($resInfos);
	
		$req .= $cols." ".$values;
	
		$this->sql_execute($req);
	}
	
	
	/* 
	 * Faire UPDATE
	 */
	function sql_update($table, $keys, $valeurs)
	{	
		//	Init
		$resInfos=null;
		$req = "Update $table SET ";
		$i=0;
		$where="";
	
		//	Récupérer les colonnes de la table
		$this->sql_open_cur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//	CLAUSE SET
		while ($rowInfos=$this->sql_fetch_cur($resInfos)) 
		{	
			if (array_key_exists ( $rowInfos->Field , $valeurs ))
			{
				$i++;
				if ($valeurs[$rowInfos->Field]==NULL)
					$req .= " ".$rowInfos->Field." = NULL, ";
				elseif (get_magic_quotes_gpc())
					$req .= " ".$rowInfos->Field." = '".$valeurs[$rowInfos->Field]."', ";
				else
					$req .= " ".$rowInfos->Field." = '".addslashes ($valeurs[$rowInfos->Field])."', ";
			}
		}
		// on vire le dernier ', ' de la req
		$req = substr($req, 0, strlen($req) - 2 );
		$this->sql_close_cur($resInfos);
	
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
		$this->sql_execute($req);
	}
	
	
	/*
	 * Faire un DELETE
	 */
	function sql_delete($table, $keys)
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
		$this->sql_execute($req);
	}
	
	
	/*
	 * Renvoyer la dernière valeur de la clef insérer dans la table...
	 */	
	function sql_last_insert($table, $key)
	{
		$row=null;
		$req=	"SELECT	$key ".
				"FROM	$table ".
				"WHERE	$key = LAST_INSERT_ID()";
		$this->sql_select_array($row, $req);
	
		return $row[$key];
	}
}

$db= new db();