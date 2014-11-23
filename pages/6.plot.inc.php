<?php
$joueurs = new Joueur($db);
$sessions = new Session($db);

$id_tournoi=$_GET["id_tournoi"];

$series = array();

$aTabJoueurs = $joueurs->getJoueursByTournoi($id_tournoi);
$categories = '';
$aTabSessions=array();

$aTabSessions = $sessions->getSessionByTournoi($id_tournoi);

$listeJoueurs='';
$totalCumulJoueur=0;
foreach($aTabJoueurs as $idJ => $det)
{
	$ydata=array();
    $cumulJoueur=0;
    foreach($aTabSessions as $idS => $detS)
	{
        $row2 = $sessions->getStatsSessionByJoueur($id_tournoi, $idS, $idJ);
        if	(!isset($row2->points))
            $truc=0;
        else
            $truc=$row2->points;
        $ydata[]=	$truc;
        if ($truc<0)
        {
            $cumulJoueur = $cumulJoueur + $truc;
            $totalCumulJoueur = $totalCumulJoueur + $truc;
        }
        $categories .= '\''.$idS.'\', ';
    }
    $series[$idJ] = array(  'name'  => $this->getNickname($det), //$det->nickname,
                            'cumul' => $cumulJoueur,
                            'data'  => $ydata
                        );
    $listeJoueurs .= '\'' . $det->nickname . '\', ';
}

if (strlen($categories)>0)
{
    $categories = substr($categories, 0, strlen($categories)-2);
}
if (strlen($listeJoueurs)>0)
{
    $listeJoueurs = substr($listeJoueurs, 0, strlen($listeJoueurs)-2);
}
?>
<script type="text/javascript">
    $(function () {
        $('#container1').highcharts({
            title: {
            text: 'Statistiques tournoi <?php echo $id_tournoi;?>',
                x: -20 //center
            },
            subtitle: {
            text: 'Source: guig.net',
                x: -20
            },
            xAxis: {
                title: {
                    text: 'Sessions'
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
    $tmp3 .= '[\'' . $v['name'] . '\', ' . abs($v['cumul']/$totalCumulJoueur*100) .'],'.PHP_EOL;
}
$tmp = substr($tmp, 0, strlen($tmp)-4);
$tmp2 = substr($tmp2, 0, strlen($tmp2)-4);
$tmp3 = substr($tmp3, 0, strlen($tmp3)-2);
echo $tmp;
?>
            }]
        });


        $('#container2').highcharts({
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 25,
                    depth: 70
                }
            },
            title: {
                text: 'Cumul des pertes'
            },
            yAxis: {
                title: {
                    text: 'Points'
                }
            },
            xAxis: {
                categories: [<?php echo $listeJoueurs;?>]
            },
            credits: {
                enabled: false
            },
            series: [{
<?php
echo $tmp2;
?>
            }]
        });

        $('#container3').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: false,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Répartition des pertes'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Cumul des pertes',
                data: [
<?php
echo $tmp3;
?>
                ]
            }]
        });
    });
</script>

