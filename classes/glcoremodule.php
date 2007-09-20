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


class GlCoreModule extends GlModule
{
    public function handlers() {
        return Array(
            '404'          => $this->make_handler('404'),
            'favicon.ico'  => $this->make_handler('favicon'),
            'robots.txt'   => $this->make_handler('robots'),
        );
    }

    function handler_404(&$page, $path)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        $page->changeTpl('core/404.tpl');
    }

    function handler_favicon(&$page)
    {
        global $globals;
        $data = file_get_contents($globals->root.'/htdocs/images/favicon.ico');
        header('Content-Type: image/x-icon');
        echo $data;
        exit;
    }

    function handler_robots(&$page)
    {
        header('Content-Type: text/plain');
        echo "User-Agent: *\n";
        echo "Disallow: /\n";
        exit;
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
