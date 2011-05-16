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


function microtime_float()
{
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}
$TIME_BEGIN = microtime_float();


// apply url_encode, but keep /'s
function path_encode($path, $hack = true)
{
    $tokens = preg_split('#/#', $path);

    foreach ($tokens as $t)
        $p .= '/'.rawurlencode($t);

    // double encoding for mod_rewrite (Apache bug 34602 ?)
    if ($hack) {
        $p = str_replace('%25', '%2525', $p);
        $p = str_replace('%26', '%2526', $p);
    }

    $count = 0;
    do $p = str_replace('//', '/', $p, $count);
    while ($count != 0);

    return trim($p, '/');
}

// url_decode, strip leading and trailing /'s, and /..?/'s
function path_decode($path)
{
    $tokens = preg_split('#/#', $path);
    $p = '/';

    foreach ($tokens as $t)
        $p .= rawurldecode($t).'/';

    $p = str_replace('/./', '/', $p);
    $p = str_replace('/../', '/', $p);
    $count = 0;
    do $p = str_replace('//', '/', $p, $count);
    while ($count != 0);

    $p = trim($p, '/');
    return $p;
}


function format_long($name)
{
    // Process names in form "yyyy-mm-dd.yyyy-mm-dd HH-MM-SS nn - text"
    $y = '(\d{4})';
    $md = "(\d{2})(-(\d{2}))?";
    $hms = "(\d{2})-(\d{2})-(\d{2})(\w)?";
    $pattern = "/^$y(-$md)?(\.$y?-?($md)?)?( $hms)?( (\w+))?( - (.*))?$/";

    if (!preg_match($pattern, $name, $m))
        return htmlspecialchars($name);

    $d = '';

    // process first part of date
    if ($m[5])
        $d .= $m[5].'/';
    if ($m[3])
        $d .= $m[3].'/';
    $d .= $m[1];

    // process second part of date
    if ($m[7] || $m[9]) {
        $d .= '&nbsp;-&nbsp;';

        if ($m[11]) {
            $d .= $m[11].'/'.$m[9].'/';
        } elseif ($m[9]) {
            if ($m[5])
                $d .= $m[9].'/'.$m[3].'/';
            else
                $d .= $m[9].'/';
        }

        if ($m[7])
            $d .= $m[7];
        else
            $d .= $m[1];
    }

    // add time if present
    $i = 13;
    if ($m[$i] && $m[$i+1] && $m[$i+2])
        $d .= '&nbsp;'.$m[$i].':'.$m[$i+1].':'.$m[$i+2];

    // add text if relevant
    $i = 20;
    if ($m[$i])
        $d .= ' '.htmlspecialchars($m[$i]);

    return $d;
}


function format_short($name)
{
    // Extract comment from name "... - Text"
    $pattern = "/^[\d\s-.]+ - (.*)$/";

    if (preg_match($pattern, $name, $m))
        return htmlspecialchars($m[1]);
    else
        return htmlspecialchars($name);
}

// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
