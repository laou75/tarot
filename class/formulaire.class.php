<?php
class Formulaire
{
	var $valeurs;
	var $widthLabel;
	var $widthData;
	
	function __construct()
	{
		$this->widthLabel = " width=25%";
		$this->widthData = " width=75%" ;
	}

	function setValeurs($valeurs)
	{
		$this->valeurs=$valeurs;
	}

	function setValeur($poste, $valeurs)
	{
		$this->valeurs[$poste]=$valeurs;
	}

	function getValeurs()
	{
		return $this->valeurs;
	}

	function getValeur($key)
	{
		if	(!is_array($this->valeurs))
            return '';
		if	(array_key_exists($key, $this->valeurs)) 
			return $this->valeurs[$key];
		else
			return '';
	}

	function openForm($titre="", $action=null, $enctype="application/x-www-form-urlencoded", $width=null)
	{
		$width=(isset($width))?" width='$width'":" width='75%'";

		if (!isset($action) || strlen($action)<1)
			$action = (array_key_exists("REQUEST_URI", $_SERVER))?"action=\"".$_SERVER["REQUEST_URI"]."\"":"action=\"".$_SERVER["PHP_SELF"]."\"";
		else
			$action = "action=\"".$action."\"";

		if	(count($_POST)>0 && array_key_exists("from", $_POST))
			$from = $_POST["from"];
		else
			$from = (array_key_exists("HTTP_REFERER", $_SERVER))?$this->makeHidden("from", "from", $_SERVER["HTTP_REFERER"]):"";
		return	"<center>\n".
				"	<table".$width." border=0>\n".
				"		<tr>\n".
				"			<td width='100%'>\n".
				"				<form ".$action." method=\"post\" enctype=\"".$enctype."\">\n".
				"					<fieldset>\n".
				"						<legend>".$titre."</legend>\n".
				"						<table width='100%' border=0>\n".
				"							<tr>\n".
				"								<td".$this->widthLabel.">&nbsp;</td>\n".
				"								<td".$this->widthData.">&nbsp;</td>\n".
				"							</tr>\n".
				$from;
	}

	function closeForm()
	{
		return	"						</table>\n".
				"					</fieldset>\n".
				"				</form>\n".
				"			</td>\n".
				"		</tr>\n".
				"	</table>\n".
				"</center>\n";
	}

	function makeHidden($name, $id, $value="")
	{
		return	"<input type='hidden' name='$name' id='$id' value='$value'>\n";
	}

	function makeInput($name, $id, $label="", $value="", $options="")
	{
		return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='$id'><nobr>$label</nobr></label></td>\n".
				"	<td".$this->widthData."><input type='text' name='$name' id='$id' value='$value'$options></td>\n".
				"</tr>\n";
	}

	function makeMulti($name, $id, $label="", $champs)
	{
		$ret=	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='$id'><nobr>$label</nobr></label></td>\n".
				"	<td".$this->widthData.">";

		foreach($champs as $k => $det)
		{
			$id		= array_key_exists("id", $det)?$det["id"]:$det["name"];
			$options= array_key_exists("options", $det)?$det["options"]:"";
			$value	= array_key_exists("value", $det)?$det["value"]:"";
			switch($det["type"])
			{
				case "input":
					$ret .= "<input type='text' name='".$name."' id='".$id."' value='".$value."'".$options.">";
					break;

				case "combo":
					if	($value=="")
						$option="\n\t<option value=\"\" selected>---\n";
					else
						$option="\n\t<option value=\"\">---\n";
					foreach($det["values"] as $key => $libelle)
					{
						if	($value==$key)
							$option.="\t<option value=\"".$key."\" selected>".$libelle."\n";
						else
							$option.="\t<option value=\"".$key."\">".$libelle."\n";
					}
					$ret .= "<select name='".$name."' id='".$id."'".$options.">\n".$option."</select>\n";
					break;

				case "texte":
				default:
					$ret .= nl2br($value);
					break;
			}
		}
		$ret.=	"	</td>\n".
				"</tr>\n";
		return $ret;
	}

	function makePassword($name, $id, $label="", $value="", $options="")
	{
		return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='$id'>$label</label></td>\n".
				"	<td".$this->widthData."><input type='password' name='$name' id='$id' value='$value'$options></td>\n".
				"</tr>\n";
	}

	function makeFileInput($name, $id, $label="", $value="", $image=null, $options="")
	{
        $image = (isset($image)) ? '<br>' . $image : "";
        $value = (isset($value)) ? ' value="' . $value . '"' : '';
        return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='$id'>$label</label></td>\n".
				"	<td".$this->widthData."><input type='file' name='$name' id='$id' $options>".$image."</td>\n".
				"</tr>\n";
	}

