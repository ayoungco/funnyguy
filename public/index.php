<?php
// include("includes/functions.php");
require("includes/include.php");

$first = false;
$latest = false;

$bounds = fgdb("SELECT MAX(ComicNumber) as Last FROM Comics");
$bounds = $bounds['Last'];
$maxpage = fgdb("SELECT MAX(PageNumber) as MaxPage FROM Comics WHERE ComicNumber=".$bounds);
$maxpage = $maxpage['MaxPage'];

// Get specified comic
if (isset($_GET['number'])) {
	$number = $_GET['number'];
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$info = fgdb("SELECT * FROM Comics WHERE ComicNumber = '".$number."' AND PageNumber = '".$page."'");
	}
	else {
		$info = fgdb("SELECT * FROM Comics WHERE ComicNumber = '".$number."'");
	}
	// report if last or first
	if ($info['ComicNumber'] == 1) $first = true;
	elseif ( ( ($info['ComicNumber'] == $bounds) && is_null($maxpage) ) || ( ($info['ComicNumber'] == $bounds) && ($page == $maxpage) ) ) {
		$latest = true;
	}
} else {
	// get latest
	$info = fgdb("SELECT * FROM Comics ORDER BY ComicNumber DESC LIMIT 1");
	$latest = true;
}

#if query returns nothing
if (!isset($info)) {
	#flag for 404 and trigger logic that includes(404.php)
	die("404");
}

$activateSocialBtns = true;
$activateArrowKeys = true;

// pre generate neighbor comics
$previousComic = getPreviousComic($info);
$latestComic = getLatestComic($info);
$nextComic = getNextComic($info);

// comic url
$comicURL = ROOT."comics/".$info['Filename'];

$title = $info['ComicTitle'];

// construct links

// begin building page

// disable the previous button if it's the first comic.
if ($previousComic == false) { 
	$prev = '<li class="comicnav_prev_disable"><span>Previous</span></li>';
} else { 
	$prev = '<li class="comicnav_previous"><a href="'.ROOT.$previousComic.'"><span>Previous</span></a></li>';
}
// disable the next button if it's the last page of the last comic
if ($nextComic == false) { 
	$next = '<li class="comicnav_next_disable"><span>Next</span></li>';
} else { 
	$next = '<li class="comicnav_next"><a href="'.ROOT.$nextComic.'"><span>Next</span></a></li>';
}

$comicNav = $prev.'
<li class="comicnav_first"><a href="'.ROOT.'1/"><span>First</span></a></li> 
<li class="comicnav_random"><a href="'.ROOT.'random/"><span>Random</span></a></li> 
<li class="comicnav_latest"><a href="'.ROOT.$latestComic.'"><span>Last</span></a></li> 
'.$next;

include("includes/template_header.php");
?>
<ul class="comicnav">
	<?=$comicNav?>
</ul>
<div style="clear:left;"></div>

<div style="text-align:center; margin-top:15px;">
<!--<div id="comic">--><img id="comic" src="<?=$comicURL?>" width="<?=$info['ComicWidth']?>" height="<?=$info['ComicHeight']?>" /><!--</div>-->
</div>

<ul class="comicnav">
	<?=$comicNav?>
</ul>
<div style="clear:left;">
</div>
<?php include("includes/template_footer.php"); ?>
