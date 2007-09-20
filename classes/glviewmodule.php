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


class GlViewModule extends GlModule
{
    public function handlers()
    {
        return Array(
            'list'   => $this->make_handler('list'),
            'view'   => $this->make_handler('view'),
        );
    }

    protected function make_head(&$page, $path)
    {
        $name = basename($path);
        $root = new ItemDirectory(dirname($path));
        $list = $root->getList();
       
        if ( $list === false )
          return false;

        $found = false;

        for ($i = 0; $i < count($list); $i++)
            if ($list[$i]->getName() === $name) {
                $found = true;
                break;
            }

        $page->assign('view_root', $root);
        $page->assign('view_item', ($found ? $list[$i] : $root));

        if ($found && $i > 0)
            $page->assign('view_prev', $list[$i - 1]);
        if ($found && $i < count($list) - 1)
            $page->assign('view_next', $list[$i + 1]);

        return array($root, $list, $i);
    }

    public function handler_list(&$page, $path)
    {
        global $globals;

        $dir = new ItemDirectory($path);
        $list = $dir->getList();

        if ( $list === false )
            return false;

        if (!$this->make_head(&$page, $path))
            return false;

        $page->assign('list_items', $list);

        $page->changeTpl('view/list.tpl');

        return true;
    }

    public function handler_view(&$page, $path)
    {
        global $globals;

        $file = $globals->path->large.'/'.$path;

        if (!is_file($file))
            return false;

        if (!$this->make_head(&$page, $path))
            return false;
        
        $page->changeTpl('view/view.tpl');

        return true;
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
