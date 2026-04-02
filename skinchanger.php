<?php
/*******************************************
 * ----- Legal stuff -----
 * Name: skinchanger.php
 * Started: Wednesday, July 27, 2005
 * Copyright: (C) 2005 FGComics
 *
 * All unauthorized use of this code will 
 * be dealt with harshly.
 ******************************************/
 
/* To add new skins, just put a new option in 
the drop-down menu for it. Follow the format
of previous options. */

if (isset($_GET["skin_id"]))
{
    $skinpref = $_GET["skin_id"];
}
elseif (isset($_COOKIE['skinpref'])) 
{
	$skinpref = $_COOKIE['skinpref'];
}
else
{
	// Default style
	$skinpref = blue; 
}
// Set a cookie with the new style
setcookie("skinpref", $skinpref, time()+60*60*24*5); 
echo '<link rel="stylesheet" type="text/css" href="http://www.fgcomics.com/css/fgcomics_'.$skinpref.'.css">'."\n";
echo '<script language="javascript" src="http://www.fgcomics.com/jscript/fgcomics.js">'."\n".'<!--'."\n".'//-->'."\n".'</script>'."\n";
?>