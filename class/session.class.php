<?php
class session
{

	var	$logId;
	var	$numSession;
	var	$role;

    function session() 
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
?>