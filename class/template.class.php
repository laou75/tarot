<?php
class Template
{
    var $titre;
    var $titrePage;
	var	$id;
	var	$cheminMenu;
	var $db;

	/*
	 * 
	 */	
	function __construct($db)
	{
		$this->titre=$GLOBALS["Config"]["SITE"]["TITRE"];
		$this->db=$db;

		if	(!array_key_exists("id", $_GET))
			$this->id = $GLOBALS["Config"]["SITE"]["PAGEDEFAULT"];		
		else
			$this->id = $_GET["id"];
		$rowPage = $this->getInfosMenu($this->id);
        $this->titrePage =  (isset($rowPage->description)
                            ?   $rowPage->description
                            :   ((isset($rowPage->label))
                                ?   $rowPage->label
                                :   '')
                            );
        $this->titre = !empty($this->titrePage) ? $this->titre . ' - ' . $this->titrePage : $this->titre;

		//	Traiter les donn�es post�es
		if (count($_POST)>0)
			include("pages/".$this->id.".post.inc.php");
?>
<!DOCTYPE html>
        <html lang="fr" xmlns="http://www.w3.org/1999/html">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?php echo $this->titre;?></title>
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <!-- Richtext Edit-->
        <link rel="stylesheet" type="text/css" href="css/prettify.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap-wysihtml5.css"/>
        <!-- Datepicker-->
        <link id="bsdp-css" type="text/css" href="css/datepicker3.css" rel="stylesheet"/>
        <style type="text/css">
            input.red,  select.red, {background-color: #ffb79c;}
            label.red{color: #ff115c;}
        </style>
        <link rel="shortcut icon" href="<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>favicon.ico" />
<?php
		$libCtxt="";
		if	(isset($_GET["id_tournoi"]))
			$libCtxt .= ", tournoi n°".$_GET["id_tournoi"];
		if	(isset($_GET["id_session"]))
			$libCtxt .= ", session n°".$_GET["id_session"];
		if	(isset($_GET["id_partie"]))
			$libCtxt .= ", partie n°".$_GET["id_partie"];
?>
	</head>
    <body>
?>
        <div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>" class="navbar-brand">Tarot</a>
                </div>
                <div class="navbar-collapse collapse">
<?php
        if (!Sess::isConnected()) {
?>
                    <form role="form" class="navbar-form navbar-right" action="<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>identification.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="login" name="identifiant" id="identifiant">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="password" name="password" id="password">
                        </div>
                        <button class="btn btn-success" type="submit">Connexion</button>
                    </form>
<?php
        } 
        else 
        {
?>
                    <ul class="nav navbar-nav">
                        <?php
                        $this->getChemin($this->id);
                        echo $this->drawMenu();
                        ?>
                    </ul>
                    <form role="form" class="navbar-form navbar-right" action="<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>logout.php" method="post">
                        <span class="text-muted"><?php echo Sess::getPrenom() . ' ' . Sess::getNom();?></span>
                        <button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-off"></span> Déconnexion</button>
                    </form>
<?php
        }
?>
                </div><!--/.navbar-collapse -->
            </div>
        </div>
        <div class="container-fluid" style="margin-top: 55px;">
            <div class=page-header">
                <h3><?php echo ($this->titrePage ? $this->titrePage : 'Tarot').$libCtxt;?></h3>
            </div>
            <div class="container-fluid">
<?php
        $id_tournoi=isset($_GET["id_tournoi"]) ? $_GET["id_tournoi"] : null;
        $id_session=isset($_GET["id_session"]) ? $_GET["id_session"] : null;
        echo '<ol class="breadcrumb">';
        echo '<li>'.$this->makeLinkFromId(1, 'Accueil').'</li>';
        if (isset($id_tournoi) || isset($id_session) || isset($id_partie))
        {
            if (isset($id_tournoi))
            {
                echo '<li>'.$this->makeLinkFromId(30, 'Tournoi '.$id_tournoi, 'id_tournoi='.$id_tournoi).'</li>';
            }
            if (isset($id_session))
            {
                echo '<li>'.$this->makeLinkFromId(10, 'Session '.$id_session, 'id_tournoi='.$id_tournoi.'&amp;id_session='.$id_session).'</li>';
            }
        }
        echo '</ol>';
        if	(file_exists($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php"))
            include($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php");
        else
            echo $GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php";
?>
            </div>
        </div>
        <!--JQuery-->
        <script src="js/jquery-1.11.1.min.js"></script>
        <!--Bootstrap-->
        <script src="js/bootstrap.min.js"></script>
        <!--Highcharts-->
        <script src="js/highcharts.js"></script>
        <script src="js/highcharts-3d.js"></script>
        <script src="js/modules/exporting.js"></script>
        <!--Richtext Edit-->
        <script src="js/wysihtml5-0.3.0.js"></script>
        <script src="js/prettify.js"></script>
        <!--datepicker-->
        <script src="js/bootstrap-datepicker.js"></script>

<script type="text/javascript">
    $(function () {
        $('#commentaires').wysihtml5({
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": true, //Button to insert a link. Default true
            "image": true, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "size": 'sm' //Button size like sm, xs etc.
        });
        $('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            language: "fr",
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
<?php
        if	(file_exists($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".plot.inc.php"))
            include($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".plot.inc.php");
        
        if	(file_exists($GLOBALS["Config"]["PATH"]["JS"].$this->id.".js"))
            include($GLOBALS["Config"]["PATH"]["JS"].$this->id.".js");
?>
	</body>
</html>
<?php
	} // end __construct()

    
	/*
	 * 
	 */
	function drawMenu ($idPere=1)
	{
		$ret="";
        $menus = new Menu($this->db);
        $aTab = $menus->getMenusByIdPere($idPere);
        foreach($aTab as $row)
		{
            $ret .= "<li>".$this->makeLink("index.php?id=".$row->id, $row->label,  $row->description);
        }
		return $ret;
	}

	/*
	 * 
	 */
	function getChemin($id)
	{
		$row = $this->getInfosMenu($id);
		if	($row)
		{
			$this->cheminMenu[$row->id]["id"] = $row->id;
			$this->cheminMenu[$row->id]["label"] = $row->label;
			$this->cheminMenu[$row->id]["description"] = $row->description;
			if	($row->id_pere!=0) 
				$this->getChemin($row->id_pere);
		}
	}
	
	/*
	 * 
	 */
	function getInfosMenu ($id)
	{
        $menus = new Menu($this->db);
        return $menus->getMenuById($id);
	}

	/*
	 * 
	 */
	function makeLink($url, $label, $title="", $css="", $options="")
	{
		if (isset($title) && strlen($title)>0) $title=" title=\"$title\"";
		if (isset($css) && strlen($css)>0) $css=" class=\"$css\"";
		if (substr(strtolower($url), 0, 5) == "http:") {
			$ret = "<a href=\"".$url."\"$title$css$options>$label</a>";
		} else {
			$ret = '<a href="'.$GLOBALS["Config"]["URL"]["ROOT"].$url.'"'.$title.$css.$options.'>'.$label.'</a>';
		}
		return $ret;
	}

    /*
     *
     */
    function makeLinkFromId($id, $label, $param=null, $title="", $css="", $options="")
    {
        $url = $this->getUrlFromId($id, $param);
        if (isset($title) && strlen($title)>0) $title=" title=\"$title\"";
        if (isset($css) && strlen($css)>0) $css=" class=\"$css\"";
        if (substr(strtolower($url), 0, 5) == "http:") {
            $ret = "<a href=\"".$url."\"$title$css$options>$label</a>";
        } else {
            $ret = '<a href="'.$GLOBALS["Config"]["URL"]["ROOT"].$url.'"'.$title.$css.$options.'>'.$label.'</a>';
        }
        return $ret;
    }

    /*
     *
     */
    function getUrlFromId($id, $param=null)
    {
        return (isset($param)) ? 'index.php?id='.$id.'&amp;' . $param : 'index.php?id='.$id;
    }

	/*
	 * 
	 */
	function makeLinkBouton($id, $parm=null, $options="")
	{
		$row=$this->getInfosMenu($id);

		$label= $row->label;
		$desc = (isset($row->description)) ? $row->description : $row->label;

		if	($id==(int)$id)
			$url=(isset($parm)) ? 'index.php?id='.$id.'&amp;' . $parm : 'index.php?id='.$id;
		else
			$url=$id;

        if (isset($row->glyphs))
        {
            $url = $GLOBALS["Config"]["URL"]["ROOT"].$url;
            return '<a class="btn btn-default btn-sm" title="'.$desc.'" href="'.$url.'">'.
                   '<span class="'.$row->glyphs.'"></span> '.$label.
                   '</a>';
        }
        else
        {
            $label = (isset($row->icone)) ? $this->makeImg($row->icone)."&nbsp;".$label:$label;
            return $this->makeLink($url, $label, $desc, "btn btn-default btn-sm", $options);
        }
    }

	/*
	 * 
	 */
	function makeLinkBoutonRetour($id, $parm=null)
	{
		$row=$this->getInfosMenu($id);

		if	($id==(int)$id)
			$url=(isset($parm)) ? "index.php?id=".$id."&amp;".$parm : "index.php?id=".$id;
		else
			$url=$id;
        if (isset($row->description))
            $desc = "Retour : ".$row->description;
        elseif (isset($row->label))
            $desc = "Retour : ".$row->label;
        else
            $desc = 'Retour';
        return '<a class="btn btn-primary btn-sm" title="'.$desc.'" href="'.$url.'">'.
                '<span class="glyphicon glyphicon-share-alt"></span> Retour'.
                '</a>';
	}

	/*
	 * 
	 */
	function makeBouton($url, $label, $title="")
	{
		if (isset($title) && strlen($title)>0)
            $title=" title=\"$title\"";
		return	$this->makeLink($url, $label, $title, "bouton"); 
	}

	/*
	 * 
	 */
	function makeImg($img, $alt="", $options="")
	{
        if (isset($alt) && strlen($alt)>0)
            $alt=" alt=\"$alt\"";
        else
            $alt=" alt=\"\"";
        return "<img src=\"".$GLOBALS["Config"]["URL"]["IMG"].$img."\" $alt$options/>";
	}

	/*
	 * 
	 */
	function makeIllustration($img, $alt="", $options="")
	{
		if (isset($alt) && strlen($alt)>0) 
			$alt=" alt=\"$alt\"";
		else
			$alt=" alt=\"\"";
		return "<img src=\"".$GLOBALS["Config"]["URL"]["IMG"].$img."\" $alt$options/>";
	}

	/*
	 * 
	 */
	function makePortrait($img, $alt="", $options="")
	{
        if (isset($alt) && strlen($alt)>0)
            $alt=" alt=\"$alt\"";
        else
            $alt=" alt=\"\"";
        return "<img src=\"".$GLOBALS["Config"]["URL"]["PORTRAIT"].$img."\" border=0$alt$options class=\"img-responsive\"/>";
	}

	/*
	 * 
	 */
	function getJoueur($id)
	{
        $joueurs = new Joueur($this->db);
        $row = $joueurs->getJoueurById($id);
        if (isset($row->portrait) && strlen($row->portrait)>0)
            $portrait="<br/>".$this->makePortrait("mini/".$row->portrait, $row->prenom." ".$row->nom);
        else
            $portrait="";
		return $row->prenom . ' ' . $row->nom . $portrait;
	}

	/*
	 * 
	 */
	function makeLinkMenu($script)
	{
        return "<a href=\"".$script["url"]."\">".$script["titre"]."</a>";
	}

	/*
	 * 
	 */
	function drawBarreBouton($colonnes=null, $retour=null)
	{
        $ret = '<div class="row"><div class="col-sm-10">';
        if (isset($colonnes))
        {
            foreach($colonnes as $id => $detail)
            {
                $ret.=	$detail." \n";
            }
        }
        $ret .= '</div>';
        $ret .= '<div class="col-sm-2 text-right">';
        if (isset($retour))
        {
            $ret.=	$retour."\n";
        }
        $ret .='</div></div>';
		return $ret;
	}

	/*
	 * 
	 */
	function openListe($colonnes, $action=false, $id='table_id')
	{
        $wT = '';
        $wA = '';
		$ret=	'<table class="table table-striped table-bordered table-hover table-condensed" ' . $wT . ' id="' . $id . '">' . PHP_EOL.
				'	<tr>';
		if	($action)
			$ret.=	'		<th' . $wA . '>&nbsp;</th>' . PHP_EOL;
		foreach($colonnes as $id => $detail)
		{
			$ret.=	'		<th>' . $detail . '</th>' . PHP_EOL;
		}
		$ret.=	'	</tr>' . PHP_EOL;
		return $ret;
	}

	/*
	 * 
	 */
	function ligneListe($colonnes, $actions=null, $options=null, $class='')
	{
        $class = (!empty($class)) ? ' class="'.$class.'"' : '';
		$ret = '<tr'.$class.'>'.PHP_EOL;
		if (isset($actions))
		{
            $ret .=	'		<td>';
			foreach($actions as  $detail)
			{
				$ret .= $detail.' ';
			}
			$ret .=	'</td>'.PHP_EOL;
		}
		$options = isset($options) ? ' '.$options : '';
		foreach($colonnes as $detail)
		{
			if (strlen($detail)==0)
                    $detail='&nbsp;';
			$ret .=	'<td class="liste"'.$options.'>'.$detail.'</td>'.PHP_EOL;
		}
		return $ret.'</tr>'.PHP_EOL;
	}

	/*
	 * 
	 */
	function closeListe()
	{
		$ret=	"</table>";
		return $ret;
	}

    /*
     *
     */
    function getNickname($row)
    {
        return isset($row->nickname) ? $row->nickname : $row->prenom." ".substr($row->nom,0,1).".";
    }

    /*
     *
     */
    function getPortrait($image='', $alt='', $option='')
    {
        return strlen($image)>0 ? $this->makePortrait("mini/".$image, $alt, $option) : '<span class="glyphicon glyphicon-user" style="font-size: 600%;"></span>';
    }

    /*
     *
     */
    function getMaxiPortrait($image='', $alt='', $option='')
    {

        if (isset($alt) && strlen($alt)>0)
            $alt=" alt=\"$alt\"";
        else
            $alt=" alt=\"\"";
        return "<img height=\"300px\" src=\"" . $GLOBALS["Config"]["URL"]["PORTRAIT"] . "mini/".$image . "\" $alt/>";
    }

    /*
     *
     */
    function lienPortrait($image="", $label, $labelLong=null)
    {
        return $label;
    }

	/*
	 * 
	 */
	function openCadre()
	{
		$ret=	"<table cellpadding=0 cellspacing=0 border=0>\n".
				"	<tr>\n".
				"		<td class=\"tl\">".$this->makeImg("vide.gif")."</td>\n". 
				"		<td class=\"t\">".$this->makeImg("vide.gif")."</td>\n". 
				"		<td class=\"tr\">".$this->makeImg("vide.gif")."</td>\n". 
				"	</tr>\n".
				"	<tr>\n".
				"		<td class=\"cl\">".$this->makeImg("vide.gif")."</td>\n". 
				"		<td class=\"c\">".$this->makeImg("vide.gif");
		return $ret;
	}

	/*
	 * 
	 */
	function closeCadre()
	{
		$ret=	"		</td>\n".
				"		<td class=\"cr\">".$this->makeImg("vide.gif")."</td>\n". 
				"	</tr>\n".
				"	<tr>\n".
				"		<td class=\"bl\">".$this->makeImg("vide.gif")."</td>\n". 
				"		<td class=\"b\">".$this->makeImg("vide.gif")."</td>\n". 
				"		<td class=\"br\">".$this->makeImg("vide.gif")."</td>\n". 
				"	</tr>\n". 
				"</table>\n";
		return $ret;
	}

    function getLibClassement($rang)
    {
        return ($rang==1) ? $rang.'<sup>er</sup>' : $rang.'<sup>eme</sup>';
    }

    function drawCarousel($data)
    {
        echo '<div><div id="carousel-example-generic" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators">';
        // indicators
        $tmp = ' class="active"';
        foreach($data as $k => $v)
        {
            echo '<li data-target="#carousel-example-generic" data-slide-to="'.$k.'"'.$tmp.'></li>';
            $tmp = '';
        }
        echo '</ol>';

        //Wrapper for slides
        echo '<div class="carousel-inner">';
        $tmp = ' active';
        foreach($data as $k => $v)
        {
            echo    '<div class="item'.$tmp.'">' . $v['contenu'] .
                    '<div class="carousel-caption">' . $v['caption'] . '</div>' .
                    '</div>';
            $tmp = '';
        }
        echo '</div>';

        //Controls
        echo    '<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">' .
                '<span class="glyphicon glyphicon-chevron-left"></span>' .
                '</a>' .
                '<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">' .
                '<span class="glyphicon glyphicon-chevron-right"></span>' .
                '</a>' .
                '</div></div>';
    }
}
