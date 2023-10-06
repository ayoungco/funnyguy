<?php
include("includes/include.php");
$comic = fgdb("SELECT ComicNumber FROM Comics WHERE SeriesID=1 ORDER BY RAND() LIMIT 1");
header("Location: ".ROOT.$comic['ComicNumber']."/");
// AND code<>3
?>
