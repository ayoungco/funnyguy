<?php
echo <<<EOT
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=0.6">
$metaKeywords 
$metaDesc
<title>$title - Funny Guy Comics</title>
<link href='https://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
EOT;
?>
<?php /* should arrow keys be activated? */ if($activateArrowKeys) { ?>
<script type="text/javascript">
document.onkeydown=function(e){
		switch(e.which){
		<?php if (!$first) { ?>
			case 37:
				// Key left.
				<?php echo 'window.location = "'.ROOT.$previousComic.'";'; ?>
				break;
		<?php } ?>
		<?php if (!$latest) { ?>
			case 39:
				// Key right.
				<?php echo 'window.location = "'.ROOT.$nextComic.'";'; ?>
				break;
		<?php } ?>
		}
	}
<?php } ?>
</script>
<link href="<?=ROOT?>nawful.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div id="header">
<div id="logo"></div>
<ul>
	<li><a href="<?=ROOT?>">Comics</a></li>
    <li><a href="<?=ROOT?>archive/">Archive</a></li>
    <?php /* <li><a href="<?=ROOT?>extras/">Extras</a></li> */ ?>
    <li><a href="<?=ROOT?>game/">FGRPG Game</a></li>
    <li><a href="<?=ROOT?>history/">History</a></li>
</ul>
</div>

<div id="page">
<div id="page_nest" class="content">
