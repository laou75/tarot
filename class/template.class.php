<?php
class Template
{
	var $titre;
	var	$pageDefaut;
	var	$id;
	var	$cheminMenu;
	var $db;
	var $rowPage;
	var $modeMini;
	var $zoomMini;
	var $cellspacingMini;
	var $nameCSS;

	/*
	 * 
	 */	
	function __construct($db)
	{
		setlocale(LC_TIME, "fr");

		$this->modeMini = strpos($_SERVER["HTTP_USER_AGENT"], "Palm");
		$this->zoomMini = 2;	// divis� par 2	
		$this->cellSpacing=$this->modeMini?12:20;	
		
		$this->titre="Tarot";
		$this->pageDefaut=1;
		$this->db=$db;

		if	(!array_key_exists("id", $_GET))
			$this->id = $this->pageDefaut;		
		else
			$this->id = $_GET["id"];		
		$this->rowPage = $this->getInfosMenu($this->id);
		$this->titre =
                (isset($this->rowPage->description))
                ?   $this->rowPage->description
                :   (
                        (isset($this->rowPage->label))
                        ?   $this->rowPage->label
                        :   '');
		
		$logo = $this->modeMini
                ?   ''
                :   (isset($this->rowPage->logo))
                    ?   $this->makeImg("logos/".$this->rowPage->logo, 'logo', ' align="left"')
                    :   '';

		//	Traiter les donn�es post�es
		if (count($_POST)>0)
			include("pages/".$this->id.".post.inc.php");

        ob_start();
?>
<!DOCTYPE html>
    <html lang="fr">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?php echo $this->titre;?></title>
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <style type="text/css">
            .lead{margin-top: 55px;}
            input.red,  select.red, {background-color: #ffb79c;}
            label.red{color: #ff115c;}
        </style>
        <link rel="shortcut icon" href="/favicon.ico" />
        <script type='text/javascript' src='<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>js/main.js'></script>
        <script type='text/javascript' src='<?php echo $GLOBALS["Config"]["URL"]["ROOT"];?>js/calcul.js'></script>
<?php
		//	Inclure le js spécifique si y'en a
/*
		if	(file_exists("js/".$this->id.".js"))
			echo "		<script type='text/javascript' src='".$GLOBALS["Config"]["URL"]["ROOT"]."js/".$this->id.".js'></script>\n";
*/
		$libCtxt="";
		if	(isset($_GET["id_tournoi"]))
			$libCtxt .= ", tournoi n°".$_GET["id_tournoi"];
		if	(isset($_GET["id_session"]))
			$libCtxt .= ", session n°".$_GET["id_session"];
		if	(isset($_GET["id_partie"]))
			$libCtxt .= ", partie n°".$_GET["id_partie"];
?>
	</head>
<?php
        if($this->id==11 || $this->id==12) {
?>
	<body onload="calculePoints()">
<?php
        } else {
?>
    <body>
<?php
        }
?>
    <div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand">Tarot</a>

            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
<?php
        $this->getChemin($this->id);
        echo $this->drawMenu();
?>
                </ul>
<?php
        if (!isset($_SESSION['sessionTarot']))
        {
?>
                    <form role="form" class="navbar-form navbar-right" action="/identification.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email" name="identifiant" id="identifiant">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                        </div>
                        <button class="btn btn-success" type="submit">Connexion</button>
                    </form>
<?php
        }
        else
        {
?>
                    <form role="form" class="navbar-form navbar-right" action="/logout.php" method="post">
                        <button class="btn btn-warning" type="submit">Déconnexion</button>
                    </form>
<?php
        }
?>
            </div><!--/.navbar-collapse -->
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 55px;">
        <h2 style="float: left;"><!--<?php echo $logo?>--><?php echo $this->titre.$libCtxt;?></h2>
    </div>

    <div class="container-fluid">
<?php
        if	(file_exists($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php"))
            include($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php");
        else
            echo $GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php";
?>
        </div>
		<div id="pop-up" class="pop-portrait" style="display:none;"></div>
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/highcharts.js"></script>
    <?php
    if	(file_exists($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".plot.inc.php"))
        include($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".plot.inc.php");
    ?>

	</body>
</html>
<?php
	    ob_end_flush();
	} // end __construct()

	/*
	 * 
	 */
	function drawMenu ($idPere=1)
	{
		$ret="";
		$res=null;
		// On part de la racine et on affiche tous les dossiers jusqu'au dossier à afficher
		$req = "select * from menu where id_pere = " . intval($idPere) . " and visible_menu = 1 order by ordre asc";
		$this->db->sqlOpenCur($res, $req);
		while	($row=$this->db->sqlFetchCur($res))
		{
            $ret .= "<li>".$this->makeLink("index.php?id=".$row->id, $row->label,  $row->description);
		}
		$this->db->sqlCloseCur($res);
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
		$row=null;
		$req = "select * from menu where id = " . intval($id);
		$this->db->sqlSelect($row, $req);

		return $row;
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
	function makeLinkBouton($id, $parm=null, $options="")
	{
		$row=$this->getInfosMenu($id);

		$label= ($this->modeMini) ? '' : $row->label;

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
        return "<img src=\"".$GLOBALS["Config"]["URL"]["KIT"].$img."\" border=0$alt$options>";
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
		return "<img src=\"".$GLOBALS["Config"]["URL"]["IMG"].$img."\" $alt$options>";
	}

	/*
	 * 
	 */
	function makePortrait($img, $alt="", $options="")
	{
		{
			if (isset($alt) && strlen($alt)>0) 
				$alt=" alt=\"$alt\"";
			else
				$alt=" alt=\"\"";
			return "<img src=\"".$GLOBALS["Config"]["URL"]["PORTRAIT"].$img."\" border=0$alt$options>";
		}
	}

	/*
	 * 
	 */
	function getJoueur($id)
	{
		$row=null;
		$this->db->sqlSelect($row, "select * from joueurs where id=" . intval($id));
		if (isset($row->portrait) && strlen($row->portrait)>0) 
			$portrait="<br>".$this->makePortrait("mini/".$row->portrait, $row->prenom." ".$row->nom);
		else
			$portrait="";
		return $row->prenom." ".$row->nom.$portrait;
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
        $ret = '<div class="row"><div class="col-md-8">';
        if (isset($colonnes))
        {
            foreach($colonnes as $id => $detail)
            {
                $ret.=	$detail." \n";
            }
        }
        $ret .= '</div>';
        $ret .= '<div class="col-md-4 text-right">';
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
	function ligneListe($colonnes, $actions=null, $options=null)
	{
		$ret="<tr>\n";
		if (isset($actions))
		{
			$ret.=	"		<td style='vertical-align: middle;text-align:center;'>";
			foreach($actions as $id => $detail)
			{
				$ret.=$detail." ";
			}
			$ret.=	"</td>\n";
		}
		$options=isset($options)?" ".$options:"";
		foreach($colonnes as $id => $detail)
		{
			if (strlen($detail)==0) $detail="&nbsp;";
			$ret.=	"		<td class='liste'".$options.">".$detail."</td>\n";
		}
		return $ret."</tr>";
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
	function lienPortrait($image="", $label, $labelLong=null)
	{
        return $label;
		if ($image=="") $image="inconnu.gif";
		if (!isset($labelLong)) $labelLong=$label;
		$ret=	"<a href=\"#\" onMouseOver=\"openPortrait('".$image."','".$labelLong."');\" onMouseOut=\"closePortrait();\">".$label."</a>";
		return $ret;
	}

	/*
	 * 
	 */
	function lienPopup($label, $html)
	{
		$tmp =str_replace("'", "#QUOT#", $html);
		$tmp =str_replace('"', "#DBLQUOT#", $tmp);

		$tmp =str_replace("\n", "", $tmp);
		$ret=	"<a href=\"#\" onMouseOver=\"openPopup('".$tmp."');\" onMouseOut=\"closePopup();\">".$label."</a>";
		return $ret;
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
}