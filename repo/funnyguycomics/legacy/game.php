<?php
include("includes/include.php");

$metaKeywords = '<meta name="keywords" content="video game, rpg maker, funny guy, adam young, steve kohler, stick-figures, stick games, funny games" />';
$metaDesc = '<meta name="description" content="The FGRPG is the official Funny Guy video game. Join Funny Guy, Junior, Repair Man, and more in the quest to deliver their world from Humorlessness. Download for free here!"/>';

$activateSocialBtns = false;

$title = "The Funny Guy RPG";

include("includes/template_header.php");
?>

<div class="content">

<h1>The Funny Guy RPG (FGRPG)</h1> 

<img src="<?=ROOT?>fgrpg/front_thumb.jpg" width="320" height="318" alt="FGRPG Front Cover" /><img src="<?=ROOT?>fgrpg/back_thumb.jpg" width="399" height="318" alt="FGRPG Back Cover" />

<p>You've heard us talkin' about it... here it is. Built on the RPG Maker 2003 engine for maximum novelty value and coolness, this is a game you won't want to miss. Even if you have no idea what an RPG is, I have no doubt you'll enjoy yourself. It pokes fun at classic RPGs like Final Fantasy,  and even other RPG Maker 2003 games. Help Funny Guy find his brothers and team up against the hordes of Humorless and their elite masters... the Evilies themselves! </p> 

<a class="button" href="<?=ROOT?>fgrpg/fgrpg-1.0.4-win.zip">Download for Windows<br /><span class="sub">23.6 MB .zip</span></a>

<h2>Game Controls</h2>
<p align="center">Well you can't play a game if you don't know the controls! So here they are, in all their glory:</p>
<hr>
<p align="center"><table bgcolor="#CCCCCC">
  <tr bgcolor="#DFDFFF">
    <td width="50%" align="center"><strong>Function</strong></td>
    <td width="50%" align="center"><strong>Key Combination</strong></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Confirm/Select</div></td>
    <td width="50%"><div align="center">Enter <strong>or</strong> Spacebar </div></td>
  </tr>  
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Move (Cursor, Funny Guy) </div></td>
    <td width="50%"><div align="center">Arrow keys (&larr;, &uarr;, &rarr;, &darr; )</div></td>
  </tr>  
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Call Main Menu (When on field)</div></td>
    <td width="50%"><div align="center">Escape</div></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Call Quest Menu </div></td>
    <td width="50%"><div align="center">Any top number key (1, 2, 3 , 4, etc.)</div></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Go back (When in menu) </div></td>
    <td width="50%"><div align="center">Escape</div></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Change display type</div></td>
    <td width="50%"><div align="center">F4 (Window/Fullscreen modes) </div></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Change resolution (Window mode ONLY) </div></td>
    <td width="50%"><div align="center">F5 (High-rez, Low-rez) </div></td>
  </tr>
  <tr bgcolor="#FFFFCA">
    <td width="50%"><div align="center">Relieve frustration </div></td>
    <td width="50%"><div align="center">Slam keyboard (Just kidding!) <img src="<?=ROOT?>images/grin.gif" align="absmiddle"></div></td>
  </tr>
</table>
<br>
<div class="legible">
<h2>Controls and Menu Navigation</h2>
<p>This mini-walkthrough will give you a jumpstart on the game. It tells you how to get through the first area (Gas Town), as well as explain how the RPG battle system works, and get you educated on the Main Menu and Quest Menu.</p>
<p><img class="floatleftimg" src="<?=ROOT?>fgrpg/images/titlescreen.gif" width="320" height="240">If you haven't done so already, start the FGRPG and select &quot;New Game&quot; from the Title Screen. To dispel any confusion later, the &quot;Main Menu&quot; and &quot;Title Screen&quot; are totally different, even though most games have the Main Menu and Title Screen as the same thing. After watching the <em>beautifully</em> peiced together intro, you find yourself on the upper floor of Funny Guy's house. </p>
<p>Before doing anything, you might want to get informed about the two menus you have at your disposal. Press the escape key to bring up the Main Menu. A blue menu immediately comes up with a handful of options: Item, Skill, Equip, Save, Status, Quit, a large window with quick reference to the 4 characters in your &quot;battle party&quot;,
  and a small window displaying your current money. Item is the option you choose to view and use your items, surprisingly. Skill displays the skills your Funny Guy has currently, and even lets you use healing moves out-of-battle (once you find and use their skill scrolls). Equip lets you equip weapons, shields, armor, and accessories to your FGs. Save is the option you use to well, what else? Save the game. It should be gray now, unless you are standing on a save point, at which point will be the same color as all the other options. Status gives you in-depth detail on the status of your Funny Guys. Most importantly, it will tell you what experience points your Funny Guys have, and how many they need to reach the next level. Quit lets you quit the game, with a confirmation too so you don't accidentally quit. To close the Main Menu, press the escape key once again. </p>
