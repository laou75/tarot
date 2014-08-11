<?php
define('PATH_ROOT', realpath('..'));
include(PATH_ROOT.'/include/config.inc.php');

$db= new Db();

$method=$_GET;

header( 'Content-Type: text/html; charset=UTF-8' );

$id_tournoi = array_key_exists('id_tournoi', $method) ? $method['id_tournoi'] : '';
$id_session = array_key_exists('id_session', $method) ? $method['id_session'] : '';
$id_partie  = array_key_exists('id_partie', $method) ? $method['id_partie'] : '';
$commentaires  = array_key_exists('commentaires', $method) ? $method['commentaires'] : '';
if(!empty($id_partie))
{
    $db->sqlUpdate( 'parties',
                    array(  'id'            =>intval($id_partie),
                            'id_session'    =>intval($id_session),
                            'id_tournoi'    =>intval($id_tournoi)),
                    array(  'commentaires'  =>$commentaires));
}
elseif(!empty($id_session))
{
    $db->sqlUpdate( 'sessions',
                    array(  'id'            =>intval($id_session),
                            'id_tournoi'    =>intval($id_tournoi)),
                    array(  'commentaires'  =>$commentaires));
}
elseif(!empty($id_tournoi))
{
    $db->sqlUpdate( 'tournois',
                    array(  'id'            =>intval($id_tournoi)),
                    array(  'commentaires'  =>$commentaires));
}
