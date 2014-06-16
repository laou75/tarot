<?php
class template
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
	function template($db)
	{
		setlocale(LC_TIME, "fr");

//		$_SERVER["HTTP_USER_AGENT"]
//		PalmSource/Palm-D050; Blazer/4.3
		$this->modeMini = strpos($_SERVER["HTTP_USER_AGENT"], "Palm");
//		$this->modeMini = true;	
		$this->zoomMini = 2;	// divisé par 2	
		$this->cellSpacing=$this->modeMini?12:20;	
		
		$this->nameCSS=($this->modeMini)?"pda_styles.css":"styles.css";
		$this->titre="Tarot";
		$this->pageDefaut=1;
		$this->db=$db;

		if	(!array_key_exists("id", $_GET))
			$this->id = $this->pageDefaut;		
		else
			$this->id = $_GET["id"];		
		$this->rowPage = $this->getInfosMenu($this->id);
		$this->titre = (isset($this->rowPage->description))?$this->rowPage->description:$this->rowPage->label;
		
		$logo = $this->modeMini?"":(isset($this->rowPage->logo))?$this->makeImg("logos/".$this->rowPage->logo):"";

		//	Traiter les données postées
		if (count($_POST)>0)
			include("pages/".$this->id.".post.inc.php");

		ob_start("ob_gzhandler");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title><?=$this->titre;?></title>
		<link rel="stylesheet" href="<?=$this->nameCSS;?>" type='text/css' media="screen">
		<script type='text/javascript' src='<?=$GLOBALS["Config"]["URL"]["ROOT"];?>js/main.js'></script>
<?
		//	Inclure le js spécifique si y'en a
		if	(file_exists("js/".$this->id.".js"))
			echo "		<script type='text/javascript' src='".$GLOBALS["Config"]["URL"]["ROOT"]."js/".$this->id.".js'></script>\n";
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
		<div class="page">
			<table width="100%" class="page" cellspacing=<?=$this->cellSpacing;?>>
				<tr><td align="center"><?=$logo." ";?></td><td class="bandeau"><h1><?=$this->titre.$libCtxt;?></h1></td></tr>
				<tr>
					<td class="menu">
<?
//		$this->getChemin($chemin, $this->id);
		$this->getChemin($this->id);
		echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
		echo $this->drawMenu($this->id, 0, "");
		echo "</table>";
		
?>
					</td>
					<td class="corps">
<?
//$retour = (array_key_exists("HTTP_REFERER", $_SERVER))?$this->makeBouton($_SERVER["HTTP_REFERER"], $this->makeImg("retour.gif")."&nbsp;"."Retour"):"";
$filAriane="";
if (is_array($this->cheminMenu))
{
	$this->cheminMenu = array_reverse($this->cheminMenu, true);
	foreach($this->cheminMenu as $id => $detail)
	{
		$filAriane.="&gt; <span class='filAriane'>".$detail["label"]."</span> ";
	}
}
echo"<table width='100%'>\n".
	"	<tr>\n".
	"		<td align='left' width='80%'>".$filAriane."</td>\n".
//	"		<td align='right' width='20%'>".$retour."</td>\n".
	"	</tr>\n".
	"</table>\n".
	"<hr>";
if	(file_exists($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php"))
	include($GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php");
else
	echo $GLOBALS["Config"]["PATH"]["PAGE"].$this->id.".inc.php";
/*
echo "<pre>";
print_r($this->cheminMenu);
echo "</pre>";
*/
?>
					</td>
				</tr>
			</table>
		</div>
		<div id="pop-up" class="pop-portrait" style="display:none;"></div>
	</body>
</html>
<?
//		<div id="pop-up" name="pop-up" class="pop-portrait" style="display:none;"></div>
	ob_end_flush();
	}


	/*
	 * 
	 */
	function drawMenu ($id, $id_pere, $indent="")
	{
		$ret="";
		$res=null;
//		$where = "where 1 ";
		// On part de la racine et on affiche tous les dossiers jusqu'au dossier à afficher
		$req = "select * from menu where id_pere = ".$id_pere." and visible_menu = 1 order by ordre asc";
		$this->db->sql_open_cur($res, $req);
		$nb = $this->db->sql_count_cur($res);
		$i=0;
		while	($row=$this->db->sql_fetch_cur($res))
		{
			$class_explorer = ($row->id==$id)?"explorer-sel":"explorer";
			$class="";

//			$icone = ($row->id==$this->id)?$this->makeImg("moins.gif"):$this->makeImg("plus.gif");	
			$icone = (is_array($this->cheminMenu) && array_key_exists($row->id, $this->cheminMenu))?$this->makeImg("moins.gif"):$this->makeImg("plus.gif");	
			
//			$label=(isset($row->icone)&&strlen($row->icone)>0)?$this->makeImg($row->icone)."&nbsp;".$row->label:$row->label;
			$label=(isset($row->icone)&&strlen($row->icone)>0)?$this->makeImg($row->icone)."&nbsp;".($this->modeMini?$row->labelCourt:$row->label):($this->modeMini?$row->labelCourt:$row->label);
			$ret .="<tr>\n".
					"	<td>\n".
					"		<table cellpadding='0' cellspacing='0' border='0'>\n".
					"			<tr valign='top	'>\n".
					"				<td>".$indent."</td><td>".$icone."</td>\n".
					"				<td class=\"".$class."\">".$this->makeLink("index.php?id=".$row->id, $label,  $row->description, $class_explorer)."&nbsp;</td>\n".
					"			</tr>\n".
					"		</table>\n".
					"	</td>\n".
					"</tr>\n";
			// Quand le dossier lu est sur le chemin, on cherche ses sous-dossiers
			if	(is_array($this->cheminMenu) && array_key_exists($row->id, $this->cheminMenu))
			{	
				$tmp = $indent.$this->makeImg("vide.gif", null, " width=20");
				$ret .= $this->drawMenu ($id, $row->id, $tmp);
			}
		}
		$this->db->sql_close_cur($res);
		return $ret;
	}
	
	/*
	 * 
	 */
/*
	function getChemin($id)
	{
		$row = $this->getInfosMenu($id);
		if	(is_array($this->cheminMenu))
			$i=count($this->cheminMenu);
		else
			$i=0;
		if	($row)
		{
			$i++;
			$this->cheminMenu[$i]["id"] = $row->id;
			$this->cheminMenu[$i]["label"] = $row->label;
			$this->cheminMenu[$i]["description"] = $row->description;
			if	($row->id_pere!=0) 
				$this->getChemin($row->id_pere);
		}
	}
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
		$req = "select * from menu where id = ".$id;
		$this->db->sql_select($row, $req);
		return $row;
	}


	/*
	 * 
	 */
	function makeLink($url, $label, $title="", $css="", $options="")
	{
		/*
		$aRpl = array('&', '&amp;');
		$url = strtr($url, $aRpl);
		*/
		if (isset($title) && strlen($title)>0) $title=" title=\"$title\"";
		if (isset($css) && strlen($css)>0) $css=" class=\"$css\"";
		if (substr(strtolower($url), 0, 5) == "http:") {
			//exit("tagada");
			$ret = "<a href=\"".$url."\"$title$css$options>$label</a>";
		} else {
			//echo 'pouet .:'.$url.':.<br>'.$GLOBALS["Config"]["URL"]["ROOT"].$url.'<br>';
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

//		$label=(isset($row->icone))?$this->makeImg($row->icone)."&nbsp;".$row->label:$row->label;
		$label=($this->modeMini)?"":$row->label;

		$label=(isset($row->icone))?$this->makeImg($row->icone)."&nbsp;".$label:$label;

		if	($id==(int)$id)
			$url=(isset($parm))?"index.php?id=".$id."&amp;".$parm:"index.php?id=".$id;
		else
			$url=$id;
//		return "<nobr>".$this->makeLink($url, $label, (isset($row->description))?$row->description:$row->label, "bouton", $options)."</nobr>";
		return $this->makeLink($url, $label, (isset($row->description))?$row->description:$row->label, "bouton", $options);
	}

	/*
	 * 
	 */
	function makeLinkBoutonRetour($id, $parm=null)
	{
		$row=$this->getInfosMenu($id);

		$label=$this->makeImg("retour.gif")."&nbsp;Retour";
		if	($id==(int)$id)
			$url=(isset($parm))?"index.php?id=".$id."&amp;".$parm:"index.php?id=".$id;
		else
			$url=$id;
//		return "<nobr>".$this->makeLink($url, $label, (isset($row->description))?"Retour à ".$row->description:"Retour à ".$row->label, "bouton")."</nobr>";
		return $this->makeLink($url, $label, (isset($row->description))?"Retour à ".$row->description:"Retour à ".$row->label, "bouton");
	}

	/*
	 * 
	 */
	function makeBouton($url, $label, $title="")
	{
		if (isset($title) && strlen($title)>0) $title=" title=\"$title\"";
		return	$this->makeLink($url, $label, $title, "bouton"); 
	}

	/*
	 * 
	 */
	function makeImg($img, $alt="", $options="")
	{
/*
		$Mini="";
		if ($this->modeMini)
		{
			$size = getimagesize($GLOBALS["Config"]["URL"]["KIT"].$img);
			$Mini=$this->modeMini?" width=\"".($size[0]/$this->zoomMini)."px\" height=\"".($size[1]/$this->zoomMini)."px\"":"";
		}
*/
//		if (file_exists($GLOBALS["Config"]["PATH"]["IMG"].$img))
		{
			if (isset($alt) && strlen($alt)>0) 
				$alt=" alt=\"$alt\"";
			else
				$alt=" alt=\"\"";
			return "<img src=\"".$GLOBALS["Config"]["URL"]["KIT"].$img."\" border=0$alt$options>";
		}
//		elseif ($GLOBALS["Config"]["SITE"]["DEBUG"]=="TRUE")
//			return "<span class=\"error\">Image '".$GLOBALS["Config"]["PATH"]["IMG"].$img."' introuvable</span>";
	}

	/*
	 * 
	 */
	function makeIllustration($img, $alt="", $options="")
	{
/*
		$Mini="";
		if ($this->modeMini)
		{
			$size = getimagesize($GLOBALS["Config"]["URL"]["IMG"].$img);
			$Mini=$this->modeMini?" width=\"".($size[0]/$this->zoomMini)."px\" height=\"".($size[1]/$this->zoomMini)."px\"":"";
		}
*/
		if (isset($alt) && strlen($alt)>0) 
			$alt=" alt=\"$alt\"";
		else
			$alt=" alt=\"\"";
		return "<img src=\"".$GLOBALS["Config"]["URL"]["IMG"].$img."\" border=0$alt$options>";
	}

	/*
	 * 
	 */
	function makePortrait($img, $alt="", $options="")
	{
/*
		$Mini="";
		if ($this->modeMini)
		{
			$size = getimagesize($GLOBALS["Config"]["URL"]["PORTRAIT"].$img);
			$Mini=$this->modeMini?" width=\"".($size[0]/$this->zoomMini)."px\" height=\"".($size[1]/$this->zoomMini)."px\"":"";
		}
*/
//		if (file_exists($GLOBALS["Config"]["PATH"]["IMG"].$img))
		{
			if (isset($alt) && strlen($alt)>0) 
				$alt=" alt=\"$alt\"";
			else
				$alt=" alt=\"\"";
//			return "<img src=\"".$GLOBALS["Config"]["URL"]["IMG"].$img."\" border=0$alt$options>";
			return "<img src=\"".$GLOBALS["Config"]["URL"]["PORTRAIT"].$img."\" border=0$alt$options>";
		}
//		elseif ($GLOBALS["Config"]["SITE"]["DEBUG"]=="TRUE")
//			return "<span class=\"error\">Image '".$GLOBALS["Config"]["PATH"]["IMG"].$img."' introuvable</span>";
	}

	/*
	 * 
	 */
	function getJoueur($id)
	{
		$row=null;
		$this->db->sql_select($row, "select * from joueurs where id=".$id." ");
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
		/*
  		echo "<pre>";
		print_r($script);
		echo "</pre>";
		*/

		$ret="<a href=\"".$script["url"]."\">".$script["titre"]."</a>";
		return $ret;
	}

	/*
	 * 
	 */
/*
	function drawBarreBouton($colonnes=null, $retour=null)
	{
		if (isset($retour) && isset($colonnes))
		{
			$ret=	"<table width='100%'>\n".
					"	<tr>\n".
					"		<td>\n".
					"			<table>\n".
					"				<tr>\n";
		}
		else
			$ret=	"<table width='100%'>\n".
					"	<tr>\n";
		if (isset($colonnes))
			foreach($colonnes as $id => $detail)
			{
				$ret.=	"					<td>".$detail."</td>\n";
			}
		if (isset($retour) && isset($colonnes))
			$ret.=	"				</tr>\n".
					"			</table>\n".
					"		</td>";
		if (isset($retour))
			$ret.=	"		<td align='right'>".$retour."</td>\n";
		$ret.=	"	</tr>\n".
				"</table\n>".
				"<hr>\n";
		return $ret;
	}
*/
	/*
	 * 
	 */
	function drawBarreBouton($colonnes=null, $retour=null)
	{
		if (isset($retour) && !isset($colonnes))
			$ret=	"<table width='100%'>\n".
					"	<tr>\n".
					"		<td align='right'>\n";
		else
			$ret=	"<table width='100%'>\n".
					"	<tr>\n".
					"		<td align='left'>\n";
		if (isset($colonnes))
		{
			foreach($colonnes as $id => $detail)
			{
				$ret.=	$detail." \n";
			}
		}
		if (isset($retour) && isset($colonnes))
			$ret.=	"		</td>".
					"		<td align='right'>";
		if (isset($retour))
			$ret.=	$retour."\n";
		$ret.=	"		</td>\n".
				"	</tr>\n".
				"</table\n>".
				"<hr>\n";
		return $ret;
	}

	/*
	 * 
	 */
	function openListe($colonnes, $action=false)
	{
		$bMax=false;
		if ($bMax)
		{
			$wT =  " width='100%'";
			$wA = ($action)?" width='20%'":"";
		}
		else
		{
			$wT =  "";
			$wA = "";
		}
		$ret=	"<table class='liste'".$wT.">\n".
				"	<tr>";
		if	($action)
			$ret.=	"		<td".$wA.">&nbsp;</td>\n";
		foreach($colonnes as $id => $detail)
		{
			$ret.=	"		<th>".$detail."</th>\n";
		}
		$ret.=	"	</tr>\n";
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
//			$ret.=	"		<td style='vertical-align: middle;text-align:center;'><nobr>";
			$ret.=	"		<td style='vertical-align: middle;text-align:center;'>";
			foreach($actions as $id => $detail)
			{
				$ret.=$detail." ";
			}
//			$ret.=	"</nobr></td>\n";
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
		$ret=	"</table>\n";
		return $ret;
	}

	/*
	 * 
	 */
	function lienPortrait($image="", $label, $labelLong=null)
	{
		if ($image=="") $image="inconnu.gif";
		if (!isset($labelLong)) $labelLong=$label;
		$ret=	"<a href=\"#\" onMouseOver=\"openPortrait('".$image."','".$labelLong."');\" onMouseOut=\"closePortrait();\">".$label."</a>";
		return $ret;
	}

	/*
	 * 
	 */
	function lienPopup($label, $html, $titre=null)
	{
		$tmp =str_replace("'", "#QUOT#", $html);
		$tmp =str_replace('"', "#DBLQUOT#", $tmp);
/*
		$tmp =str_replace('>', "#GT#", $tmp);
		$tmp =str_replace('<', "#LT#", $tmp);
*/
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
/*
session_name("tarot_session");
session_start();
*/
$tpl= new template($db);
?>