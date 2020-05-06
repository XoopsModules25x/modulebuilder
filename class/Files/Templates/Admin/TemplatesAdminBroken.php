<?php

namespace XoopsModules\Tdmcreate\Files\Templates\Admin;

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
 * Class TemplatesAdminBroken.
 */
class TemplatesAdminBroken extends Files\CreateFile
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
     * @return TemplatesAdminBroken
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
     * @param        $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesAdminBrokenHeader
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesAdminBrokenHeader($moduleDirname)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret = $hc->getHtmlComment('Header', "\n");
        $ret .= $sc->getSmartyIncludeFile($moduleDirname, 'header', true, '', '', "\n\n");

        return $ret;
    }

    /**
     * @private  function getTemplatesAdminBrokenTableThead
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminBrokenTableThead($language, $t)
    {
        $hc         = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc         = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $th         = '';

        $lang = $sc->getSmartyConst($language, 'BROKEN_TABLE');
        $th   .= $hc->getHtmlTableHead($lang, 'center', '', $t . "\t\t");
        $lang = $sc->getSmartyConst($language, 'BROKEN_MAIN');
        $th   .= $hc->getHtmlTableHead($lang, 'center', '', $t . "\t\t");
        $lang = $sc->getSmartyConst($language, 'FORM_ACTION');
        $th   .= $hc->getHtmlTableHead($lang, 'center width5', '', $t . "\t\t");
        $tr   = $hc->getHtmlTableRow($th, 'head', $t . "\t");
        $ret  = $hc->getHtmlTableThead($tr, '', $t);

        return $ret;
    }

    /**
     * @private  function getTemplatesAdminBrokenTableTBody
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @return string
     * @internal param string $language
     */
    private function getTemplatesAdminBrokenTableTBody($tableName, $tableSoleName, $language)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $td = '';

        $doubleKey = $sc->getSmartyDoubleVar($tableSoleName, 'key');
        $doubleVal = $sc->getSmartyDoubleVar($tableSoleName, 'keyval');

        $double  = $sc->getSmartyDoubleVar($tableSoleName, 'table');
        $td      .= $hc->getHtmlTableData($double, 'center', '', "\t\t\t\t");
        $double  = $sc->getSmartyDoubleVar($tableSoleName, 'main');
        $td      .= $hc->getHtmlTableData($double, 'center', '', "\t\t\t\t");
        $lang    = $sc->getSmartyConst('', '_EDIT');
        $src     = $sc->getSmartyNoSimbol('xoModuleIcons16 edit.png');
        $img     = $hc->getHtmlImage($src, $tableName, '', '', '');
        $anchor  = $hc->getHtmlAnchor($tableName . ".php?op=edit&amp;{$doubleKey}=" . $doubleVal, $img, $lang, '', '', '', "\t\t\t\t\t", "\n");
        $lang    = $sc->getSmartyConst('', '_DELETE');
        $src     = $sc->getSmartyNoSimbol('xoModuleIcons16 delete.png');
        $img     = $hc->getHtmlImage($src, $tableName, '', '', '');
        $anchor  .= $hc->getHtmlAnchor($tableName . ".php?op=delete&amp;{$doubleKey}=" . $doubleVal, $img, $lang, '', '', '', "\t\t\t\t\t", "\n");
        $td      .= $hc->getHtmlTableData($anchor, 'center width5', '', "\t\t\t\t", "\n", true);
        $cycle   = $sc->getSmartyNoSimbol('cycle values=\'odd, even\'');
        $tr      = $hc->getHtmlTableRow($td, $cycle, "\t\t\t");
        $foreach = $sc->getSmartyForeach($tableSoleName, $tableName . '_list', $tr, '','', "\t\t\t");
        $tbody   = $hc->getHtmlTableTbody($foreach,'' , "\t\t");

        return $tbody;
    }

    /**
     * @private function getTemplatesAdminBrokenTable
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminBrokenTable($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $tbody  = $this->getTemplatesAdminBrokenTableThead($language, "\t\t");
        $tbody  .= $this->getTemplatesAdminBrokenTableTBody($tableName, $tableSoleName, $language, "\t\t");
        $ret    .= $hc->getHtmlTable($tbody, 'table table-bordered', "\t");

        return $ret;
    }

    /**
     * @private function getTemplatesAdminBrokenList
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminBrokenList($moduleDirname, $table, $language, $t = '')
    {
        $hc = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $ucfTableName  = ucfirst($tableName);
        $double    = $sc->getSmartySingleVar($tableName . '_result');
        $ret       = $hc->getHtmlHNumb($double, 3, '');
        $htmlTable = $this->getTemplatesAdminBrokenTable($moduleDirname, $tableName, $tableSoleName, $language);
        $htmlTable .= $hc->getHtmlDiv('&nbsp;', 'clear', $t, "\n", false);
        $single    = $sc->getSmartySingleVar('pagenav');
        $div       = $hc->getHtmlDiv($single, 'xo-pagenav floatright', $t . "\t", "\n", false);
        $div       .= $hc->getHtmlDiv('', 'clear spacer', $t . "\t" , "\n", false);
        $htmlTable .= $sc->getSmartyConditions('pagenav', '', '', $div, '', '', '', $t );
        $noData    = $sc->getSmartySingleVar('nodata' . $ucfTableName, $t . "\t\t");
        $src       = $sc->getSmartyNoSimbol('xoModuleIcons32 button_ok.png');
        $noData    .= $hc->getHtmlImage($src, $tableName,'','',"\n");
        $div       = $hc->getHtmlDiv($noData, '', $t . "\t", "\n", true);
        $div       .= $hc->getHtmlDiv('', 'clear spacer', $t . "\t" , "\n", false);
        $div       .= $hc->getHtmlBr('2', '', $t . "\t");
        $contElse  = $sc->getSmartyConditions('nodata' . $ucfTableName, '', '', $div, false, '', '', $t);
        $ret       .= $sc->getSmartyConditions($tableName . '_count', '', '', $htmlTable, $contElse);
        $ret       .= $hc->getHtmlEmpty('', '', "\n");

        return $ret;
    }

    /**
     * @private function getTemplatesAdminBrokenFooter
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesAdminBrokenFooter($moduleDirname)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $single = $sc->getSmartySingleVar('error');
        $strong = $hc->getHtmlTag('strong', [], $single, false, '', '');
        $div    = $hc->getHtmlDiv($strong, 'errorMsg', "\t", "\n");
        $ret    = $sc->getSmartyConditions('error', '', '', $div);
        $ret    .= $hc->getHtmlEmpty('', '', "\n");
        $ret    .= $hc->getHtmlComment('Footer', "\n");
        $ret    .= $sc->getSmartyIncludeFile($moduleDirname, 'footer', true);

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
        $tables         = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getTemplatesAdminBrokenHeader($moduleDirname);
        foreach ($tables as $table) {
            if (1 === (int)$table->getVar('table_broken')) {
                $content .= $this->getTemplatesAdminBrokenList($moduleDirname, $table, $language, "\t");
            }
        }
        $content .= $this->getTemplatesAdminBrokenFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
