<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Templates\Admin;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * Class TemplatesAdminBroken.
 */
class TemplatesAdminBroken extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $hc = null;
    /**
     * @var mixed
     */
    private $sc = null;

    /**
     * @public function constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $this->sc = Modulebuilder\Files\CreateSmartyCode::getInstance();
    }

    /**
     * @static function getInstance
     *
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
     * @param        $module
     * @param        $tables
     * @param        $filename
     */
    public function write($module, $tables, $filename): void
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
    private function getTemplatesAdminBrokenHeader(string $moduleDirname)
    {
        $ret = $this->hc->getHtmlComment('Header', '', "\n");
        $ret .= $this->sc->getSmartyIncludeFile($moduleDirname, 'header', true, '', "\n\n");

        return $ret;
    }

    /**
     * @private  function getTemplatesAdminBrokenTableThead
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminBrokenTableThead(string $language)
    {
        $th   = '';
        $lang = $this->sc->getSmartyConst($language, 'BROKEN_TABLE');
        $th   .= $this->hc->getHtmlTableHead($lang, 'center', '', "\t\t\t\t");
        $lang = $this->sc->getSmartyConst($language, 'BROKEN_MAIN');
        $th   .= $this->hc->getHtmlTableHead($lang, 'center', '', "\t\t\t\t");
        $lang = $this->sc->getSmartyConst($language, 'FORM_ACTION');
        $th   .= $this->hc->getHtmlTableHead($lang, 'center width5', '', "\t\t\t\t");
        $tr   = $this->hc->getHtmlTableRow($th, 'head', "\t\t" . "\t");
        return $this->hc->getHtmlTableThead($tr, '', "\t\t");
    }

    /**
     * @private  function getTemplatesAdminBrokenTableTBody
     * @param string $tableName
     * @param        $tableSoleName
     * @return string
     * @internal param string $language
     */
    private function getTemplatesAdminBrokenTableTBody(string $tableName, $tableSoleName)
    {
        $td        = '';
        $doubleKey = $this->sc->getSmartyDoubleVar($tableSoleName, 'key', '', '', "''|escape:'htmlattr'");
        $doubleVal = $this->sc->getSmartyDoubleVar($tableSoleName, 'keyval', '', '', "''|escape:'htmlattr'");

        $double  = $this->sc->getSmartyDoubleVar($tableSoleName, 'table', '', '', "''|escape:'html'");
        $td      .= $this->hc->getHtmlTableData($double, 'center', '', "\t\t\t\t");
        $double  = $this->sc->getSmartyDoubleVar($tableSoleName, 'main', '', '', "''|escape:'html'");
        $td      .= $this->hc->getHtmlTableData($double, 'center', '', "\t\t\t\t");
        $lang    = $this->sc->getSmartyConst('', '_EDIT');
        $src     = $this->sc->getSmartyNoSimbol("xoModuleIcons16 'edit.png'");
        $img     = $this->hc->getHtmlImage($src, $tableName);
        $anchor  = $this->hc->getHtmlAnchor($tableName . ".php?op=edit&amp;{$doubleKey}=" . $doubleVal, $img, $lang, '', '', '', "\t\t" . "\t\t\t", "\n");
        $lang    = $this->sc->getSmartyConst('', '_DELETE');
        $src     = $this->sc->getSmartyNoSimbol("xoModuleIcons16 'delete.png'");
        $img     = $this->hc->getHtmlImage($src, $tableName);
        $anchor  .= $this->hc->getHtmlAnchor($tableName . ".php?op=delete&amp;{$doubleKey}=" . $doubleVal, $img, $lang, '', '', '', "\t\t" . "\t\t\t", "\n");
        $td      .= $this->hc->getHtmlTableData($anchor, 'center width5', '', "\t\t\t\t", "\n", true);
        $cycle   = $this->sc->getSmartyNoSimbol('cycle values=\'odd, even\'');
        $tr      = $this->hc->getHtmlTableRow($td, $cycle, "\t\t" . "\t");
        $foreach = $this->sc->getSmartyForeach($tableSoleName, $tableName . '_list', $tr, '', '', "\t\t" . "\t");
        return $this->hc->getHtmlTableTbody($foreach, '', "\t\t");
    }

    /**
     * @private function getTemplatesAdminBrokenTable
     * @param string $tableName
     * @param        $tableSoleName
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminBrokenTable(string $tableName, $tableSoleName, string $language)
    {
        $tbody = $this->getTemplatesAdminBrokenTableThead($language);
        $tbody .= $this->getTemplatesAdminBrokenTableTBody($tableName, $tableSoleName);
        return $this->hc->getHtmlTable($tbody, 'outer', "\t");
    }

    /**
     * @private function getTemplatesAdminBrokenList
     * @param        $table
     * @param string $language
     * @param string $t
     * @return string
     */
    private function getTemplatesAdminBrokenList($table, string $language, string $t = '')
    {
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $ucfTableName  = \ucfirst($tableName);
        $double        = $this->sc->getSmartySingleVar($tableName . '_result');
        $ret           = $this->hc->getHtmlHNumb($double, '3');
        $htmlTable     = $this->getTemplatesAdminBrokenTable($tableName, $tableSoleName, $language);
        $htmlTable     .= $this->hc->getHtmlDiv('&nbsp;', 'clear', $t, "\n", false);
        $single        = $this->sc->getSmartySingleVar('pagenav_' . $tableName, '', '', '');
        $div           = $this->hc->getHtmlDiv($single, 'xo-pagenav floatright', $t . "\t", "\n", false);
        $div           .= $this->hc->getHtmlDiv('', 'clear spacer', $t . "\t", "\n", false);
        $htmlTable     .= $this->sc->getSmartyConditions('pagenav_' . $tableName, '', '', $div, '', '', '', $t);
        $noData        = $this->sc->getSmartySingleVar('nodata' . $ucfTableName, $t . "\t\t");
        $src           = $this->sc->getSmartyNoSimbol("xoModuleIcons32 'button_ok.png'");
        $noData        .= $this->hc->getHtmlImage($src, $tableName, '', '', "\n");
        $div           = $this->hc->getHtmlDiv($noData, '', $t . "\t");
        $div           .= $this->hc->getHtmlDiv('', 'clear spacer', $t . "\t", "\n", false);
        $div           .= $this->hc->getHtmlBr(2, '', $t . "\t");
        $contElse      = $this->sc->getSmartyConditions('nodata' . $ucfTableName, '', '', $div, false, '', '', $t);
        $ret           .= $this->sc->getSmartyConditions($tableName . '_count', '', '', $htmlTable, $contElse);
        $ret           .= $this->hc->getHtmlBr(3);

        return $ret;
    }

    /**
     * @private function getTemplatesAdminBrokenFooter
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesAdminBrokenFooter(string $moduleDirname)
    {
        $single = $this->sc->getSmartySingleVar('error');
        $strong = $this->hc->getHtmlTag('strong', [], $single, false, '', '');
        $div    = $this->hc->getHtmlDiv($strong, 'errorMsg', "\t");
        $ret    = $this->sc->getSmartyConditions('error', '', '', $div);
        $ret    .= $this->hc->getHtmlEmpty('', '', "\n");
        $ret    .= $this->hc->getHtmlComment('Footer', '', "\n");
        $ret    .= $this->sc->getSmartyIncludeFile($moduleDirname, 'footer', true);

        return $ret;
    }

    /**
     * @public function render
     *
     * @return string
     */
    public function render()
    {
        $module        = $this->getModule();
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM', '', false);
        $content       = $this->getTemplatesAdminBrokenHeader($moduleDirname);
        foreach ($tables as $table) {
            if (1 === (int)$table->getVar('table_broken')) {
                $content .= $this->getTemplatesAdminBrokenList($table, $language, "\t");
            }
        }
        $content .= $this->getTemplatesAdminBrokenFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
