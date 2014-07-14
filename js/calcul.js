//var der=0;
var nbErreur=0;

function controleFormulaire()
{
	d = document.forms[0];

	//	Controler la selection des joueurs
	if (d.id_preneur.selectedIndex==0)
	{
		alert ("Renseigner le preneur !");
		return false;
	}
	if (d.id_second.selectedIndex==0 && d.def4.selectedIndex==0)
	{
		alert ("Renseigner l'appele ou le quatrieme defenseur !");
		return false;
	}
	if (d.def1.selectedIndex==0)
	{
		alert ("Renseigner le premier defenseur !");
		return false;
	}
	if (d.def2.selectedIndex==0)
	{
		alert ("Renseigner le second defenseur !");
		return false;
	}
	if (d.def3.selectedIndex==0)
	{
		alert ("Renseigner le troisieme defenseur !");
		return false;
	}

	//	Controler la selection de l'annonce
	ok=false;
	for (var i in d.annonce) 
	{
		if (d.annonce[i].checked) ok=true;
	}
	if (ok==false)
	{
		alert ("Renseigner l'annonce !");
		return false;
	}

//	alert ("points : "+d.points.value);

	//	Controler le score effectu?
	if (!d.points.value || d.points.value=="")
	{
		alert ("Renseigner les points !");
		return false;
	}

	return true;
}

function checkSelectJoueur(id_J)
{
	d = document.forms[0];
	valeur=document.getElementById(id_J).value;
	if (valeur=="") return;
	if (id_J == "id_preneur")
	{
		if	(valeur == d.id_second.value ) d.id_second.selectedIndex=0;
		if	(valeur == d.def1.value ) d.def1.selectedIndex=0;
		if	(valeur == d.def2.value ) d.def2.selectedIndex=0;
		if	(valeur == d.def3.value ) d.def3.selectedIndex=0;
		if	(valeur == d.def4.value ) d.def4.selectedIndex=0;
	}
	else if (id_J == "id_second")
	{
		if	(valeur == d.id_preneur.value ) d.id_preneur.selectedIndex=0;
		if	(valeur == d.def1.value ) d.def1.selectedIndex=0;
		if	(valeur == d.def2.value ) d.def2.selectedIndex=0;
		if	(valeur == d.def3.value ) d.def3.selectedIndex=0;
		if	(valeur == d.def4.value ) d.def4.selectedIndex=0;
	}
	else if (id_J == "def1")
	{
		if	(valeur == d.id_preneur.value ) d.id_preneur.selectedIndex=0;
		if	(valeur == d.id_second.value ) d.id_second.selectedIndex=0;
		if	(valeur == d.def2.value ) d.def2.selectedIndex=0;
		if	(valeur == d.def3.value ) d.def3.selectedIndex=0;
		if	(valeur == d.def4.value ) d.def4.selectedIndex=0;
	}
	else if (id_J == "def2")
	{
		if	(valeur == d.id_preneur.value ) d.id_preneur.selectedIndex=0;
		if	(valeur == d.id_second.value ) d.id_second.selectedIndex=0;
		if	(valeur == d.def1.value ) d.def1.selectedIndex=0;
		if	(valeur == d.def3.value ) d.def3.selectedIndex=0;
		if	(valeur == d.def4.value ) d.def4.selectedIndex=0;
	}
	else if (id_J == "def3")
	{
		if	(valeur == d.id_preneur.value ) d.id_preneur.selectedIndex=0;
		if	(valeur == d.id_second.value ) d.id_second.selectedIndex=0;
		if	(valeur == d.def1.value ) d.def1.selectedIndex=0;
		if	(valeur == d.def2.value ) d.def2.selectedIndex=0;
		if	(valeur == d.def4.value ) d.def4.selectedIndex=0;
	}
	else if (id_J == "def4")
	{
		if	(valeur == d.id_preneur.value ) d.id_preneur.selectedIndex=0;
		if	(valeur == d.id_second.value ) d.id_second.selectedIndex=0;
		if	(valeur == d.def1.value ) d.def1.selectedIndex=0;
		if	(valeur == d.def2.value ) d.def2.selectedIndex=0;
		if	(valeur == d.def3.value ) d.def3.selectedIndex=0;
	}
}

function getValRadio(champ)
{
	d = document.forms[0];
	for (var i = 0; i < champ.length; i++) 
	{
		if (champ[i].checked)
			return i;
	}
	return 0;

}

function testeRadio(champ, nom)
{
    if(typeof(champ)  === "undefined")
    {
        $("label[for="+nom+"]").addClass("red");
        nbErreur++;
    }
    else
    {
        $("label[for="+nom+"]").removeClass("red");
    }
}

function testeInput(nom)
{
    if($("#"+nom).val()  == "")
    {
        $("label[for="+nom+"]").addClass("red");
        nbErreur++;
    }
    else
    {
        $("label[for="+nom+"]").removeClass("red");
    }
}

