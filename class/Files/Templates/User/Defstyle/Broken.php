<?php

namespace XoopsModules\Tdmcreate\Files\Templates\User\Defstyle;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;
use XoopsModules\Tdmcreate\Files\Templates\User;

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
 * class Broken.
 */
class Broken extends Files\CreateFile
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
     * @return User\Broken
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
     * @private function getTemplatesUserBrokenFileHeader
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserBrokenFileHeader($moduleDirname)
    {
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'header');
    }

    /**
     * @private function getTemplatesUserBrokenTableHead
     * @param $tableMid
     * @param $tableId
     * @param $tableAutoincrement
     * @param $language
     * @return string
     */
    private function getTemplatesUserBrokenTableHead($tableMid, $tableId, $tableAutoincrement, $language)
    {
        $hc     = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc     = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $th     = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $stuFieldName = mb_strtoupper($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                $const = $sc->getSmartyConst($language, $stuFieldName);
                $th    .= $hc->getHtmlTag('th', ['class' => 'center'], $const, '', "\t\t\t");
            }
        }
        $tr = $hc->getHtmlTableRow($th, 'head',"\t\t");

        return $hc->getHtmlTableThead($tr, 'outer', "\t");
    }

    /**
     * @private function getTemplatesUserBrokenBody
     * @param $moduleDirname
     * @param $tableMid
     * @param $tableId
     * @param $tableName
     * @param $tableSoleName
     * @param $tableAutoincrement
     * @return string
     */
    private function getTemplatesUserBrokenBody($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableAutoincrement)
    {
        $hc     = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc     = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret    = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->getRightString($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                switch ($fieldElement) {
                    case 9:
                        $span = $hc->getHtmlSpan("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSoleName}.{$rpFieldName}}>", "#<{\${$tableSoleName}.{$rpFieldName}}>");
                        $ret  .= $hc->getHtmlTableData($span, 'center', '', "\t\t\t");
                        break;
                    case 10:
                        $img = $hc->getHtmlImage("<{xoModuleIcons32}><{\${$tableSoleName}.{$rpFieldName}}>", $tableName);
                        $ret .= $hc->getHtmlTableData($img, 'center', '', "\t\t\t");
                        break;
                    case 13:
                        $img = $hc->getHtmlImage("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSoleName}.{$rpFieldName}}>", $tableName);
                        $ret .= $hc->getHtmlTableData($img, 'center', '', "\t\t\t") ;
                        break;
                    default:
                        $ret .= $hc->getHtmlTableData("<{\${$tableSoleName}.{$rpFieldName}}>", 'center', '', "\t\t\t") ;
                        break;
                }
            }
        }
        $row     = $hc->getHtmlTableRow($ret, '<{cycle values="odd, even"}>',"\t\t") ;
        $foreach = $sc->getSmartyForeach($tableSoleName, $tableName, $row, '', '', "\t\t");

        return $hc->getHtmlTableTbody($foreach, '', "\t");
    }

    /**
     * @private function getTemplatesUserBrokenFileFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserBrokenFileFooter($moduleDirname)
    {
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();

        return $sc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     * @public function render
     * @return bool|string
     */
    public function render()
    {
        $hc                 = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getTemplatesUserBrokenFileHeader($moduleDirname);
        $contentTable       = $this->getTemplatesUserBrokenTableHead($tableMid, $tableId, $tableAutoincrement, $language);
        $contentTable       .= $this->getTemplatesUserBrokenBody($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableAutoincrement);
        $content            .= $hc->getHtmlTable($contentTable, 'table table-bordered');
        $content            .= $this->getTemplatesUserBrokenFileFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
