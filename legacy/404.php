<? header("HTTP/1.0 404 Not Found"); 
include("includes/include.php");
$activateSocialBtns = false;

$title = "404! :(";

include("includes/template_header.php");
?>
<div class="indent" style="background-image:url('<?=$root?>img/nofun.jpg'); background-repeat:no-repeat; background-position:top right; min-height:600px; text-shadow: 5px 3px 4px rgba(255, 255, 255, 1);">

<h1>404 Error!</h1>
<p><?php var_dump($_SERVER['HTTP_REFERER']); ?></p> 
<a href="<?=$root?>random/"><h3>Care for a random comic?</h3></a>
<h3>Go back?</h3>

</div>
<?php include("includes/template_footer.php"); ?>