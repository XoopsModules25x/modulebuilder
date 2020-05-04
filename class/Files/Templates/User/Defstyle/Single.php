<?php

namespace XoopsModules\Tdmcreate\Files\Templates\User\Defstyle;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * class Single.
 */
class Single extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return Single
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     * @param string $module
     * @param string $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserSingleHeader
     * @param $moduleDirname
     * @return string
     */
    private function getTemplatesUserSingleHeader($moduleDirname)
    {
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret    = $sc->getSmartyIncludeFile($moduleDirname);

        return $ret;
    }

    /**
     * @private function getTemplatesUserSingleBody
     * @param string $moduleDirname
     * @param string $table
     * @param string $language
     *
     * @return string
     */
    private function getTemplatesUserSingleBody($moduleDirname, $table, $language)
    {
        $tableName = $table->getVar('table_name');
        $hc        = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $ret       = '';
        $ret       .= $hc->getHtmlEmpty('', '',"\n");
        $content   = $hc->getHtmlHNumb('Services Panels', '2', 'page-header', "\t\t\t");
        $collg12   = $hc->getHtmlDiv($content, 'col-lg-12', "\t\t");
        $row       = $hc->getHtmlDiv($collg12, 'row', "\t");
        $ret       .= $hc->getHtmlDiv($row, 'container');
        $ret       .= $hc->getHtmlEmpty('', '',"\n");

        return $ret;
    }

    /**
     * @private function getTemplatesUserSingleFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserSingleFooter($moduleDirname)
    {
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret    = $sc->getSmartyIncludeFile($moduleDirname, 'footer');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplatesUserSingleHeader($moduleDirname);
        $content       .= $this->getTemplatesUserSingleBody($moduleDirname, $table, $language);
        $content       .= $this->getTemplatesUserSingleFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
