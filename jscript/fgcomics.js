<!-- BEGIN switch_enable_pm_popup -->
if ( {PRIVATE_MESSAGE_NEW_FLAG} )
{
	window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
}
<!-- END switch_enable_pm_popup -->

function MM_jumpMenu(targ,selObj,restore)
{ 
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function skinSubmit(targ,selObj,restore)
{ 
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function delayer()
{
	alert("DO NOT modify and redistribute this package in your own name without permission!")
	document.location = "http://www.fgcomics.com/files/fgrpg_v.1.0.2.zip"
}

document.write("It worked, fool!")