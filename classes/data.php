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
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston,
    MA  02111-1307, USA.
*/

class Data
{
    protected $path;
    protected $size;
    protected $time;


    public function __construct($path = NULL)
    {
        echo "  Data::__construct($path)\n";

        if (!empty($path))
            $this->stat($path);
    }


    public function stat($path)
    {
        global $globals;

        $file = rtrim($globals->path->large.'/'.$path, '/');

        echo "  trying stat($file)\n";
        if (empty($path) || !($stat = stat($file))) {
            echo "  BLAH\n";
            return false;
        }
        echo "  OK\n";

        $this->path = $path;
        $this->size = $stat['size'];
        $this->time = $stat['mtime'];
    }


    public function read($file)
    {
        echo "  opening data file\n";
        if (!($h = @fopen($file, 'r'))) {
            echo "  data file not found\n";
            return false;
        }

        echo "  reading data file\n";
        $p = substr(rtrim(fgets($h), "\n"), 6);
        fscanf($h, "size: %u\n", &$s);
        fscanf($h, "modified: %u\n", &$t);

        if (!fclose($h) || empty($p) || !$s || !$t) {
            echo "  something went wrong\n";
            return false;
        }

        $this->path = $p;
        $this->size = $s;
        $this->time = $t;

        echo "  data ok\n";
        return true;
    }

    public function write($file)
    {
        if (empty($this->path) || $this->size == 0 || $this->time == 0)
            return false;

        return ($h = fopen($file, 'w'))
            && fwrite($h, sprintf("file: %s\nsize: %u\nmodified: %u\n",
                                  $this->path, $this->size, $this->time))
            && fclose($h);
    }

    public function equals(Data $d)
    {
        if (!$d)
            return false;

        return ($this->path === $d->path)
            && ($this->size === $d->size)
            && ($this->time === $d->time);
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
