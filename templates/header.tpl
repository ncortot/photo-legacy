{**************************************************************************}
{*                                                                        *}
{*  Copyright (C) 2003-2007 Polytechnique.org                             *}
{*  http://opensource.polytechnique.org/                                  *}
{*                                                                        *}
{*  This program is free software; you can redistribute it and/or modify  *}
{*  it under the terms of the GNU General Public License as published by  *}
{*  the Free Software Foundation; either version 2 of the License, or     *}
{*  (at your option) any later version.                                   *}
{*                                                                        *}
{*  This program is distributed in the hope that it will be useful,       *}
{*  but WITHOUT ANY WARRANTY; without even the implied warranty of        *}
{*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *}
{*  GNU General Public License for more details.                          *}
{*                                                                        *}
{*  You should have received a copy of the GNU General Public License     *}
{*  along with this program; if not, write to the Free Software           *}
{*  Foundation, Inc.,                                                     *}
{*  59 Temple Place, Suite 330, Boston, MA  02111-1307  USA               *}
{*                                                                        *}
{**************************************************************************}

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Gallery" />
    <meta name="keywords" content="photo image gallery" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="images/favicon.png" type="image/png" />
    <link rel="index"  href="/" />
    {foreach from=$gallery_css item=css}
    <link rel="stylesheet" type="text/css" href="css/{$css}" media="all"/>
    {/foreach}
    {foreach from=$gallery_inline_css item=css}
    <style type="text/css">
    {$css|smarty:nodefaults}
    </style>
    {/foreach}
    {foreach from=$gallery_js item=js}
    <script type="text/javascript" src="javascript/{$js}"></script>
    {/foreach}

    <title>{$globals->info->title|default:"Gallery"}</title>

{* vim:set et sw=2 sts=2 sws=2 enc=utf-8: *}