function calculePoints()
{
	/*
	Pour gagner son contrat, le Preneur doit r?aliser un nombre de points fonction du nombre de Bouts qu?il poss?de (voir 3.But du Jeu).
	Si le compte des points contenus dans les lev?es du Preneur est inf?rieur ? son contrat, le Preneur chute celui-ci. 
	Tout contrat vaut 25 points auquels on ajoute les points de gain ou de perte. 
	Le nombre obtenu est multipli? par 2 pour une Garde, par 4 pour une Garde Sans ou par 6 pour une Garde Contre. 
	Ce nombre est soustrait (ou ajout? en cas de chute) ? chacun des 3 D?fenseurs, et ajout? 3 fois (ou soustrait 3 fois en cas de chute) au Preneur.
	Exemple: 
	le Preneur gagne une Garde avec 43 points, 2 Bouts et le Petit au Bout. 
	Son gain est de 43-41=2 points; (25 + 2 + 10 pour le Petit au Bout) x 2 pour la Garde = 74 points; 
	r?sultat -74 points pour les 3 D?fenseurs et 74 x 3 = 222 points pour le Preneur.
	*/
	var nbBouts=0;
    var ptFait=0;
	var ptContrat=0;
	var ptChelem=0;
	var ptPrime=0;
	var ANN;
	var multiplie;
	var sens;
	var ptBase;
	var	petitAuBout;
	var typePoignee;
    var id_preneur;

    nbErreur=0;

    id_preneur = $( "#id_preneur option:selected" ).text();
    testeRadio(id_preneur, "id_preneur");

    ANN = $('input[type=radio][name=annonce]:checked').attr('value');
    testeRadio(ANN, "annonce");

    nbBouts = $('input[type=radio][name=nombre_bouts]:checked').attr('value');
    testeRadio(nbBouts, "nombre_bouts");

    ptFait = $("#points").val();
    testeInput("points");

    typePoignee = $('input[type=radio][name=poignee]:checked').attr('value');
    testeRadio(typePoignee, "poignee");

    petitAuBout = $('input[type=radio][name=petitaubout]:checked').attr('value');
    testeRadio(petitAuBout, "petitaubout");

    if(nbErreur>0)
    {
        return false;
    }

    if (nbBouts==0) ptAFAIRE=56;
	else if (nbBouts==1) ptAFAIRE=51;
	else if (nbBouts==2) ptAFAIRE=41;
	else if (nbBouts==3) ptAFAIRE=36;
    else return false;

    if (ANN=="Petite") multiplie=1;
	else if (ANN=="Pousse") multiplie=1;
	else if (ANN=="Garde") multiplie=2;
	else if (ANN=="Garde sans le Chien") multiplie=4;
    else if (ANN=="Garde contre le Chien") multiplie=6;
    else return false;

	sens = (ptFait>=ptAFAIRE) ? +1 : -1;

    ptBase = (petitAuBout==1) ? 35 : 25;
    ptContrat = (ptBase + Math.abs(ptAFAIRE - ptFait)) * multiplie * sens;

	/*
	la Poign?e. Le joueur poss?dant une Poign?e (10, 13 ou 15 Atouts) peut s?il le d?sire l?annoncer et l?exposer avant de jouer sa 1?re carte.
	Simple Poign?e (10 Atouts): prime de 20 points.
	Double Poign?e (13 Atouts): prime de 30 points.
	Triple Poign?e (15 Atouts): prime de 40 points.
	Ces primes ne sont pas multipliables (voir Score) et sont acquises au camp vainqueur de la donne. L?excuse dans une poign?e implique que le joueur n?a pas d?autre Atout.
	*/
    if (typePoignee=="simple") ptPrime+=20;
	else if (typePoignee=="double") ptPrime+=30;
	else if (typePoignee=="triple") ptPrime+=40;

/*
Le Chelem consiste ? remporter les 18 lev?es de la donne?

Le Chelem peut ?tre demand? en plus du contrat; une prime suppl?mentaire non multipliable est ajout?e au r?sultat du contrat:
? Chelem annonc? et r?alis?: prime de 400 points.
? Chelem non annonc? mais r?alis?: prime de 200 points.
? Chelem annonc? mais non r?alis?: amende de 200 points.

L?annonce peut ?tre faite apr?s l?Ecart; l?annonceur d?un Chelem b?n?ficie alors de l?entame. 
Si le joueur tentant le Chelem poss?de l?Excuse, celle-ci peut ?tre jou?e en carte ma?tresse au dernier pli si tous les autres plis ont ?t? acquis; 
dans ce cas, le Petit sera consid?r? comme ?tant au bout ? l?avant dernier pli.
*/
		
	//d.total.value = ptContrat + ptChelem + ptPrime;
    $("#total").val( ptContrat + ptChelem + ptPrime );
}

function change_chelem()
{
	d = document.forms[0];
//	alert(d.chelem.value);
	if	(d.chelem[0].checked)
	{
		document.getElementById("trchelem_annonce").style.display="none";
		for (var i = 0; i < d.chelem_annonce.length; i++) 
		{
			d.chelem_annonce[i].checked=false;
		}
		//d.chelem_annonce.value=0;
		document.getElementById("trchelem_reussi").style.display="none";
		for (var i = 0; i < d.chelem_reussi.length; i++) 
		{
			d.chelem_reussi[i].checked=false;
		}
//		d.chelem_reussi.value=0;
	}
	else
	{
		document.getElementById("trchelem_annonce").style.display="block";
		document.getElementById("trchelem_reussi").style.display="block";
	}
	change_chelem_annonce();
	change_chelem_reussi();
	calculePoints();
}

function change_chelem_annonce()
{
	calculePoints();
}

function change_chelem_reussi()
{
	calculePoints();
}