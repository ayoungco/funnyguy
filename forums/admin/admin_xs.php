<?php

/***************************************************************************
 *                               admin_xs.php
 *                               ------------
 *   copyright            : (C) 2003 - 2005 CyberAlien
 *   support              : http://www.phpbbstyles.com
 *
 *   version              : 2.2.1
 *
 *   file revision        : 55
 *   project revision     : 68
 *   last modified        : 17 Apr 2005  23:15:23
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if(empty($setmodules))
{
	return;
}

define('IN_XS', true);
define('XS_ADMIN_OVERRIDE', true);
include_once('xs_include.' . $phpEx);
return;

?>