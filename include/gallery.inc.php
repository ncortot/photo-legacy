<?php
/*
    Copyright (C) 2007 Nicolas CORTOT

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the
    Free Software Foundation, Inc.,
    59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/


// autoloading classes
function __autoload($class)
{
    include dirname(dirname(__FILE__)).'/classes/'.strtolower($class).'.php';
}


// get Env ASAP
__autoload('Env');


// Global configuration data
$globals = new Globals('GallerySession');


// Misc functions
require_once 'functions.inc.php';


define('SKINNED', 0);
define('NO_SKIN', 1);


// Used by Smarty
$_SESSION = array();

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