	// D�but d'un Fieldset
	function openFieldset($label)
	{
		return	"<tr>\n".
				"	<td colspan=2>\n".
				"		<fieldset>\n".
				"			<legend>$label</legend>\n".
				"			<table cellspacing=0 cellpadding=0 border=0 width='100%'>\n";
	}

	// Fin d'un Fieldset
	function closeFieldset()
	{
		return	"			</table>\n".
				"		</fieldset>\n".
				"	</td>\n".
				"</tr>\n";
	}


	function makeRadio($name, $id, $label="", $value=NULL, $valeurs, $options="")
	{
		$lstRadio="";
		while (list ($valeur, $libelle) = each ($valeurs)) 
		{
			$check = (isset($value) && $valeur==$value)?" checked":"";
			$lstRadio .= "<input type='radio' name='".$name."' id='".$id."' value='".$valeur."'".$options.$check.">".$libelle."&nbsp;";
		}
		return	"<tr id='tr".$id."'>".
				"	<td".$this->widthLabel."><label for='".$id."'>".$label."</label></td>\n".
				"	<td".$this->widthData.">".$lstRadio."</td>\n".
				"</tr>\n";
	}


	function makeRadioEnum($name, $id, $label, $value, $table, $colonne, $addLigneVide, $db, $options="")
	{
        $aValuesLabels = $this->getListeValeursEnum($table, $colonne, $addLigneVide, $db);
        return	$this->makeRadio($name, $id, $label, $value, $aValuesLabels, $options);
	}


	// La requete est de type SELECT xx as ID, yyy as LIBELLE from zzz
	// $value contient soit juste une valeur, soit un tableau de valeurs.
	function makeCheckbox($name, $id, $label="", $value=NULL, $requete="", $options="")
	{
		$res=null;
		if (strlen($requete)>1)
		{
			$ret=$this->openFieldset($label);
			$lstCheck="";
			$i=0;
			sqlOpenCur($res, $requete);
			while($row=sqlFetchCur($res))
			{
				$check = "";
				$i++;
				if (isset($value) && is_array($value))
				{
					if ( array_key_exists($row->ID, $value) ) $check = " checked";
				}
				elseif (isset($value))
				{
					if ($row->ID == $value ) $check = " checked";
				}
 				$lstCheck.="<input type='checkbox' name='$name$i' id='$id$i' value='$row->ID'$check$options><label for='$id$i'>$row->LIBELLE</label><br>\n";

			}
			sqlCloseCur($res);
			$ret.=	"<tr id='tr".$id."'>\n".
					"	<td".$this->widthLabel.">&nbsp;</td>\n".
					"	<td".$this->widthData.">".$lstCheck."</td>\n".
					"</tr>\n";
			return $ret.$this->closeFieldset();
		}
		else
			return	"<tr id='tr".$id."'>\n".
					"	<td><label for='$id'>$label</label></td>\n".
					"	<td><input type='checkbox' name='$name' id='$id' value='$value'$options></td>\n".
					"</tr>\n";
	}


	function makeComboMultiple($name, $id, $label="", $values, $aTableau, $options="")
	{
		$option="";
		foreach($aTableau as $key => $libelle)
		{
			if	(is_array($values) && array_key_exists($key, $values))
				$option.="\t<option value=\"".$key."\" selected>".$libelle."\n";
			else
				$option.="\t<option value=\"".$key."\">".$libelle."\n";
		}
		return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='".$id."'>".$label."</label></td>\n".
				"	<td".$this->widthData."><select name='".$name."' id='".$id."' multiple size=5".$options.">".$option."</select></td>\n".
				"</tr>\n";
	}


	function makeCombo($name, $id, $label="", $value="", $aTableau, $options="")
	{
		if	($value=="")
			$option="\n\t<option value=\"\" selected>---\n";
		else
			$option="\n\t<option value=\"\">---\n";
		foreach($aTableau as $key => $libelle)
		{
			if	($value==$key)
				$option.="\t<option value=\"".$key."\" selected>".$libelle."\n";
			else
				$option.="\t<option value=\"".$key."\">".$libelle."\n";
		}
		return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='".$id."'>$label</label></td>\n".
				"	<td".$this->widthData."><select name='".$name."' id='".$id."'".$options.">$option</select></td>\n".
				"</tr>\n";
	}


	// La requete est de type SELECT xx as ID, yyy as LIBELLE from zzz
	function makeComboSQL($name, $id, $label="", $value="", $requete="", $db)
	{
		$res=null;
		$db->sqlOpenCur($res, $requete);
		while	($row=$db->sqlFetchCur($res))
		{
			$aTableau[$row->ID] = $row->LIBELLE;
		}
		$db->sqlCloseCur($res);
		return	$this->makeCombo($name, $id, $label, $value, $aTableau);
	}


