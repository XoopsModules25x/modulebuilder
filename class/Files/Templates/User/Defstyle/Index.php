<?php declare(strict_types=1);

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 */

/**
 * class Index.
 */
class Index extends Files\CreateFile
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
     * @return Index
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
     * @param $module
     * @param $table
     * @param $tables
     * @param $filename
     */
    public function write($module, $table, $tables, $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @public function getTemplateUserIndexHeader
     * @param $moduleDirname
     * @return bool|string
     */
    public function getTemplateUserIndexHeader($moduleDirname)
    {
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'header', false);
    }

    /**
     * @public function getTemplatesUserIndexBodyDefault
     * @param $module
     * @param $language
     * @return bool|string
     */
    public function getTemplatesUserIndexIntro($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $ret           = $this->hc->getHtmlEmpty('','',"\n");
        $ret           .= $this->hc->getHtmlComment('Start index list','', "\n");
        //Table head
        $th        = $this->hc->getHtmlTableHead("<{\$smarty.const.{$language}TITLE}>  -  <{\$smarty.const.{$language}DESC}>", '', '',"\t\t\t");
        $tr        = $this->hc->getHtmlTableRow($th,'center',"\t\t");
        $thead     = $this->hc->getHtmlTableThead($tr,'', "\t");
        $contTable = $thead;
        //Table body
        $li     = $this->hc->getHtmlLi("<a href='<{\${$moduleDirname}_url}>'><{\$smarty.const.{$language}INDEX}></a>",'',"\t\t\t\t\t");
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        foreach (\array_keys($tables) as $i) {
            if (1 == $tables[$i]->getVar('table_index')) {
                $tableNameLi = $tables[$i]->getVar('table_name');
                $tableName = $tables[$i]->getVar('table_name');
                $stuTableNameLi = \mb_strtoupper($tableName);
                $li .= $this->hc->getHtmlLi("<a href='<{\${$moduleDirname}_url}>/{$tableNameLi}.php'><{\$smarty.const.{$language}{$stuTableNameLi}}></a>", '', "\t\t\t\t\t");
            }
        }
        $ul        = $this->hc->getHtmlUl($li,'menu text-center',"\t\t\t\t");
        $td        = $this->hc->getHtmlTableData($ul, 'bold pad5','',"\t\t\t", "\n", true);
        $tr        = $this->hc->getHtmlTablerow($td, 'center',"\t\t");
        $tbody     = $this->hc->getHtmlTableTbody($tr,'', "\t");
        $contTable .= $tbody;
        //Table foot
        $single    = $this->sc->getSmartySingleVar('adv');
        $cond      = $this->sc->getSmartyConditions('adv','','', $single, false, '','', "\t\t\t\t", "\n", false);
        $td        = $this->hc->getHtmlTableData($cond, 'bold pad5','',"\t\t\t", "\n", true);
        $tr        = $this->hc->getHtmlTablerow($td, 'center',"\t\t");
        $tfoot     = $this->hc->getHtmlTableTfoot($tr,'', "\t");
        $contTable .= $tfoot;

        $ret .= $this->hc->getHtmlTable($contTable);
        $ret .= $this->hc->getHtmlComment('End index list','', "\n");
        $ret .= $this->hc->getHtmlEmpty('','',"\n");

        return $ret;
    }

    /**
     * @public function getTemplateUserIndexTable
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @param string $t
     * @return bool|string
     */
    public function getTemplateUserIndexTable($moduleDirname, $tableName, $tableSoleName, $language, $t = '')
    {
        $double  = $this->sc->getSmartyConst($language, 'INDEX_LATEST_LIST');
        $ret     = $this->hc->getHtmlDiv($double, "{$moduleDirname}-linetitle", $t, "\n", false);
        $table   = $this->hc->getHtmlComment("Start show new {$tableName} in index",$t . "\t", "\n");
        $include = $this->sc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName, $t . "\t\t\t\t\t", "\n");
        $td      = $this->hc->getHtmlTableData($include, 'col_width<{$numb_col}> top center', '', $t . "\t\t\t\t", "\n", true);
        $trClose = $this->hc->getHtmlEmpty('</tr><tr>', $t . "\t\t\t\t\t", "\n");
        $td      .= $this->sc->getSmartyConditions('smarty.foreach.' . $tableSoleName . '.iteration', ' is div by ', '$divideby', $trClose, false, false, false, $t . "\t\t\t\t", "\n", true, false);
        $foreach = $this->hc->getHtmlComment('Start new link loop',$t . "\t\t\t", "\n");
        $foreach .= $this->sc->getSmartyForeach($tableSoleName, $tableName . '_list', $td, $tableSoleName,'',"\t\t\t");
        $foreach .= $this->hc->getHtmlComment('End new link loop',$t . "\t\t\t", "\n");
        $tr      = $this->hc->getHtmlTableRow($foreach, '',$t . "\t\t");

        $table   .= $this->hc->getHtmlTable($tr, 'table table-<{$table_type}>', $t . "\t");
        $ret     .= $this->sc->getSmartyConditions($tableName . 'Count', ' > ','0', $table, false, false, false, '', "\n", true, 'int');

        return $ret;
    }

    /**
     * @public function getTemplateUserIndexFooter
     * @param $moduleDirname
     * @return bool|string
     */
    public function getTemplateUserIndexFooter($moduleDirname)
    {
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $tables        = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA', '', false);
        $content       = $this->getTemplateUserIndexHeader($moduleDirname);
        $content       .= $this->getTemplatesUserIndexIntro($module, $language);
        foreach (\array_keys($tables) as $t) {
            $tableName      = $tables[$t]->getVar('table_name');
            $tableSoleName  = $tables[$t]->getVar('table_solename');
            $tableIndex     = $tables[$t]->getVar('table_index');
            if (1 == $tableIndex) {
                $content .= $this->getTemplateUserIndexTable($moduleDirname, $tableName, $tableSoleName, $language);
            }
        }
        $content  .= $this->getTemplateUserIndexFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
