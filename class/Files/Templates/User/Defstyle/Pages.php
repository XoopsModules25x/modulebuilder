<?php

namespace XoopsModules\Modulebuilder\Files\Templates\User\Defstyle;

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
     * @var mixed
     */
    private $hc = null;

    /**
     * @var mixed
     */
    private $sc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $this->sc = Modulebuilder\Files\CreateSmartyCode::getInstance();
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
     * @param        $module
     * @param        $table
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
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'header', '','','',"\n\n");
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
        $tbody  = $this->getTemplatesUserPagesTableThead($tableName, $language);
        $tbody  .= $this->getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSoleName);
        $tbody  .= $this->getTemplatesUserPagesTableTfoot();
        $single = $this->sc->getSmartySingleVar('table_type');

        return $this->hc->getHtmlTable($tbody, 'table table-' . $single, "\t");
    }

    /**
     * @private function getTemplatesUserPagesThead
     * @param string $language
     * @param        $tableName
     * @return string
     */
    private function getTemplatesUserPagesTableThead($tableName, $language)
    {
        $stuTableName = \mb_strtoupper($tableName);
        $single       = $this->sc->getSmartySingleVar('divideby');
        $lang         = $this->sc->getSmartyConst($language, $stuTableName . '_TITLE');
        $th           = $this->hc->getHtmlTableHead($lang, '', $single, "\t\t\t\t");
        $tr           = $this->hc->getHtmlTableRow($th, 'head', "\t\t\t");

        return $this->hc->getHtmlTableThead($tr, '', "\t\t");
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
        $single  = $this->sc->getSmartySingleVar('panel_type');
        $include = $this->sc->getSmartyIncludeFile($moduleDirname, $tableName . '_item', false,false, "\t\t\t\t\t\t", "\n");
        $div     = $this->hc->getHtmlDiv($include, 'panel panel-' . $single, "\t\t\t\t\t", "\n");
        $cont    = $this->hc->getHtmlTableData($div, '', '', "\t\t\t\t", "\n", true);
        $html    = $this->hc->getHtmlEmpty('</tr><tr>', "\t\t\t\t\t", "\n");
        $cont    .= $this->sc->getSmartyConditions('smarty.foreach.' . $tableSoleName . '.iteration', ' is div by ', '$divideby', $html, '', '', '',"\t\t\t\t", "\n", true, false);
        $foreach = $this->sc->getSmartyForeach($tableSoleName, $tableName, $cont, $tableSoleName,'',"\t\t\t\t");
        $tr      = $this->hc->getHtmlTableRow($foreach,'',"\t\t\t");

        return $this->hc->getHtmlTableTbody($tr,'',"\t\t");
    }

    /**
     * @private function getTemplatesUserPagesTfoot
     * @param null
     * @return string
     */
    private function getTemplatesUserPagesTableTfoot()
    {
        $td = $this->hc->getHtmlTableData("&nbsp;", '', '', '', '');
        $tr = $this->hc->getHtmlTableRow($td, '', '', '');

        return $this->hc->getHtmlTableTfoot($tr, '', "\t\t", "\n", false);
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
        $table = $this->getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSoleName, $language);
        $div   = $this->hc->getHtmlDiv($table, 'table-responsive');

        return $this->sc->getSmartyConditions($tableName . 'Count', ' > ', '0', $div, false, false, true, '', "\n", true, 'int');
    }

    /**
     * @private function getTemplatesUserPagesForm
     * @param string $t
     * @return string
     */
    private function getTemplatesUserPagesForm($t = "\t")
    {
        $var  = $this->sc->getSmartySingleVar('form', $t, "\n");

        return $this->sc->getSmartyConditions('form', '', '', $var, false, false, true);
    }

    /**
     * @private function getTemplatesUserPagesError
     * @param string $t
     * @return string
     */
    private function getTemplatesUserPagesError($t = "\t")
    {
        $var  = $this->sc->getSmartySingleVar('error', $t, "\n");

        return $this->sc->getSmartyConditions('error', '', '', $var, false, false, true);
    }

    /**
     * @private function getTemplatesUserPagesFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserPagesFooter($moduleDirname)
    {
        $ret = $this->hc->getHtmlEmpty('', '', "\n");
        $ret .= $this->sc->getSmartyIncludeFile($moduleDirname, 'footer');

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
        $content       .= $this->getTemplatesUserPagesForm();
        $content       .= $this->getTemplatesUserPagesError();
        $content       .= $this->getTemplatesUserPagesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
