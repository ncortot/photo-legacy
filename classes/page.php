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

if (!@include_once 'smarty/libs/Smarty.class.php') {
    require_once 'smarty/Smarty.class.php';
}

class Page extends Smarty
{
    protected $_tpl;
    protected $_type;
    protected $_errors;
    protected $_failure;

    // constructor
    public function __construct($tpl, $type = SKINNED)
    {
        global $globals;

        parent::__construct();

        $this->template_dir  = $globals->root."/templates/";
        $this->compile_dir   = $globals->root."/spool/templates_c/";
        array_unshift($this->plugins_dir, $globals->root."/plugins/");
        $this->config_dir    = $globals->root."/config/";

        $this->_tpl       = $tpl;
        $this->_type      = $type;
        $this->_errors    = array();
        $this->_failure   = false;
    }

    // Change contents template
    public function changeTpl($tpl, $type = SKINNED)
    {
        $this->_tpl = $tpl;
        $this->_type = $type;
        $this->assign('gallery_tpl', $tpl);
    }

    public function addCssLink($path)
    {
        $this->append('gallery_css', $path);
    }

    public function addCssInline($css)
    {
        if (!empty($css)) {
            $this->append('gallery_inline_css', $css);
        }    
    }

    public function addJsLink($path)
    {
        $this->append('gallery_js', $path);
    }

    public function run()
    {
        global $globals;

        session_write_close();

        $this->assign('gallery_errors', $this->_errors);
        $this->assign('gallery_failure', $this->_failure);
        $this->assign('globals', $globals);


        $this->register_outputfilter('hide_emails');
        $this->addJsLink('wiki.js');
        header("Accept-Charset: utf-8");


        //error_reporting(0);
        error_reporting(1);
        $this->display('page.tpl');
        exit;
    }
}


function _hide_email($source)
{
    $source = str_replace("\n", '', $source);
    return '<script type="text/javascript">//<![CDATA[' . "\n" .
           'Nix.decode("' . addslashes(str_rot13($source)) . '");' . "\n" .
           '//]]></script>'; 
}

function hide_emails($source, $smarty)
{
    //prevent email replacement in <script> and <textarea>
    $tags = '(script|textarea|select)';
    preg_match_all("!<$tags.*?>.*?</$tags>!ius", $source, $tagsmatches);
    $source = preg_replace("!<$tags.*?>.*?</$tags>!ius", "&&&tags&&&", $source);

    //catch all emails in <a href="mailto:...">
    preg_match_all("!<a[^>]+href=[\"'][^\"']*[-a-z0-9+_.]+@[-a-z0-9_.]+[^\"']*[\"'].*?>.*?</a>!ius", $source, $ahref);
    $source = preg_replace("!<a[^>]+href=[\"'][^\"']*[-a-z0-9+_.]+@[-a-z0-9_.]+[^\"']*[\"'].*?>.*?</a>!ius", '&&&ahref&&&', $source);

    //prevant replacement in tag attributes
    preg_match_all("!<[^>]+[-a-z0-9_+.]+@[-a-z0-9_.]+.+?>!is", $source, $misc);
    $source = preg_replace("!<[^>]+[-a-z0-9_+.]+@[-a-z0-9_.]+.+?>!is", '&&&misc&&&', $source);

    //catch !
    $source = preg_replace('!([-a-z0-9_+.]+@[-a-z0-9_.]+)!ie', '_hide_email("\1")', $source); 
    $source = preg_replace('!&&&ahref&&&!e', '_hide_email(array_shift($ahref[0]))', $source);

    // restore data
    $source = preg_replace('!&&&misc&&&!e', 'array_shift($misc[0])', $source);
    $source = preg_replace("!&&&tags&&&!e",  'array_shift($tagsmatches[0])', $source);

    return $source;
}


// vim:set et sw=4 sts=4 sws=4 foldmethod=marker enc=utf-8:
?>
