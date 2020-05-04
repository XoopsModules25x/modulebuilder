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
 * class Pages.
 */
class Pages extends Files\CreateFile
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
     * @return Pages
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
     * @private function getTemplatesUserPagesHeader
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesUserPagesHeader($moduleDirname)
    {
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'header', '','','',"\n\n");
    }

    /**
     * @private function getTemplatesUserPagesTable
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param string $language
     * @return string
     */
    private function getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc     = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc     = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $tbody  = $this->getTemplatesUserPagesTableThead($tableName, $language);
        $tbody  .= $this->getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSoleName);
        $tbody  .= $this->getTemplatesUserPagesTableTfoot();
        $single = $sc->getSmartySingleVar('table_type');

        return $hc->getHtmlTable($tbody, 'table table-' . $single, "\t");
    }

    /**
     * @private function getTemplatesUserPagesThead
     * @param string $language
     * @param        $tableName
     * @return string
     */
    private function getTemplatesUserPagesTableThead($tableName, $language)
    {
        $hc           = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc           = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $stuTableName = mb_strtoupper($tableName);
        $single       = $sc->getSmartySingleVar('divideby');
        $lang         = $sc->getSmartyConst($language, $stuTableName . '_TITLE');
        $th           = $hc->getHtmlTableHead($lang, '', $single, "\t\t\t\t");
        $tr           = $hc->getHtmlTableRow($th, 'head', "\t\t\t");

        return $hc->getHtmlTableThead($tr, '', "\t\t");
    }

    /**
     * @private function getTemplatesUserPagesTbody
     * @param string $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @return string
     */
    private function getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSoleName)
    {
        $hc      = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc      = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $single  = $sc->getSmartySingleVar('panel_type');
        $include = $sc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName, "\t\t\t\t\t\t", "\n");
        $div     = $hc->getHtmlDiv($include, 'panel panel-' . $single, "\t\t\t\t\t", "\n");
        $cont    = $hc->getHtmlTableData($div, '', '', "\t\t\t\t", "\n", true);
        $html    = $hc->getHtmlEmpty('</tr><tr>', "\t\t\t\t\t", "\n");
        $cont    .= $sc->getSmartyConditions($tableSoleName . '.count', ' is div by ', '$divideby', $html, '', '', '',"\t\t\t\t");
        $foreach = $sc->getSmartyForeach($tableSoleName, $tableName, $cont,'','',"\t\t\t\t");
        $tr      = $hc->getHtmlTableRow($foreach,'',"\t\t\t");

        return $hc->getHtmlTableTbody($tr,'',"\t\t");
    }

    /**
     * @private function getTemplatesUserPagesTfoot
     * @param null
     * @return string
     */
    private function getTemplatesUserPagesTableTfoot()
    {
        $hc = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $td = $hc->getHtmlTableData("&nbsp;", '', '', '', '');
        $tr = $hc->getHtmlTableRow($td, '', '', '');

        return $hc->getHtmlTableTfoot($tr, '', "\t\t", "\n", false);
    }

    /**
     * @private function getTemplatesUserPages
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getTemplatesUserPages($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc    = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc    = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $table = $this->getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSoleName, $language);
        $div   = $hc->getHtmlDiv($table, 'table-responsive');

        return $sc->getSmartyConditions($tableName . 'Count', ' > ', '0', $div, false, false, true);
    }

    /**
     * @private function getTemplatesUserPagesFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserPagesFooter($moduleDirname)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret = $hc->getHtmlEmpty('', '', "\n");
        $ret .= $sc->getSmartyIncludeFile($moduleDirname, 'footer');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplatesUserPagesHeader($moduleDirname);
        $content       .= $this->getTemplatesUserPages($moduleDirname, $tableName, $tableSoleName, $language);
        $content       .= $this->getTemplatesUserPagesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