<p><img class="floatrightimg" src="<?=ROOT?>fgrpg/images/screen2.gif" width="320" height="239" hspace="10">Now time to learn about the Quest Menu. Press any of the number keys  to activate it. As with the Main Menu, a blue menu comes up in the middle of the screen, except your cursor has changed from a box to a sword. There are 4 options (Master Skills, Lineup, Cheats, and Exit)(3 if you exclude the &quot;Exit&quot; option, which isn't really that amazing), but you'll probably use these the most of all in your quest. </p>
<ul>
  <li>Master Skills: Sadly, only one real option is available at first when you play the game for the first time! That option is Master Skills, the utility that should be first on your mind whenever you aquire a Skill Scroll (Scroll of Fire, Scroll of Rage, etc.). When you select it, it brings you to a list of all the Funny Guys that have joined your quest. From there you can choose the Funny Guy you are going to use the Skill Scroll on. Once you select the FG, you are presented with two lists of empty circles (which will eventually be filled with icons that correspond to their assigned skill), and a choice of offensive or defensive moves. It takes a little guessing to see which scroll belongs to which group of circles, but you'll figure it out. Once you find the correct circle group, the system asks you if you want to use that scroll. If you select yes, a small animation plays, the circle fills in with an icon, and the FG displayed at the top is taught that skill. </li>
  <li>Lineup: An option that will become available later on in the game. It allows you to swap FGs in and out from your battle party (&quot;Party&quot; is different than &quot;Battle Party&quot;. Party is how many FGs are with you. Battle Party is the FGs that will fight in a battle (Maximum is 4 FGs). </li>
  <li>Cheats: Well, cheats for the game. <strong>This is where I get a little unsure about having cheats in the game. I know people will cheat (no pun intended) and not wait until the end of the game to get the password, by hacking it out of the executable or one of the map files. DO NOT DO IT. It makes me sad that people must use my cheats to play through the game and not enjoy it. Please be a good gamer and wait until the end of the game to get the cheats password and don't give it away to other people. Use the cheats on your second, third, etc. play through the game. </strong></li>
  <li>Exit: Self-explanatory. Kind of. It just closes the Quest Menu. Use the escape key to close the menu.
  </li>
</ul>
<h2>Intro Walkthrough</h2>
<img class="floatleftimg" src="<?=ROOT?>fgrpg/images/screen3.gif" width="320" height="240"><p>Now let's play this thing! Check the computer for new Strong Bad Email, and then go on downstairs. Nothing much is going on, except for a reality show on TV. Head outside and explore the town. You can talk to residents about the current &quot;wall&quot; situation, as well as discover secrets about Potty-P's (The only other stick figure in the town, I might add) free drinks. Down toward the bottom of the town is a green save point. These are a necessity if you don't plan to beat the game in one hour. To use them, step onto one. The save point tells you that you can save in the Main Menu, and then asks you if you want to CAMP. This is a neat little feature I put in that lets you fully heal your Battle Party on certain save points (ones that are close to Inns or another form of complete healing won't let you camp), provided that you have camping sets, which you can buy at every shop. To save, stand on the save point and activate the Main Menu, then choose the save option. The game supports 15 game slots. Now back to the town. The buildings in Gas Town are as follows: the store (upper left-hand corner, you may want to invest in some CAMPING SETS and Mixtures before heading out), Funny Guy's house (top-middle), Funny Guy's shed (top-right), Potty-P's Disco Club (bottom-right), and four small houses (bottom-left).</p>
<p> To get out of the town, you normally would have to talk to the little girl in Potty-P's to learn how to get into Potty-P's and FG's basement, by paying her about 30G. But since you've showed interest in my little help manual here, I'll save you a few bucks and tell you that you don't have to talk to the little girl to get into the basement; just go up and use the action key on either the large cupboard in FG's house, or the drawer next to the little girl in Potty-P's to get to the basement. Once in there, go up to the top of the basement and climb the vine up the wall and select the sign. FG gets an <em>Old Key. </em>Huzzah! Resurface and go to FG's shed, outside, and climb the vine to the top. Use the Old Key on the little door on top, and then jump inside. Did someone steal FG's stuff, or is it that empty in here? Either way, push the button on the wall to open a door, then go back down to the basement. Whoa! Big hole! Go through the Hummer-sized gap in the stone wall and... Surprise! You're out of the town! Head back into the maze-like Silent Creek Forest. </p>
<img class="floatleftimg" src="<?=ROOT?>fgrpg/images/screen4.gif" width="320" height="238"><p> Chances are you're going to get into a battle or two in your travels. So before I close this guide, let's take a look at the classic Role-Playing Game battle system! Battles take RPG newbies by surprise, basically shoving a bunch of things in their face that they know nothing about. Let's break everything down. When the battle starts, you see 2 menus and a scene. On the scene, the Humorless are usually on the left, with your FG Battle Party on the right, but that may change one or two times. The menu on the left lists the names of your enemies (in the event that there are more than four enemies, this menu can scroll down to other names). The larger menu lists the most important data about your Battle Party. Name, Condition (poisoned, asleep, dead, etc.), Health Points (as a number, which will turn yellow once that FG's health becomes critical), and the Time Bar, which, when full, makes a new menu appear (slightly obscuring the enemy name box) and allows you to take an action. Act quickly, you don't have hours to decide what you want to do, so plan your moves while waiting for your Time Bar to fill up. Don't be too frantic; the battle will pause all actions once you select an option, so you can think. The escape key lets you go back through your menus, in case you change you mind about, say, using an item instead of attacking. Now let's take a look at those options! Fight lets your Funny Guy use their equipped weapon. Once you select Fight, a small sword cursor will appear on the screen, allowing you to use the enemy name box (and the scene, for a more visual guide) to choose your target. Skill (or GunTech, in Target Funny Guy's case) lets you pick a skill you taught to that FG through the Quest Menu. Neat, huh? After choosing the skill, you can choose your target as with the Fight command (remember, you can always use Escape to go back in case you change your mind). But be careful. All skills use Magic Points. A number is displayed next to the skill name to show its MP use. Use Magic Mixes to replenish MP. Defend has two uses. One is using it to defend an FG, for whatever reason, doubling their defense for a turn. Use number two is like a &quot;no action&quot; option. If you don't need that FG to do anything, you might as well make him  defend himself. Their battle sprite will change to a &quot;defending&quot; animation to symbolize that you chose the &quot;Defend&quot; option. Item lets you use items. Nothing too complicated, except once you choose an item you must cycle through your FGs to pick which one gets the item. Run is the last option, for you cowards. You can attempt to end the battle immediately, by running away, but it doesn't always work. Boss battles have this option disable to prevent you from wasting a turn trying.
<p>I hope you enjoyed my guide! Good luck bashing hordes of Humorless into bloody pulps! If you need help further on in the game, go to the <a href="http://www.fgcomics.com/forums/index.php">FGComics Forums</a> and post a topic on your problem in the &quot;Games&quot; forum.</p>
</p>
</p>

