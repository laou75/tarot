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
	
	/*
	 * 	Constructor
	 */
	function db()
	{
		$this->server	= $GLOBALS["Config"]["DATABASE"]["DBSERVER"];
		$this->login	= $GLOBALS["Config"]["DATABASE"]["DBUSER"];
		$this->password	= $GLOBALS["Config"]["DATABASE"]["DBPASSWORD"];
		$this->name		= $GLOBALS["Config"]["DATABASE"]["DBNAME"];
	}


	/*
	 * 	Erreur SQL
	 */
	function sql_error($parms="")
	{	
		echo "<div class=\"error\"><u>Erreur SQL</u> (".mysql_errno().")<br><i>".mysql_error()."</i><br>";
		if ($parms) echo "<i>".$parms."</i>";
		echo "</div>";
		exit();
	}
	
	
	/*
	 * Connexion � la base
	 */	
	function sql_connect()
	{	
		$link = mysql_pconnect(	$this->server, $this->login, $this->password); 
		Return mysql_select_db(	$this->name, $link); 
	}
	
	
	/*
	 * Lib�rer un RES
	 */	
	function sql_free_result($res)
	{
		mysql_free_result($res);
	}
	
	
	/*
	 * Faire un select : suppose ne retourner qu'une seule ligne -> sinon KO !!!!
	 */	
	function sql_select(&$row, $req)
	{	
		$res = mysql_db_query(	$this->name, $req);
		if (mysql_errno()) $this->sql_error($req);
		$nb_rows = mysql_num_rows($res);
		if ( $nb_rows == 1 )
		{	
			$row = mysql_fetch_object($res);	
			mysql_free_result($res);
			Return $nb_rows;
		}
		elseif ( $nb_rows == 0 )
		{	
			mysql_free_result($res);
			Return -100;
		}
		else
		{	
			$this->sql_error($req."<br>La requ�te retourne trop d'occurence");
		}
		mysql_free_result($res);
		Return 0;
	}
	
	
	/*
	 * Faire un select pour r�cup�rer les valeurs dans un tableau (pour les COMBOs !!!!)
	 * La requ�te ne doit retourner deux colonnes :
	 * - ID			: la 1ere constitue la clef
	 * - LIBELLE	: la 2nde constitue le libell�
	 */
	function sql_get_array(&$row, $req)
	{
		$res=null;
		
		$this->sql_open_cur($res, $req);
		if (mysql_errno()) $this->sql_error($req);
		$nb_rows = mysql_num_rows($res);
		while ($tmp = $this->sql_fetch_cur($res)) 
		{
			$row[$tmp->ID] = $tmp->LIBELLE;
		}
		mysql_free_result($res);
		Return $nb_rows;
	}
	
	
	/*	
	 * Faire un select pour r�cup�rer les valeurs dans un tableau associatif
	 */
	function sql_select_array(&$row, $req)
	{	
		$res = mysql_query($req);
		if (mysql_errno() || !$res) $this->sql_error($req);
		$nb_rows = mysql_num_rows($res);
		if ( $nb_rows == 1 )
		{	
			$row = mysql_fetch_assoc($res);
			mysql_free_result($res);
			Return 1;
		}
		elseif ( $nb_rows == 0 )
		{	
			mysql_free_result($res);
			Return -100;
		}
		else
		{	
			$this->sql_error($req."<br>La requ�te retourne trop d'occurence");
		}
		mysql_free_result($res);
		Return 0;
	}
	
	
	/*
	 * Prepare un fetch
	 */
	function sql_open_cur(&$res, $req)
	{	
		$res = mysql_db_query(	$this->name, $req);
		if (mysql_errno()) $this->sql_error($req);
	}
	
	
	/*
	 * Compte le nb d'occurence trouv�e lors d'une requ�te
	 */
	function sql_count_cur(&$res)
	{	
		return mysql_num_rows($res);
	}
	
	
	/*
	 * Ex�cute un Fetch
	 */
	function sql_fetch_cur($res)
	{
		if (!isset($res) || $this->sql_count_cur($res)<1) return NULL;
		return mysql_fetch_object($res);
	}
	
	
	/*
	 * Ex�cute un Fetch dans un tableau
	 */
	function sql_fetch_array($res)
	{
		if (!isset($res) || $this->sql_count_cur($res)<1) return NULL;
		return mysql_fetch_array($res, MYSQL_ASSOC);
	}
	
	
	/*
	 * Fin d'un Fetch
	 */
	function sql_close_cur($res)
	{	
		if (!isset($res) || $this->sql_count_cur($res)<1) return NULL;
		return mysql_free_result($res); 
	}
	
	
	/*
	 * Ex�cuter une requ�te (update, insert, ...)
	 */
	function sql_execute($req)
	{
		mysql_db_query(	$this->name, $req);
		if (mysql_errno()) $this->sql_error($req);
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
	
		//	R�cup�rer les colonnes de la table
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
//		exit($req);
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
	
		//	R�cup�rer les colonnes de la table
		$this->sql_open_cur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//	Eliminer les 'ind�sirables'
		//	if (array_key_exists("buttonName", $valeurs)) unset ($valeurs["buttonName"]);
	
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
	 * Faire UPDATE
	 */
	function sql_update($table, $keys, $valeurs)
	{	
		//	Init
		$resInfos=null;
		$req = "Update $table SET ";
		$i=0;
		$where="";
	
		//	R�cup�rer les colonnes de la table
		$this->sql_open_cur($resInfos, "SHOW FULL COLUMNS FROM $table");
	
		//	Eliminer les 'ind�sirables'
		//	if (array_key_exists("buttonName", $valeurs)) unset ($valeurs["buttonName"]);
	
		//$count=count($valeurs);
	
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
	//	exit($req);
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
	 * Renvoyer la derni�re valeur de la clef ins�rer dans la table...
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
$db->sql_connect();
?>