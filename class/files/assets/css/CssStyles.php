<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: 1.91 CssStyles.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class CssStyles.
 */
class CssStyles extends TDMCreateFile
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return CssStyles
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function write
    *  @param string $module
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename, '@charset "UTF-8";');
        $content .= <<<EOT
table > .{$moduleDirname} {
   margin: 0;
   padding: 2px;
   border: 1px solid #ccc;
   width: 100%;
}

thead {
   margin: 0;
   padding: 5px;
}

tbody {
   margin: 0;
   padding: 5px;
}

tr {
   font-family: verdana, tahoma, sans-serif;
}

td {
   font-size: 12px;
   font-weight: normal;
   padding: 5px;
}

div.outer {
   color: #555;
   background-color: #eee;
   border: 1px solid #ccc;
   width: 100%;
}

ul.menu > li {
   display: inline;
   width: 100%;
   text-align: center;
   list-style-type: none;
   padding: 0 5px 0 5px;
}

span.block-pie {
	float:left;
	padding:2px 8px 2px 8px;
	border-right:1px solid #444;
}

span.block-pie:first-child {
	padding:2px 8px 2px 0;
}

span.block-pie:last-child {
	padding: 2px 0 2px 8px;
	border:none;
}
.printOnly {
  display: none;
}
EOT;
        $this->create($moduleDirname, 'assets/css', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
