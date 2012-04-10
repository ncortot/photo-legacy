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

class ItemDirectory extends Item
{
    public function __construct($path)
    {
        global $globals;

        parent::__construct($path);

        //if (empty($this->text))
        //    $this->text = $globals->info->home;
    }

    public function isDir()
    {
        return true;
    }

    public function getList()
    {
        global $globals;

        $list = array();

        try
        {
            foreach (new DirectoryIterator($this->local_path) as $file) {
                if (substr($file->getFilename(), 0, 1) === '.')
                    continue;

                $new = ltrim($this->path.'/'.$file->getFilename(), '/');
                if ($file->isFile())
                    $list[] = new ItemImage($new);
                elseif ($file->isDir())
                    $list[] = new ItemDirectory($new);
            }
        }
        catch (Exception $e)
        {
            return false;
        }

        usort($list, array($this, "sort_by_name"));

        return $list;
    }

    protected function sort_by_name($a, $b)
    {
        $x = $a->name;
        $y = $b->name;

        if ($a->isDir() && !$b->isDir())
            return -1;
        elseif (!$a->isDir() && $b->isDir())
            return 1;
        else
            return strcmp($x, $y);
    }

    protected function make_image($list, $count)
    {
        global $globals;

        // create background image
        $file = $globals->path->icons.'/dir-background.png';
        $thumb = imagecreatefrompng($file);

        $lightblue = imagecolorallocate($thumb, 186, 208, 231);

        $bx = max(imagesx($thumb), 1);
        $by = max(imagesy($thumb), 1);

        settype($bx, 'integer');
        settype($by, 'integer');

        // add 4 thumbs to background
        for ($i = min(3, count($list) - 1); $i >= 0; $i--) {
            $im = $this->read_image($list[$i]->small_path);

            $sx = max(imagesx($im), 1);
            $sy = max(imagesy($im), 1);

            $tx = 0.6 * $sx;
            $ty = 0.6 * $sy;

            switch ($i) {
                case 3:
                    $dx = 2;
                    $dy = 44;
                    break;
                case 2:
                    $dx = 12;
                    $dy = 27;
                    break;
                case 1:
                    $dx = 30;
                    $dy = 10;
                    break;
                case 0:
                    $dx = 55;
                    $dy = 2;
                    break;
            }

            settype($dx, 'integer');
            settype($dy, 'integer');
            settype($sx, 'integer');
            settype($sy, 'integer');
            settype($tx, 'integer');
            settype($ty, 'integer');

            if ($list[$i]->isImage())
                imagefilledrectangle($thumb, $dx - 2, $dy - 2,
                                     $dx + $tx + 1, $dy + $ty + 1, $lightblue);

            imagecopyresampled($thumb, $im, $dx, $dy, 0, 0, $tx, $ty, $sx, $sy);
            imagedestroy($im);
        }

        // complete with foreground
        $file = $globals->path->icons.'/dir-foreground.png';
        $im = imagecreatefrompng($file);

        imagecopy($thumb, $im, 0, 0, 0, 0, $bx, $by);
        imagedestroy($im);

        // add number of elements
        $text = "$count ".$globals->info->count;

        $font = $globals->path->icons.'/directory.ttf';

        $darkblue = imagecolorallocate($thumb, 52, 101, 164);

        $bb = imagettfbbox(10, 0, $font, $text);
        $bw = abs($bb[4] - $bb[0]);
        $bh = abs($bb[5] - $bb[1]);

        $x2 = 127;
        $y2 = 130;

        $x1 = $x2 - $bw - 7;
        $y1 = $y2 - $bh - 6;

        $x3 = $x1 - $bb[0] + 3;
        $y3 = $y2 - $bb[1] - 3;

        imagefilledrectangle($thumb, $x1, $y1, $x2, $y2, $darkblue);
        imagefilledrectangle($thumb, $x1+2, $y1+2, $x2-2, $y2-2, $lightblue);

        imagettftext($thumb, 10, 0, $x3, $y3, $darkblue, $font, $text);


        imagesavealpha($thumb, true);

        return $thumb;
    }

    public function make_thumbs($flags)
    {
        global $globals;

        echo "> ".$this->local_path."\n";

        // list directory contents
        $list = $this->getList();
        $count = 0;
        $dir = 0;
        $br = true;

        // generate content's thumbnails
        foreach ($list as $item) {
            $r = $item->make_thumbs($flags);

            if ($r === false)
                $br = false;
            else
                $count += $r;

            if ($flags & ITEM_NONRECURSIVE && ++$dir > 3)
                break;
        }

        // create own image
        $thumb = $this->make_image($list, $count);

        // write image file
        $bt = $this->write_image($thumb, $this->small_path, IMAGETYPE_PNG);
        imagedestroy($thumb);

        // return numbers of sub-items, false on failure
        return ($br && $bt ? $count : false);
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
