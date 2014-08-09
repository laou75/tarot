<div class="row">
    <div class="col-md-4 text-center">
<?php
echo $this->makeIllustration('tarot.png', '', 'class="img-responsive"');
?>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                <h4>Derniers tournois</h4>
<?php
echo $this->openListe(array('Commencé le'));
$tournois = new Tournoi($this->db);
$listeTournois = $tournois->getLast();
foreach($listeTournois as $row)
{
    echo $this->ligneListe(
            array(
                $this->makeLinkFromId(
                    30,
                    strftime("%d", $row->datedeb) . '/' . strftime("%m", $row->datedeb) . '/' . strftime("%Y", $row->datedeb),
                    'id_tournoi='.$row->id
                ) . ($row->commentaires ? '<br/>'.substr($row->commentaires, 0, 128) : '')
            ,
            )
        );
}
echo $this->closeListe();
?>
            </div>
            <div class="col-md-4">
                <h4>Dernières sessions</h4>
<?php
echo $this->openListe(array("Commencé le"));
$sessions = new Session($this->db);
$listeSessions = $sessions->getLast();
foreach($listeSessions as $row)
{
    echo $this->ligneListe(
        array(
            $this->makeLinkFromId(
                10,
                strftime("%d", $row->datedeb) . '/' . strftime("%m", $row->datedeb) . '/' . strftime("%Y", $row->datedeb),
                'id_tournoi='.$row->id_tournoi.'&amp;id_session='.$row->id
            ) . ($row->commentaires ? '<br/>'.substr($row->commentaires, 0, 128) : ''),
        )
    );
}
echo $this->closeListe();
?>
            </div>
            <div class="col-md-4">
                <h4>Dernières parties</h4>
<?php
echo $this->openListe(array("Commencé le"));
$parties = new Partie($this->db);
$listeParties = $parties->getLast();
foreach($listeParties as $row)
{
    echo $this->ligneListe(
        array(
            $this->makeLinkFromId(
                10,
                strftime("%d", $row->date) . '/' . strftime("%m", $row->date) . '/' . strftime("%Y", $row->date),
                'id_tournoi='.$row->id_tournoi.'&amp;id_session='.$row->id_session
            ) . ($row->commentaires ? '<br/>'.substr($row->commentaires, 0, 128) : ''),
        )
    );
}
echo $this->closeListe();
?>
            </div>
        </div>
    </div>
</div>