<?php

/***************************************************************************
 *                               admin_xs.php
 *                               ------------
 *   copyright            : (C) 2003 - 2005 CyberAlien
 *   support              : http://www.phpbbstyles.com
 *
 *   version              : 2.2.0
 *
 *   file revision        : 55
 *   project revision     : 66
 *   last modified        : 09 Mar 2005  14:49:49
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