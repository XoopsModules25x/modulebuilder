<?php

namespace XoopsModules\Modulebuilder\Files\Templates\User\Defstyle;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;
use XoopsModules\Modulebuilder\Files\Templates\User;

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
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class Categories.
 */
class Categories extends Files\CreateFile
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
     * @return bool|Categories
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
     * @private function getTemplatesUserCategoriesHeader
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesUserCategoriesHeader($moduleDirname)
    {
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'header') . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesTable
     * @param string $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @return string
     */
    private function getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $single = $this->sc->getSmartySingleVar('table_type');
        $table  = $this->getTemplatesAdminPagesTableThead($tableName, $language);
        $table  .= $this->getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $language);

        return $this->hc->getHtmlTable($table, 'table table-' . $single) . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesThead
     * @param string $language
     * @param        $tableName
     * @return string
     */
    private function getTemplatesUserCategoriesThead($tableName, $language)
    {
        $stuTableName = \mb_strtoupper($tableName);
        $lang         = $this->sc->getSmartyConst($language, $stuTableName . '_TITLE');
        $single       = $this->sc->getSmartySingleVar('numb_col');
        $th           = $this->hc->getHtmlTableHead($lang, '', $single) . PHP_EOL;
        $tr           = $this->hc->getHtmlTableRow($th, 'head') . PHP_EOL;

        return $this->hc->getHtmlTableThead($tr) . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesTbody
     * @param string $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @return string
     */
    private function getTemplatesUserCategoriesTbody($moduleDirname, $tableName, $tableSoleName)
    {
        $single  = $this->sc->getSmartySingleVar('panel_type');
        $include = $this->sc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName);
        $div     = $this->hc->getHtmlDiv($include, 'panel panel-' . $single);
        $cont    = $this->hc->getHtmlTableData($div) . PHP_EOL;
        $html    = $this->hc->getHtmlEmpty('</tr><tr>') . PHP_EOL;
        $cont    .= $this->sc->getSmartyConditions($tableSoleName . '.count', ' is div by ', '$divideby', $html, false, false, false, '', "\n", true, false) . PHP_EOL;
        $foreach = $this->sc->getSmartyForeach($tableSoleName, $tableName, $cont) . PHP_EOL;
        $tr      = $this->hc->getHtmlTableRow($foreach) . PHP_EOL;

        return $this->hc->getHtmlTableTbody($tr) . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesTfoot
     * @return string
     */
    private function getTemplatesUserCategoriesTfoot()
    {
        $td = $this->hc->getHtmlTableData('&nbsp;') . PHP_EOL;
        $tr = $this->hc->getHtmlTableRow($td) . PHP_EOL;

        return $this->hc->getHtmlTableTfoot($tr) . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategories
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategories($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $tab = $this->getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSoleName, $language) . PHP_EOL;
        $div = $this->hc->getHtmlDiv($tab, 'table-responsive') . PHP_EOL;

        return $this->sc->getSmartyConditions($tableName, ' gt ', '0', $div, false, true, false, '', "\n", true, 'int') . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesPanel
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $language
     * @return string
     */
    private function getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $stuTableName = \mb_strtoupper($tableName);
        $incl         = $this->sc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName) . PHP_EOL;
        $html         = $this->hc->getHtmlEmpty('<br>') . PHP_EOL;
        $incl         .= $this->sc->getSmartyConditions($tableSoleName . '.count', ' is div by ', '$numb_col', $html, false, false, false, '', "\n", true, false) . PHP_EOL;
        $const        = $this->sc->getSmartyConst($language, $stuTableName . '_TITLE');
        $div          = $this->hc->getHtmlDiv($const, 'panel-heading') . PHP_EOL;
        $cont         = $this->hc->getHtmlDiv($incl, 'panel panel-body') . PHP_EOL;
        $div          .= $this->sc->getSmartyForeach($tableSoleName, $tableName, $cont) . PHP_EOL;
        $panelType    = $this->sc->getSmartySingleVar('panel_type');

        return $this->hc->getHtmlDiv($div, 'panel panel-' . $panelType) . PHP_EOL;
    }

    /**
     * @private function getTemplatesUserCategoriesFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserCategoriesFooter($moduleDirname)
    {
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     * @public function render
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
        $content       = $this->getTemplatesUserCategoriesHeader($moduleDirname);
        $content       .= $this->getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSoleName, $language);
        $content       .= $this->getTemplatesUserCategoriesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