<h2>Frequently Asked Questions</h2>
<p>For those of you who don't know what an FAQ is, here's a definition for you... <i>FAQ: A list of frequently asked questions and their answers about a given subject. </i>With that said, let's move on!</p>
<h4>How do you start the game?</h4>
<p>You start the FGRPG by clicking on either of the two shortcuts generated during the installation. One is in the Start Menu, under Programs &rarr; FGComics &rarr; The Funny Guy RPG. The second is on your computer's desktop. If you wish to start the RPG manually (for whatever reason), go to {HARD DRIVE} &rarr; Program Files &rarr; FGComics &rarr; FGRPG v.1.0.4 &rarr; RPG_RT.exe.</p>
<h4>The FGRPG isn't displaying correctly on my monitor (edges are cut off, etc.)!</h4>
<p>The FGRPG was tested in a screen resolution of 1024x768. All three modes of display work fine in that resolution. If your monitor has a different resolution (1280x1024, 800x600, or (God forbid) 640x480), you may want to try changing the display mode.</p>
<h4>I'm stuck at [place in game]!</h4>
<p>Before anything else, I just want to say don't dog me with emails that say something like "Help me!", etc (you will get an email back with a link to the forums). Go to the <a href="http://www.fgcomics.com/forums/index.php">FGComics Forums</a> and post a topic with your problem. You should recieve a reply from someone other than me within 24 hours. If ABSOLUTELY no one replies to your topic for more than 4 days, you may Private Message (PM) me (<a href="http://www.fgcomics.com/forums/profile.php?mode=viewprofile&u=2">Elemental</a>) on the forums.</p>
<h4>I found a glitch!</h4>
<p>Report it online at the official FGRPG glitch topic in the <a href="http://www.fgcomics.com/forums/index.php">FGComics Forums.</a> Once I feel like doing it, I'll fix the glitch you found (if I find it too, and if it's fixable) and release a new FGRPG version (I use the versioning format x.y.z, which is the Linux kernel versioning system. Letter X is for huge, complete overhauls [The FGRPG 2 would be FGRPG 2.0.0], y is for major updates, and z is for small glitch fixes [the next glitch fix for this version of the FGRPG would be version 1.0.5]) for users to download and replace the old version of their FGRPG.</p>
<h4>When running the game, everything acts laggy.</h4>
<p>That is because the program is running a lot of events at one time. Try closing other programs and switching to full-screen mode (F4). If that doesn't work, your computer probably doesn't have enough memory (RAM) to support games. Try running the FGRPG on another computer or buying more RAM.</p>
</div>
</div>
<?php include("includes/template_footer.php"); ?>
