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


abstract class GlModule
{
    abstract public function handlers();

    public function __construct()
    {
    }

    public static function factory($name)
    {
        $class = 'Gl'.ucfirst($name).'Module';
        return new $class();
    }

    protected function make_handler($function) {
        return array($this, 'handler_'.$function);
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
