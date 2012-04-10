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

class ItemImage extends Item
{
    protected $medium_path;

    public function __construct($path)
    {
        global $globals;

        parent::__construct($path);

        $this->small_path = $globals->path->small.'/'.$this->hash().'.jpg';
        $this->medium_path = $globals->path->medium.'/'.$this->hash().'.jpg';

        $ext = strrchr($this->name, '.');
        if($ext !== false)
            $this->text = substr($this->name, 0, -strlen($ext));
    }

    public function isImage()
    {
        return true;
    }


    public function getLink()
    {
        return path_encode('view/'.$this->path);
    }

    public function getImageSmall()
    {
        global $globals;
        return path_encode($globals->url->small.'/'.$this->hash().'.jpg');
    }

    public function getImageMedium()
    {
        global $globals;
        return path_encode($globals->url->medium.'/'.$this->hash().'.jpg');
    }

    public function getImageLarge()
    {
        global $globals;
        return path_encode($globals->url->large.'/'.$this->path, false);
    }

    protected function read_large()
    {
        return $this->read_image($this->local_path);
    }

    protected function make_image($image, $size_x, $size_y)
    {
        if (! $image)
            return false;

        // compute new image size
        $sx = max(imagesx($image), 1);
        $sy = max(imagesy($image), 1);

        settype($sx, 'integer');
        settype($sy, 'integer');

        if ($sx * $size_y < $size_x * $sy)
            $size_x = $size_y * $sx / $sy;
        else
            $size_y = $size_x * $sy / $sx;

        $size_x = max($size_x, 1);
        $size_y = max($size_y, 1);

        settype($size_x, 'integer');
        settype($size_y, 'integer');


        // create new image
        $new = imagecreatetruecolor($size_x, $size_y);
        imagecopyresampled($new, $image, 0, 0, 0, 0, $size_x, $size_y, $sx, $sy);

        return $new;
    }

    public function make_thumbs($flags)
    {
        global $globals;

        if ($flags & ITEM_DIRECTORY)
            return 1;

        echo "- ".$this->local_path."\n";

        // get saved and current metadata
        $data = new Data($this->path);
        $fdat = new Data();
        $fdat->read($this->data_path);

        // do we need to update the thumbnails ?
        if ( $data->equals($fdat)
          && (is_file($this->small_path) || !($flags & ITEM_SMALL))
          && (is_file($this->medium_path) || !($flags & ITEM_MEDIUM))
          && !($flags & ITEM_FORCEUPDATE) )
            return 1;

        // update needed
        echo "x ".$this->local_path."\n";

        $bs = $bm = true;

        if ($flags & ITEM_IMAGES)
            $image = $this->read_large();

        // small thumbnail creation
        if ($flags & ITEM_SMALL) {
            $x = $globals->images->small_x;
            $y = $globals->images->small_y;

            $im = $this->make_image($image, $x, $y);
            $bs = $this->write_image($im, $this->small_path, IMAGETYPE_JPEG); 
            @imagedestroy($im);
        }

        // medium image creation
        if ($flags & ITEM_MEDIUM) {
            $x = $globals->images->medium_x;
            $y = $globals->images->medium_y;

            $im = $this->make_image($image, $x, $y);
            $bm = $this->write_image($im, $this->medium_path, IMAGETYPE_JPEG); 
            @imagedestroy($im);
        }

        if ($flags & ITEM_IMAGES)
            @imagedestroy($image);

        // failure detection
        if (!$bs || !$bm) {
            $bu = $bs || $bm;
            echo 'Error creating or writing '
                 .($bs?'':'small')
                 .($bu?'':' and ')
                 .($bm?'':'medium')
                 .' image'.($bu?'':'s').' !';
            return false;
        }

        // write thumbnails metadata
        if (!$data->write($this->data_path)) {
            echo 'Error writing data file !';
            return false;
        }

        // one image counts for one file
        return 1;
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
