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

{include file=view/head.tpl}

<div id="view">
{foreach from=$list_items item=item}
  <div class="item">
    <a href="{$item->getLink()}">
    <img style="margin: {$item->getVerticalSpace()}px 0px;" src="{$item->getImageSmall()}" alt="[{$item->getShortText()}]" /><br />
    {$item->getLongText()}
    </a>
  </div>
{/foreach}
  <div class="spacer">&nbsp;</div>
</div>

{* vim:set et sw=2 sts=2 sws=2 enc=utf-8: *}
