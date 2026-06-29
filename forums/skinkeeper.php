<?php
/*******************************************
 * ----- Legal stuff -----
 * Name: skinkeeper.php
 * Started: Sunday, July 31, 2005
 * Copyright: (C) 2005 FGComics
 ******************************************/
 
/* This file is for pages that don't have a
drop-down menu to change skins. */

if (isset($_COOKIE['skinpref'])) 
{
    $skinpref = $_COOKIE['skinpref'];
}
else
{
	// Default style
	$skinpref = blue; 
}
// Refresh the cookie; the visitor has returned.
setcookie("skinpref", $skinpref, time()+60*60*24*5); 
echo '<link rel="stylesheet" type="text/css" href="http://www.fgcomics.com/css/fgcomics_'.$skinpref.'.css">'."\n";
echo '<script language="javascript" src="http://www.fgcomics.com/jscript/fgcomics.js">'."\n".'<!--'."\n".'//-->'."\n".'</script>'."\n";
?>