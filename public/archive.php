<?php
include("includes/include.php");

$activateSocialBtns = true;
$activateArrowKeys = false;

$title = "Comic Archive";

// begin building page

include("includes/template_header.php");
?>
<h1>Comic Archive</h1>
<p class="notice">I recommend a <a href="<?=ROOT?>random/">random comic</a> since we aren't doing weekly updates right now. Expect those when I have all of the old stuff up. :)</p>
<h2><a name="funnyguycomix"></a>The Funny Guy Comix</h2>
<p class="description">The Funny Guy Comix are our &quot;flagship&quot; line of comics. We're named after them, after all. They started in 2000, more than a decade ago, and they were written almost constantly for 5 years, until the 2005 hiatus. Most are full-page comics with about 30 squares in them on average. There were a few multi-page comics released, most notably <a href="<?=ROOT?>182/">175: An F.G. Christmas Carol</a> and <a href="<?=ROOT?>192/">192: Attack of the Drones.</a> All of the comics were arranged into "books" or collections of approximately 40 comics each with generally similar themes. Each one follows a loose storyline that continues from 1 to 320. Ctrl+F is your friend!</p>
</div>
<?php
// get latest book
$result = fgdb("SELECT * FROM Collections WHERE Series=1 ORDER BY Sequence DESC LIMIT 1");
$book = $result['Sequence'];
// print out all comic books in order
while ($book > 0) {
	// AND isFirstPage IS NOT FALSE 
	$comics = fgdb("SELECT * FROM Comics INNER JOIN Collections ON Comics.BookID=Collections.ID WHERE Collections.Series=1 AND Collections.Sequence=".$book." AND isFirstPage IS NOT FALSE ORDER BY ComicNumber DESC", false);
	$result = fgdb("SELECT * FROM Collections WHERE ID=".$book);
	// print title and background image
	echo '<div class="comicbook" style="background-image:url(\''.ROOT.'img/'.$result['ArchiveImage'].'\'); background-repeat:no-repeat;">
	<h3><a name="book'.$result['Sequence'].'"></a>Book '.$result['Sequence'].': '.$result['Title'].' ('.$result['RangeMin'].' to '.$result['RangeMax'].')</h3>
	<div><p>'.$result['Description'].'</p><div class="comiclinks">';
	// print each comic link
	while ($row = $comics->fetch_array()) {
		// if ($row['isFirstPage'] == true) echo "<b style=\"font-size:110%\">";
		echo '<a href="'.ROOT.$row['ComicNumber'].'/"><b>'.$row['ComicNumber'].':</b> '.$row['ComicTitle'].'</a>';
		// if ($row['isFirstPage'] == true) echo "</b>";
		echo "<br>";
	}
	echo '</div></div></div>';
	$book--;
}

?>
<div style="clear:both">

<?php include("includes/template_footer.php"); ?>