	function makeComboEnum($name, $id, $label, $value, $table, $colonne, $addLigneVide, $db)
	{
        $aValuesLabels = $this->getListeValeursEnum($table, $colonne, $addLigneVide, $db);
		return	$this->makeCombo($name, $id, $label, $value, $aValuesLabels);
	}


	function makeTextarea($name, $id, $label="", $value="", $options="")
	{
		return	"<tr id='tr".$id."'>\n".
				"	<td".$this->widthLabel."><label for='".$id."'><nobr>".$label."</nobr></label></td>\n".
				"	<td".$this->widthData."><textarea name='".$name."' id='".$id."'".$options.">".$value."</textarea></td>\n".
				"</tr>\n";
	}


	function makeTexteRiche($name, $id, $value="", $options="")
	{
        return	$this->makeTextarea($name, $id, "Description", $value, $options);
	}


	function makeTexte($label, $texte)
	{
		return	"<tr>".
				"	<td".$this->widthLabel."><label>$label</label>&nbsp;</td>".
				"	<td class='fauxinput'".$this->widthData.">".nl2br($texte)."&nbsp;</td>".
				"</tr>\n";
	}


	function makeNote($texte)
	{
		return	"<tr><td colspan=2><span class='note'>".nl2br($texte)."</span></td></tr>\n";
	}


	function makeNoteObligatoire()
	{
		return	"<tr><td colspan=2 align='right'><span class='note'>Les champs suivis de (*) sont obligatoires.</span></td></tr>\n";
	}


	function makeMsgError($texte)
	{
		return "<tr><td colspan=2><span style=\"color:#ff0000;\"><b>ERREUR</b> : $texte</span></td></tr>\n";
	}


	function makeMsgWarning($texte)
	{
		return	"<tr><td colspan=2><span style=\"color:#D2691E;\">$texte</span></td></tr>\n";
	}


	function makeMsgInfo($texte)
	{
		return	"<tr><td colspan=2><span style=\"color:#008000;\">$texte</span></td></tr>\n";
	}


	function makeButton($value, $options=' class="btn btn-success"')
	{
        return	"<tr>\n".
                "	<td colspan=2>&nbsp;</td>\n".
                "</tr>\n".
                "<tr>\n".
                "	<td colspan=2 width='100%' align='center'>\n".
                "		<input type=\"submit\" value=\"".$value."\"".$options.">\n".
                "	</td>\n".
                "</tr>\n";
	}


    private function getListeValeursEnum($table, $colonne, $addLigneVide, $db)
    {
        $aValuesLabels = array();
        if	($addLigneVide==TRUE)
            $aValuesLabels[NULL]="";
        $resInfos='';
        $db->sqlOpenCur($resInfos, "SHOW FULL COLUMNS FROM $table");
        while ($rowInfos=$db->sqlFetchCur($resInfos))
        {
            if ($rowInfos->Field==$colonne)
            {
                $type = $rowInfos->Type;
                $type = substr($type, 5, strlen($type));		// virer 'enum(' au d�but
                $type = substr($type, 0, strlen($type)-1);		// virer ')' � la fin
                $aTmp = explode(",", $type );
                foreach($aTmp as $val)
                {
                    $za = substr($val, 1, strlen($val)-2);
                    $aValuesLabels[$za] = $za;
                }
                break;
            }
        }
        $db->sqlCloseCur($resInfos);
        return $aValuesLabels;
    }


    /*
     * ------------------------------------------------
     * --> Fonctions de transformation de date et heure
     * ------------------------------------------------
     */
 	
	static function textToTime($time=null)
	{
		if	(!isset($time))
		{
			$t=time();
			$time=strftime("%d", $t)."/".strftime("%m", $t)."/".strftime("%Y", $t);
		}
		$date=explode(" ", $time);
		list($j,$m,$a) = explode("/",$date[0]);
		list($h,$mn,$s) = explode(":",$date[1]);
		return mktime ( $h, $mn, $s, $m, $j, $a); 
	}

    static function textToDate($date)
    {
        return mktime ( 0, 0, 0, substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4));
    }

    static function timeToTextDate($time=null)
	{
		if	(!isset($time)) $time=time();
		return strftime("%d", $time)."/".strftime("%m", $time)."/".strftime("%Y", $time);
	}

    static function timeToTextDatetime($time=null)
	{
		if	(!isset($time)) $time=time();
		return strftime("%d", $time)."/".strftime("%m", $time)."/".strftime("%Y", $time)." ".strftime("%H", $time).":".strftime("%M", $time).":".strftime("%S", $time);
	}

    static function timeToTextTime($time=null)
	{
		if	(!isset($time)) $time=time();
		return strftime("%H", $time).":".strftime("%M", $time).":".strftime("%S", $time);
	}
}