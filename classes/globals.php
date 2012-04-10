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


class Globals
{
    protected $locale;
    protected $timezone;

    public $session;

    /** paths */
    public $baseurl;
    public $spoolroot;

    public function __construct($sess)
    {
        $this->session   = $sess;

        if (isset($_SERVER['SERVER_NAME'])) {
            $base = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
            $this->baseurl = trim($base.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']), '/');
        } else {
            $this->baseurl = trim(dirname($_SERVER['PHP_SELF']), '/');
        }
        $this->root = dirname(dirname(__FILE__));

        # read configuration
        $this->read_config();
        $this->setlocale();

        # construct helpers
        $this->path = new stdClass;
        $this->path->data = $this->root.'/spool/images_data';
        $this->path->small = $this->root.'/spool/images_small';
        $this->path->medium = $this->root.'/spool/images_medium';
        $this->path->large = $this->root.'/htdocs/'.$this->url->large;
        $this->path->icons = $this->root.'/htdocs/'.$this->url->icons;

        $this->info->year = date('Y');
    }

    function read_ini_file($filename)
    {
        $array = parse_ini_file($filename, true);
        if (!is_array($array)) {
            return;
        }
        foreach ($array as $cat => $conf) {
            $c = strtolower($cat);
            foreach ($conf as $k => $v) {
                if ($c == 'core' && property_exists($this, $k)) {
                    $this->$k=$v;
                } else {
                    if (!isset($this->$c)) {
                        $this->$c = new stdClass;
                    }
                    $this->$c->$k = $v;
                }
            }
        }
    }

    function read_config()
    {
        $this->read_ini_file($this->root.'/config/gallery.conf');
    }

    function setlocale()
    {
        setlocale(LC_MESSAGES, $this->locale);
        setlocale(LC_TIME,     $this->locale);
        setlocale(LC_CTYPE,    $this->locale);
        date_default_timezone_set($this->timezone);
    }
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker:
?>
