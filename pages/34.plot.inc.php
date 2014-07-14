<?php
$id_session=$_GET["id_session"];
$id_tournoi=$_GET["id_tournoi"];

$joueurs = new Joueur($db);
$parties = new Partie($db);

$categories = '';

//	R�cup�ration des joueurs
$aTabJ = $joueurs->getJoueursBySession($id_tournoi, $id_session);

//	R�cup�ration des parties
$aTabPar = $parties->getPartiesBySession($id_tournoi, $id_session);

$listeJoueurs='';
$totalCumulJoueur=0;
foreach($aTabJ as $idJ => $det)
{
	// Some data
	$old=0;
	$ydata=array();
    $cumulJoueur=0;

	foreach($aTabPar as $idP => $detP)
	{
        $row2 = $parties->getStatsPartie($id_tournoi, $id_session, $idP, $idJ);
        if	(!isset($row2->CUMUL))
            $truc=0;
        else
            $truc=$row2->CUMUL;
        if ($truc<0)
        {
            $cumulJoueur = $cumulJoueur + $truc;
            $totalCumulJoueur = $truc;
        }
		$old	=	$old + $truc;
		$ydata[]=	$old;

        $series[$idJ] = array(  'name'  => $det->nickname,
            'cumul' => $cumulJoueur,
            'data'  => $ydata
        );
        $listeJoueurs .= '\'' . $det->nickname . '\', ';
	}
?>
<script type="text/javascript">
    $(function () {
        $('#container1').highcharts({
            title: {
                text: 'Statistiques tournoi <?php echo $id_tournoi;?>, session <?php echo $id_session;?>',
                x: -20 //center
            },
            subtitle: {
                text: 'Source: guig.net',
                x: -20
            },
            xAxis: {
                title: {
                    text: 'Parties'
                },
                categories: [<?php echo $categories;?>]
            },
            yAxis: {
                title: {
                    text: 'Points'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' pts'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
<?php
$tmp='';
$tmp2='';
$tmp3='';
foreach($series as $k => $v)
{
    $tmp .= 'name: \'' . $v['name'] . '\', '.PHP_EOL.'data: [';
    foreach($v['data'] as $k2 => $v2)
    {
        $tmp .= $v2.', ';
    }
    $tmp = substr($tmp, 0, strlen($tmp)-2).']';
    $tmp .= PHP_EOL.'}, {';
    $tmp2 .= 'name: \'' . $v['name'] . '\', '.PHP_EOL.'data: ['.$v['cumul'].']';
    $tmp2 .= PHP_EOL.'}, {';
    $tmp3 .= '[\'' . $v['name'] . '\', ' . $v['cumul']/$totalCumulJoueur*100 .'],'.PHP_EOL;
}
$tmp = substr($tmp, 0, strlen($tmp)-4);
$tmp2 = substr($tmp2, 0, strlen($tmp2)-4);
$tmp3 = substr($tmp3, 0, strlen($tmp3)-2);
echo $tmp;
?>
}]
});

    });
</script>
<?php
}