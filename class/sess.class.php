<?php
class Sess
{

	var	$logId;
	var	$numSession;
	var	$role;

    function __construct()
    {
    }

	function sessionConnect($logId, $role)
	{
		$this->logId=	$logId;
		$this->role	=	$role;
	}
	
	function sessionDisconnect()
	{
		unset($this->logId);
		unset($this->role);
		unset($_SESSION);
		unset($this);
	}

}