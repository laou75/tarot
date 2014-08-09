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

    function openForm($titre="", $action=null, $enctype="application/x-www-form-urlencoded")
    {
        if (!isset($action) || strlen($action)<1)
            $action = (array_key_exists('REQUEST_URI', $_SERVER)) ? 'action="' . $_SERVER['REQUEST_URI'] . '"' : 'action="' . $_SERVER['PHP_SELF'] . '"';
        else
            $action = 'action="' . $action . '"';

        if	(count($_POST)>0 && array_key_exists('from', $_POST))
            $from = $this->makeHidden('from', 'from', $_POST['from']);
        else
            $from = (array_key_exists('HTTP_REFERER', $_SERVER)) ? $this->makeHidden('from', 'from', $_SERVER['HTTP_REFERER']) : '';

        return sprintf( '<form %s method="post" enctype="%s" class="form-horizontal" role="form">%s'.PHP_EOL,
                        $action, $enctype, $from);
    }

    function closeForm()
    {
        return	'</form>'.PHP_EOL;
    }

    function makeHidden($name, $id, $value="")
    {
        return	'<input type="hidden" name="'.$name.'" id="'.$id.'" value="'.$value.'"/>'.PHP_EOL;
    }

    function makeLabel($id, $label='')
    {
        return  (!empty($label))
                ? sprintf('<label for="%s" class="col-sm-3 control-label">%s</label>', $id, $label)
                : '';
    }

    function makeInput($name, $id, $label='', $value='', $options='', $type='text', $placeholder='', $helpText='')
    {
        $helpText = !empty($helpText) ? '<span class="help-block">'.$helpText.'</span>' : '';
        $classInput = ($type=='file') ? '' : 'form-control';
        return sprintf('<div class="form-group">'.PHP_EOL.
                       '    %s'.PHP_EOL.
                       '    <div class="col-sm-9">'.PHP_EOL.
                       '        <input type="%s" class="%s" id="%s" name="%s" placeholder="%s" value="%s" %s/>'.PHP_EOL.
                       '        %s'.PHP_EOL.
                       '    </div>'.PHP_EOL.
                       '</div>',
                       $this->makeLabel($id, $label), $type, $classInput, $id, $name, $placeholder, $value, $options, $helpText);
    }

	function makeMulti($name, $id, $label='', $champs, $helpText='')
	{
        $ret=   sprintf('<div class="form-group">'.PHP_EOL.
                        '   %s'.PHP_EOL.
                        '   <div class="col-sm-9">'.PHP_EOL,
                        $this->makeLabel($id, $label));

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
							$option.="\t<option value=\"".$key."\" selected/>".$libelle."\n";
						else
							$option.="\t<option value=\"".$key."\"/>".$libelle."\n";
					}
					$ret .= "<select name='".$name."' id='".$id."'".$options.">\n".$option."</select>\n";
					break;

				case "texte":
				default:
					$ret .= nl2br($value);
					break;
			}
		}

        $ret.=  sprintf('       %s'.PHP_EOL.
                        '   </div>'.PHP_EOL.
                        '</div>',
                        $helpText);

		return $ret;
	}

    function makeInputDate($name, $id, $label='', $value='', $options='', $placeholder='', $helpText='')
    {
        $helpText = !empty($helpText) ? '<span class="help-block">'.$helpText.'</span>' : '';


        return sprintf('<div class="form-group">'.PHP_EOL.
            '    %s'.PHP_EOL.
            '    <div class="col-sm-9">'.PHP_EOL.
            '        <input type="text" class="datepicker" id="%s" name="%s" placeholder="%s" size="8" value="%s" %s/><span class="glyphicon glyphicon-calendar"></span>'.PHP_EOL.
            '        %s'.PHP_EOL.
            '    </div>'.PHP_EOL.
            '</div>',
            $this->makeLabel($id, $label), $id, $name, $placeholder, $value, $options, $helpText);
    }

    function makePassword($name, $id, $label='', $value='', $options='', $placeholder='', $helpText='')
    {
        return $this->makeInput($name, $id, $label, $value, $options, 'password', $placeholder, $helpText);
    }

	function makeFileInput($name, $id, $label='', $value='', $image=null, $options='', $placeholder='')
	{
        return $this->makeInput($name, $id, $label, $value, $options, 'file', $placeholder, $image);
	}

	// D�but d'un Fieldset
	function openFieldset($label)
	{
		return	'<fieldset>'.PHP_EOL.
				'   <legend>'.$label.'</legend>'.PHP_EOL;
	}

	// Fin d'un Fieldset
	function closeFieldset()
	{
		return	'</fieldset>'.PHP_EOL;
	}


	function makeRadio($name, $id, $label='', $value=NULL, $valeurs, $options='', $placeholder='', $helpText='')
	{
		$lstRadio="";
        $classInput = 'form-control';
		while (list ($valeur, $libelle) = each ($valeurs)) 
		{
			$check = (isset($value) && $valeur==$value)?" checked":"";
			$lstRadio .= "<input type='radio' name='".$name."' id='".$id."' value='".$valeur."'".$options.$check."/>".$libelle."&nbsp;";
		}

        return sprintf('<div class="form-group">'.PHP_EOL.
            '    %s'.PHP_EOL.
            '    <div class="col-sm-9">'.PHP_EOL.
            '        %s'.PHP_EOL.
            '        %s'.PHP_EOL.
            '    </div>'.PHP_EOL.
            '</div>',
            $this->makeLabel($id, $label), $lstRadio, $helpText);
	}


	function makeRadioEnum($name, $id, $label, $value, $table, $colonne, $addLigneVide, $db, $options='', $placeholder='', $helpText='')
	{
        $aValuesLabels = $this->getListeValeursEnum($table, $colonne, $addLigneVide, $db);
        return	$this->makeRadio($name, $id, $label, $value, $aValuesLabels, $options, $placeholder, $helpText);
	}


	// La requete est de type SELECT xx as ID, yyy as LIBELLE from zzz
	// $value contient soit juste une valeur, soit un tableau de valeurs.
	function makeCheckbox($name, $id, $label='', $value=NULL, $requete='', $options='')
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
 				$lstCheck.="<input type='checkbox' name='$name$i' id='$id$i' value='$row->ID'$check$options/><label for='$id$i'>$row->LIBELLE</label><br>\n";

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
					"	<td><input type='checkbox' name='$name' id='$id' value='$value'$options/></td>\n".
					"</tr>\n";
	}


	function makeComboMultiple($name, $id, $label='', $values, $aTableau, $options='', $placeholder='', $helpText='')
	{
		$option="";
		foreach($aTableau as $key => $libelle)
		{
			if	(is_array($values) && array_key_exists($key, $values))
				$option.="\t<option value=\"".$key."\" selected>".$libelle."\n";
			else
				$option.="\t<option value=\"".$key."\">".$libelle."\n";
		}

        $classInput = '';//'form-control';

        return sprintf('<div class="form-group">'.PHP_EOL.
            '    %s'.PHP_EOL.
            '    <div class="col-sm-9">'.PHP_EOL.
            '        <select class="%s" name="%s" id="%s" multiple size="%s" placeholder="%s"%s/>%s</select>'.PHP_EOL.
            '        %s'.PHP_EOL.
            '    </div>'.PHP_EOL.
            '</div>',
            $this->makeLabel($id, $label), $classInput, $name, $id, count($aTableau), $placeholder, $options, $option, $helpText);
	}


	function makeCombo($name, $id, $label='', $value='', $aTableau, $options='', $placeholder='', $helpText='')
	{
		if	($value=="")
			$option="\n\t<option value=\"\" selected/>---\n";
		else
			$option="\n\t<option value=\"\"/>---\n";
		foreach($aTableau as $key => $libelle)
		{
			if	($value==$key)
				$option.="\t<option value=\"".$key."\" selected/>".$libelle."\n";
			else
				$option.="\t<option value=\"".$key."\"/>".$libelle."\n";
		}

        $helpText = !empty($helpText) ? '<span class="help-block">'.$helpText.'</span>' : '';
        $classInput = '';//'form-control';
        return sprintf( '<div class="form-group">'.PHP_EOL.
                        '     %s'.PHP_EOL.
                        '     <div class="col-sm-9">'.PHP_EOL.
                        '         <select class="%s" id="%s" name="%s" placeholder="%s" value=""%s" %s>%s</select>'.PHP_EOL.
                        '        %s'.PHP_EOL.
                        '     </div>'.PHP_EOL.
                        '   </div>',
                        $this->makeLabel($id, $label), $classInput, $id, $name, $placeholder, $value, $options, $option, $helpText);
	}


	// La requete est de type SELECT xx as ID, yyy as LIBELLE from zzz
	function makeComboSQL($name, $id, $label='', $value='', $requete='', $db)
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


	function makeTextarea($name, $id, $label='', $value='', $options='', $placeholder='', $helpText='')
	{
        $classInput = '';//'form-control';

        return sprintf('<div class="form-group">'.PHP_EOL.
            '    %s'.PHP_EOL.
            '    <div class="col-sm-9">'.PHP_EOL.
            '        <textarea class="%s" name="%s" id="%s" placeholder="%s"%s>%s</textarea>'.PHP_EOL.
            '        %s'.PHP_EOL.
            '    </div>'.PHP_EOL.
            '</div>',
            $this->makeLabel($id, $label), $classInput, $name, $id, $placeholder, $options, $value, $helpText);
	}


	function makeTexteRiche($name, $id, $label="", $value="", $options=" class=\"textarea\" rows=\"15\" cols=\"50\"")
	{
        return	$this->makeTextarea($name, $id, $label, $value, $options);
	}


	function makeTexte($label='', $texte='')
	{
        return sprintf('<div class="form-group">'.PHP_EOL.
                       '    %s'.PHP_EOL.
                       '    <div class="col-sm-9"><div class="well">%s</div></div>'.PHP_EOL.
                       '</div>',
                        $this->makeLabel(0, $label), nl2br($texte));
	}


	function makeNote($texte)
	{
        return sprintf('<div class="form-group">'.PHP_EOL.
                       '    <div class="col-sm-3"></div>'.PHP_EOL.
                       '    <div class="col-sm-9"><span class="help-block">%s</span></div>'.PHP_EOL.
                       '</div>',
                       $texte);
	}

    function makeMsgError($texte)
    {
        return $this->makeNote('<span class="text-danger"><strong>ERREUR</strong> :<br/>'.$texte.'</span>');
    }

    function makeMsgWarning($texte)
    {
        return $this->makeNote('<span class="text-warning">'.$texte.'</span>');
    }

    function makeMsgInfo($texte)
    {
        return $this->makeNote('<span class="text-info">'.$texte.'</span>');
    }

	function makeNoteObligatoire()
	{
        return $this->makeMsgWarning('Les champs suivis de (*) sont obligatoires.');
	}


	function makeButton($value, $options=' class="btn btn-success"')
	{
        return sprintf( '<div class="form-group">'.PHP_EOL.
                        '   <div class="col-sm-3"></div>'.PHP_EOL.
                        '   <div class="col-sm-9"><input type="submit" value="%s"%s/></div>'.PHP_EOL.
                        '</div>',
                        $value, $options);
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