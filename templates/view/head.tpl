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

<div id="header">
{if $view_prev}
  <a class="view_prev" href="{$view_prev->getLink()}">
    <img src="images/{if $view_prev->isDir()}dir{else}go{/if}-previous.png" alt="[Previous]" />
    <span>{$view_prev->getLongText()}</span>
  </a>
{/if}
{if $view_next}
  <a class="view_next" href="{$view_next->getLink()}">
    <img src="images/{if $view_next->isDir()}dir{else}go{/if}-next.png" alt="[Next]" />
    <span>{$view_next->getLongText()}</span>
  </a>
{/if}
{if $view_item->getLongText()}
  <a class="view_up" href="{$view_root->getLink()}">
    <img src="images/go-up.png" alt="[UP]" />
{if $list_items}
    <span>{$view_item->getLongText()}</span>
{else}
    <span>{$view_root->getLongText()}</span>
{/if}
  </a>
{else}
  <a class="view_up" href="{$view_root->getLink()}">
    <img src="images/album.png" alt="[{$globals->info->title}]" />
    <span>{$globals->info->title}</span>
  </a>
{/if}

  <hr class="spacer" />
</div>

{* vim:set et sw=2 sts=2 sws=2 enc=utf-8: *}
