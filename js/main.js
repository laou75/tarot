var x=0; var y=0;

function mouseMove (evt)
{
	if (document.layers)
	{
		x=evt.x;
		y=evt.y;
	}
	if (document.all)
	{
		x=event.clientX;
		y=event.clientY;
	}
	else
	{
		if (document.getElementById)
		{
			x=evt.clientX;
			y=evt.clientY;
		}
	}
//	window.status = "X="+x + '; Y=' + y;
}

function openPortrait(portrait, label)
{
//	html = "<table><tr><td class='border' align='center'>"+label+"</td></tr><tr><td class='border' align='center'><img src='images/portraits/mini/"+portrait+"'></td></tr></table>";
//	html = "<table><tr><td class='border' align='center'><img src='images/portraits/mini/"+portrait+"'></td><td class='border' align='center'>"+label+"</td></tr></table>";
	html = "<table><tr><td class='border' align='center'><img src='images/portraits/mini/"+portrait+"'></td></tr><tr><td class='border' align='center'>"+label+"</td></tr></table>";
				
	openPopup(html);
}

function closePortrait()
{
	closePopup();
}

function openPopup(html)
{
	var stmp=new String();
	var stmpNew=new String();
	stmp = html;
	re = /#QUOT#/gi;
	stmpNew = stmp.replace(re, "'");

	stmp = stmpNew;
	re = /#DBLQUOT#/gi;
	stmpNew = stmp.replace(re, '"');

//	prompt("zaza",stmpNew);
	DivImg = document.getElementById("pop-up");
	DivImg.innerHTML=stmpNew;
	DivImg.style.left=x+10;
	DivImg.style.top=y+10;
	DivImg.style.display="block";
}

function closePopup()
{
	DivImg = document.getElementById("pop-up");
	DivImg.style.display="none";
}

if	(document.layers)
	document.captureEvents(Event.MOUSEMOVE);
if	(document.layers || document.all)
	document.onmousemove = mouseMove;
if	(document.addEventListener)
	document.addEventListener('mousemove', mouseMove, true);