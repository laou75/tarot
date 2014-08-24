<?php
class Sess
{

	var	$id;
	var	$numSession;
	var	$role;
    var $nom;
    var $prenom;
    var $nickname;

    function __construct()
    {
    }

    function sessionConnect($data)
    {
        $this->id       =	$data->id;
        $this->nom	    =	$data->nom;
        $this->prenom	=	$data->prenom;
        $this->nickname =	$data->nickname;
    }


    function sessionDisconnect()
	{
		unset($this->id);
        unset($this->nom);
        unset($this->prenom);
        unset($this->nickname);
		unset($_SESSION);
		unset($this);
	}

    static function isConnected()
    {
        return isset($_SESSION['sessionTarot']);
    }

    static function getPrenom()
    {
        return $_SESSION['sessionTarot']->prenom;
    }

    static function getNom()
    {
        return $_SESSION['sessionTarot']->nom;
    }

}