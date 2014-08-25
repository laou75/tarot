<?php
$form = new Formulaire();

$form->setValeurs($_POST);

$err="";
if (strlen($form->getValeur("nom"))=="")
    $err .= "Le champ 'Nom' est obligatoire !<br>";
if (strlen($form->getValeur("prenom"))=="")
    $err .= "Le champ 'Prénom' est obligatoire !<br>";
if ($err=="")
{
    if (count($_FILES)>0 && strlen($_FILES["portrait"]["name"])>0)
    {
        $fichier = $_FILES["portrait"]["name"];
        if (!move_uploaded_file($_FILES['portrait']['tmp_name'], $GLOBALS["Config"]["PATH"]["PORTRAIT"].$fichier))
            $err .= "ERREUR : imposssible de télécharger le fichier (UPLOAD) !";
        else
        {
            $form->setValeur("portrait", $fichier);
            $ext = strtolower(substr($fichier, -3));
            if ($ext == "jpg" || $ext == "gif" || $ext == "png")
            {
                Tools::genereVignette(	$fichier,
                                        $GLOBALS["Config"]["PATH"]["PORTRAIT"],
                                        $GLOBALS["Config"]["PATH"]["PORTRAIT"]."mini/",
                                        "100");
            }
        }
    }
}
