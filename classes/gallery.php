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


class Gallery
{
    protected $path;

    protected $modules;
    protected $handlers;

    public function __construct()
    {
        $modules = func_get_args();

        $this->path = path_decode(Get::_get('n', null));

        $this->handlers = array();
        $this->handlers = array();

        foreach($modules as $module) {
            $m = GLModule::factory($module);
            $this->modules[$module] = $m;
            $this->handlers += $m->handlers();
        }
    }


    protected function call_handler($handler, $page, $mod_path) {
        if (!empty($this->handlers[$handler])
         && call_user_func($this->handlers[$handler], $page, $mod_path))
            return true;

        if ($handler !== '404')
            return $this->call_handler('404', $page, $mod_path);
        else
            return false;
    }

    public function run()
    {
        global $globals;

        $page = new Page('index.tpl', SKINNED);

        if (empty($this->path))
            $this->path = 'list';

        $h = $p = $this->path;

        while ($h) {
            if (array_key_exists($h, $this->handlers))
                break;
            $h = substr($h, 0, strrpos($h, '/'));
        }

        $p = substr($p, strlen($h));

        if (empty($h))
            $h = 'list';

        $this->call_handler($h, $page, $p);

        $page->run();
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
