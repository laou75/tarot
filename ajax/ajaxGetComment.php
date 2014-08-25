<?php
define('PATH_ROOT', realpath('..'));
include(PATH_ROOT.'/include/config.inc.php');

$db= new Db();

header( 'Content-Type: text/html; charset=UTF-8' );
$id_tournoi = array_key_exists('id_tournoi', $_GET) ? $_GET['id_tournoi'] : '';
$id_session = array_key_exists('id_session', $_GET) ? $_GET['id_session'] : '';
$id_partie  = array_key_exists('id_partie', $_GET) ? $_GET['id_partie'] : '';
$param='';
if(!empty($id_partie))
{
    $titre = 'Commentaire Partie n°'.$id_partie;
    $partie = new Partie($db);
    $data = $partie->getPartieById($id_tournoi, $id_session, $id_partie);
    $param='id_partie='.$id_partie.'&id_session='.$id_session.'&id_tournoi='.$id_tournoi;
}
elseif(!empty($id_session))
{
    $titre = 'Commentaire Session n°'.$id_session;
    $session = new Session($db);
    $data = $session->getSessionById($id_tournoi, $id_session);
    $param='id_session='.$id_session.'&id_tournoi='.$id_tournoi;
}
elseif(!empty($id_tournoi))
{
    $titre = 'Commentaire Tournoi n°'.$id_tournoi;
    $tournoi = new Tournoi($db);
    $data = $tournoi->getTournoiById($id_tournoi);
    $param='id_tournoi='.$id_tournoi;
}
else
    $data['commentaires'] = 'erreur : mauvais paramètre';
    echo ' <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">'.$titre.'</h4>
        </div>
        <div class="modal-body"><textarea name="commentaires" id="commentaires" style="width: 90%;" rows="10">'.$data['commentaires'].'</textarea></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="enregistrer">Enregistrer</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>';
?>
<script type="text/javascript">
    $("#enregistrer").on( "click", function(){
<?php
if(!empty($id_partie))
{
    $titre = 'Commentaire Partie n°'.$id_partie;
    $partie = new Partie($db);
    $data = $partie->getPartieById($id_tournoi, $id_session, $id_partie);
    $idComment = 'comment_'.$id_tournoi.'_'.$id_session.'_'.$id_partie;
}
elseif(!empty($id_session))
{
    $titre = 'Commentaire Session n°'.$id_session;
    $session = new Session($db);
    $data = $session->getSessionById($id_tournoi, $id_session);
    $idComment = 'comment_'.$id_tournoi.'_'.$id_session;
}
elseif(!empty($id_tournoi))
{
    $titre = 'Commentaire Tournoi n°'.$id_tournoi;
    $tournoi = new Tournoi($db);
    $data = $tournoi->getTournoiById($id_tournoi);
    $idComment = 'comment_'.$id_tournoi;
}
?>
        function nl2br (str, is_xhtml) {
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
        $.ajax({
            type: "GET",
            url: 'ajax/ajaxSaveComment.php?<?php echo $param;?>&commentaires=' + encodeURI($('#commentaires').val()),
            success: function(msg) {
                $("#<?php echo $idComment;?>").html(nl2br($('#commentaires').val()));
            },
            error: function(msg) {
                alert( "Erreur: " + msg );
            }
        });
        $('#myModal').modal('hide');
    });
</script>
