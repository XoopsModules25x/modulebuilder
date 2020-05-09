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
 * class Index.
 */
class Index extends Files\CreateFile
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
    public function write($module, $table, $tables, $filename)
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
        $sc  = Modulebuilder\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'header', false, false, '',  "\n");
    }

    /**
     * @public function getTemplatesUserIndexBodyDefault
     * @param $module
     * @param $language
     * @return bool|string
     */
    public function getTemplatesUserIndexIntro($module, $language)
    {
        $hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc = Modulebuilder\Files\CreateSmartyCode::getInstance();

        $moduleDirname = $module->getVar('mod_dirname');
        $ret           = $hc->getHtmlEmpty('','',"\n");
        $ret           .= $hc->getHtmlComment('Start index list','', "\n");
        //Table head
        $th        = $hc->getHtmlTableHead("<{\$smarty.const.{$language}TITLE}>  -  <{\$smarty.const.{$language}DESC}>", '', '',"\t\t\t");
        $tr        = $hc->getHtmlTableRow($th,'center',"\t\t");
        $thead     = $hc->getHtmlTableThead($tr,'', "\t");
        $contTable = $thead;
        //Table body
        $li     = $hc->getHtmlLi("<a href='<{\${$moduleDirname}_url}>'><{\$smarty.const.{$language}INDEX}></a>",'',"\t\t\t\t\t");
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        foreach (array_keys($tables) as $i) {
            if (1 == $tables[$i]->getVar('table_index')) {
                $tableNameLi = $tables[$i]->getVar('table_name');
                $tableName = $tables[$i]->getVar('table_name');
                $stuTableNameLi = mb_strtoupper($tableName);
                $li .= $hc->getHtmlLi("<a href='<{\${$moduleDirname}_url}>/{$tableNameLi}.php'><{\$smarty.const.{$language}{$stuTableNameLi}}></a>", '', "\t\t\t\t\t");
            }
        }
        $ul        = $hc->getHtmlUl($li,'menu text-center',"\t\t\t\t");
        $td        = $hc->getHtmlTableData($ul, 'bold pad5','',"\t\t\t", "\n", true);
        $tr        = $hc->getHtmlTablerow($td, 'center',"\t\t");
        $tbody     = $hc->getHtmlTableTbody($tr,'', "\t");
        $contTable .= $tbody;
        //Table foot
        $single    = $sc->getSmartySingleVar('adv');
        $cond      = $sc->getSmartyConditions('adv','','', $single, false, '','', "\t\t\t\t", "\n", false);
        $td        = $hc->getHtmlTableData($cond, 'bold pad5','',"\t\t\t", "\n", true);
        $tr        = $hc->getHtmlTablerow($td, 'center',"\t\t");
        $tfoot     = $hc->getHtmlTableTfoot($tr,'', "\t");
        $contTable .= $tfoot;

        $ret .= $hc->getHtmlTable($contTable);
        $ret .= $hc->getHtmlComment('End index list','', "\n");
        $ret .= $hc->getHtmlEmpty('','',"\n");

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
        $hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc = Modulebuilder\Files\CreateSmartyCode::getInstance();

        $double  = $sc->getSmartyConst($language, 'INDEX_LATEST_LIST');
        $ret     = $hc->getHtmlDiv($double, "{$moduleDirname}-linetitle", $t, "\n", false);
        $table   = $hc->getHtmlComment("Start show new {$tableName} in index",$t . "\t", "\n");
        $include = $sc->getSmartyIncludeFileListSection($moduleDirname, $tableName, $tableSoleName, $tableName, $t . "\t\t\t\t\t", "\n");
        $td      = $hc->getHtmlTableData($include, "col_width<{\$numb_col}> top center", '', $t . "\t\t\t\t", "\n", true);
        $tr      = $hc->getHtmlEmpty('</tr><tr>', $t . "\t\t\t\t\t", "\n");
        $td      .= $sc->getSmartyConditions($tableName . '[i].count', ' is div by ', '$divideby', $tr, false, false, false, $t . "\t\t\t\t");
        $section = $hc->getHtmlComment('Start new link loop',$t . "\t\t\t", "\n");
        $section .= $sc->getSmartySection('i', $tableName, $td, '', '', $t . "\t\t\t");
        $section .= $hc->getHtmlComment('End new link loop',$t . "\t\t\t", "\n");
        $tr      = $hc->getHtmlTableRow($section, '',$t . "\t\t");
        $table   .= $hc->getHtmlTable($tr, 'table table-<{$table_type}>', $t . "\t");
        $ret     .= $sc->getSmartyConditions($tableName . 'Count', ' > ','0',$table);

        return $ret;
    }

    /**
     * @public function getTemplateUserIndexFooter
     * @param $moduleDirname
     * @return bool|string
     */
    public function getTemplateUserIndexFooter($moduleDirname)
    {
        $sc  = Modulebuilder\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'footer');
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
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplateUserIndexHeader($moduleDirname);
        $content       .= $this->getTemplatesUserIndexIntro($module, $language);
        foreach (array_keys($tables) as $t) {
            $tableName      = $tables[$t]->getVar('table_name');
            $tableSoleName  = $tables[$t]->getVar('table_solename');
            $tableIndex     = $tables[$t]->getVar('table_index');
            if (1 == $tableIndex) {
                $content .= $this->getTemplateUserIndexTable($moduleDirname, $tableName, $tableSoleName, $language);
            }
        }
        $content  .= $this->getTemplateUserIndexFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
