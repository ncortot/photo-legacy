{**************************************************************************}
{*                                                                        *}
{*  Copyright (C) 2007 Nicolas CORTOT                                     *}
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
{*  along with this program; if not, write to the                         *}
{*  Free Software Foundation, Inc.,                                       *}
{*  59 Temple Place, Suite 330, Boston, MA  02111-1307  USA               *}
{*                                                                        *}
{**************************************************************************}
{if $smarty.server.HTTP_USER_AGENT|regex_replace:"/MSIE 6/":""}
<?xml version="1.0" encoding="utf-8"?>
{/if}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <base href="{$globals->baseurl}/" />
    <link rel="stylesheet" type="text/css" href="css/default.css" media="all" />
    {include file=header.tpl}
  </head>
  <body>

{include file=contents.tpl}

{include file=footer.tpl}

  </body>
</html>
{* vim:set et sw=2 sts=2 sws=2 enc=utf-8: *}
