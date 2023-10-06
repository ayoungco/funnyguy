<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Meta keywords
$metaKeywords = '<meta name="keywords" content="comics, webcomic, funny guy, adam young, steve kohler, stick-figures, stick comics, school comics, funny, hilarious, games, rpg" />';
$metaDesc = '<meta name="description" content="Stick-figure comics about Funny Guy, his friends, and their antics."/>';

// switches and flags
$activateArrowKeys = false;

define("ROOT",	 	"https://www.fgcomics.com/");
define("DB_ADDRESS",	"dock.core.index.one:33306");
define("DB_USER",	"http");
define("DB_PASS",	"78iKp89");
define("DB_SCHEMA",	"fgcomics");

function getPreviousComic($row) {
	// first check to make sure if it's the first comic
	if ($row['ComicNumber'] == 1) return false;
	$set = fgdb("
		SELECT COUNT( PageNumber ) AS NumberOfPages, MAX( PageNumber ) AS EndPage, MIN( PageNumber ) AS StartPage
		FROM Comics 
		WHERE ComicNumber='".$row['ComicNumber']."'");
	if ($set['NumberOfPages'] > 0) {
		if ($row['PageNumber'] > $set['StartPage']) {
			return ($row['ComicNumber'])."/".($row['PageNumber']-1)."/";
		} else {
			// check previous comic for pages, if it has them, return the highest page
			$set = fgdb("
			SELECT COUNT( PageNumber ) AS NumberOfPages, MAX( PageNumber ) AS EndPage, MIN( PageNumber ) AS StartPage
			FROM Comics 
			WHERE ComicNumber='".($row['ComicNumber']-1)."'");
			if ($set['NumberOfPages'] > 0) {
				return ($row['ComicNumber']-1)."/".$set['EndPage']."/";
			} else {
				return ($row['ComicNumber']-1)."/";
			}
		}
	} else {
		$set = fgdb("
			SELECT COUNT( PageNumber ) AS NumberOfPages, MAX( PageNumber ) AS EndPage, MIN( PageNumber ) AS StartPage
			FROM Comics 
			WHERE ComicNumber='".($row['ComicNumber']-1)."'");
		if ($set['NumberOfPages'] > 0) {
			return ($row['ComicNumber']-1)."/".$set['EndPage']."/";
		} else {
			return ($row['ComicNumber']-1)."/";
		}
	}
	#query db for comic number, get max page number
	
}

function getNextComic($row) {
	$set = fgdb("
		SELECT COUNT( PageNumber ) AS NumberOfPages, MAX( ComicNumber ) AS LatestComic, MAX( PageNumber ) AS EndPage, MIN( PageNumber ) AS StartPage
		FROM Comics 
		WHERE ComicNumber='".$row['ComicNumber']."'");
	$endComic = fgdb("SELECT MAX(ComicNumber) AS MAX FROM Comics");
	// make sure it's not the last page of the last comic, return false to disable if it is
		if (($row['ComicNumber'] == $endComic['MAX']) && ($row['PageNumber'] == $set['EndPage'])) return false;
	// Is the current comic multi-page?	
	if ($set['NumberOfPages'] > 1) {
		// IS multi
		// Is it the last page?
		if ($row['PageNumber'] == $set['EndPage']) {
			// IS the last page, increment COMIC
			return ($row['ComicNumber']+1)."/";
		} else {
			// NOT the last page, increment PAGE
			return ($row['ComicNumber'])."/".($row['PageNumber']+1)."/";
		}
	} else { 
		// IS NOT multi
		return ($row['ComicNumber']+1)."/";
	}	
}

function getLatestComic() {
	$info = fgdb("SELECT ComicNumber FROM Comics ORDER BY ComicNumber DESC LIMIT 1");
	return $info['ComicNumber']."/";
}

function fgdb($query, $doFetch = true) {
	$conn = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_SCHEMA);
	$result = $conn->query($query);
	$conn->close();
	if ($doFetch == true) {
		return $result->fetch_assoc();
	} else {
		return $result;
	}
}

function updateRSS() {
	// get the top 5 latest comics
	$result = fgdb("SELECT * FROM Comics ORDER BY ComicNumber DESC LIMIT 10", false);
	// open rss file and erase it with the parameter "w"
	$handle = fopen("../rss.xml", "w");
	// write opening tags
	$write = "<rss version=\"2.0\">
		<channel>
		<title>FGComics</title>
		<link>".$root."</link>
		<description>FGComics - Silly stick comics.</description>
		<language>en</language>";
	fwrite($handle, $write);
	// loop through the results and write them
	while($row = mysql_fetch_array($result)) {
		$comicPath = $root.$row['ComicNumber'].'/';
		$write = '<item><title>'.$row['Title'].'</title><link>'.$comicPath.'</link><description><a href="'.$comicPath.'"><img src="'.$root.'comics/'.$row['Filename'].'" /></a></description><pubDate>'.$row['DatePublished'].'</pubDate><guid>'.$root.$row['ComicNumber'].'/</guid></item>';
		fwrite($handle, $write);
	};
	// write the closing tags
	$write = "</channel></rss>";
	fwrite($handle, $write);
	fclose($handle); // close rss.xml
}
?>
