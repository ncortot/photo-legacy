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


define('ITEM_SMALL',        0x00000001);
define('ITEM_MEDIUM',       0x00000002);
define('ITEM_DIRECTORY',    0x00000004);
define('ITEM_FORCEUPDATE',  0x00000010);
define('ITEM_NONRECURSIVE', 0x00000020);

define('ITEM_IMAGES', ITEM_SMALL|ITEM_MEDIUM);



abstract class Item
{
    protected $path;

    protected $name;
    protected $text;

    protected $data_path;
    protected $small_path;
    protected $local_path;



    public function __construct($web_path)
    {
        global $globals;

        $this->path = $web_path = trim($web_path, '/');

        $this->name = basename($this->path);
        $this->text = basename($this->path);

        $this->data_path = $globals->path->data.'/'.$this->hash().'.txt';
        $this->small_path = $globals->path->small.'/'.$this->hash().'.png';
        $this->local_path = rtrim($globals->path->large.'/'.$web_path, '/');
    }

    public function isDir() {
        return false;
    }

    public function isImage() {
        return false;
    }



    abstract public function make_thumbs($flags);



    // display functions
    public function getShortText()
    {
        return format_short($this->text);
    }

    public function getLongText()
    {
        return format_long($this->text);
    }

    public function getLink() {
        return path_encode('list/'.$this->path);
    }

    public function getImageSmall()
    {
        global $globals;
        return path_encode($globals->url->small.'/'.$this->hash().'.png');
    }

    public function getVerticalSpace()
    {
        global $globals;

        list($w, $h, $t, $a) = getimagesize($this->small_path);
        if (!$h)
            return false;

        settype($h, 'integer');
        $h -= $globals->images->small_y;
        $h /= 2;

        return ($h ? -$h : 0);
    }


    public function getName()
    {
        return $this->name;
    }



    protected function hash()
    {
        return sha1($this->path);
    }



    protected function read_image($file)
    {
        try {
            list($ix, $iy) = @getimagesize($file);

            $ext = strtolower(strrchr(basename($file), '.'));

            switch (exif_imagetype($file)) {
                case IMAGETYPE_GIF:
                    $image = @imagecreatefromgif($file);
                break;
                case IMAGETYPE_JPEG:
                    $image = @imagecreatefromjpeg($file);
                break;
                case IMAGETYPE_PNG:
                    $image = @imagecreatefrompng($file);
                break;
                default:
                    $image = NULL;
            }
        }
        catch (Exception $e)
        {
            return false;
        }

        return ($image ? $image : false);
    }

    protected function write_image($image, $file, $type = IMAGETYPE_PNG)
    {
        global $globals;

        // error detection
        if (!$image) {
            echo "Image creation failed : unable to create image from".$this->local_path." for $file\n";

            $icon = $globals->path->icons.'/unknown.png';
            @copy($icon, $file);

            return false;
        }
        
        // write image file
        switch($type) {
            case IMAGETYPE_GIF :
                return imagegif($image, $file);
            case IMAGETYPE_JPEG :
                return imagejpeg($image, $file);
            case IMAGETYPE_PNG :
                return imagepng($image, $file);
            default:
                echo "Image creation failed : type \"$type\" not supported !\n";
                return false;
        }
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
