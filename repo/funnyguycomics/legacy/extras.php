<?php
include("includes/include.php");
$activateSocialBtns = false;

$title = "Extra Stuff - FGComics";

include("includes/template_header.php");
?>

<h1>Extras &amp; Stuff</h1> 
  <h2>Characters!</h2> 

<?php
$characters = fgdb("SELECT * FROM Characters WHERE SeriesID=1");
foreach($characters as $toon) {
	var_dump($toon);
}
?>
  
  <p>You've heard us talkin' about it... here it is. Built on the RPG Maker 2003 engine for maximum novelty value and coolness, this is a game you won't want to miss. Even if you have no idea what an RPG is, I have no doubt you'll enjoy yourself. It pokes fun at classic RPGs like Final Fantasy, Rudora no Hihou, and even other RPG Maker 2003 games. Help Funny Guy find his brothers and team up against the hordes of Humorless and their elite team of benefactors... the Evilies themselves! <a href="stuff/fgrpg_v1.0.4_xp.zip">Download .zip for Windows XP (includes installer and manual)</a></p>

<?php include("includes/template_footer.php"); ?>
