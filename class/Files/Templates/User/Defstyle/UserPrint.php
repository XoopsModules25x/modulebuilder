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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */

/**
 * class UserPrint.
 */
class UserPrint extends Files\CreateFile
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
     * @return UserPrint
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
     * @private function getTemplatesUserPrintHeader
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesUserPrintHeader($moduleDirname)
    {
        $ret = $this->hc->getHtmlComment('Header', '',"\n");
        $ret .= $this->sc->getSmartyIncludeFile($moduleDirname, 'header', false, '', '', "\n\n");

        return $ret;
    }

    /**
     * @private  function getTemplatesUserPrintTableThead
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param array $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesUserPrintTableThead($tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $th         = '';
        $langHeadId = \mb_strtoupper($tableSoleName) . '_ID';
        if (1 == $tableAutoincrement) {
            $lang = $this->sc->getSmartyConst($language, $langHeadId);
            $th   .= $this->hc->getHtmlTag('th', ['class' => 'center'], $lang, false, "\t\t\t");
        }
        foreach (\array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
            $rpFieldName   = $this->getRightString($fieldName);
            $langFieldName = \mb_strtoupper($tableSoleName) . '_' . \mb_strtoupper($rpFieldName);
            if (1 == $fields[$f]->getVar('field_user')) {
                $lang = $this->sc->getSmartyConst($language, $langFieldName);
                $th   .= $this->hc->getHtmlTag('th', ['class' => 'center'], $lang, false, "\t\t\t");
            }
        }

        $tr   = $this->hc->getHtmlTableRow($th, 'head', "\t\t");
        $ret  = $this->hc->getHtmlTableThead($tr, '', "\t");

        return $ret;
    }

    /**
     * @private  function getTemplatesUserPrintTableTBody
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param array $fields
     * @return string
     * @internal param string $language
     */
    private function getTemplatesUserPrintTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields)
    {
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $this->sc->getSmartyDoubleVar($tableSoleName, 'id');
            $td     .= $this->hc->getHtmlTableData($double, 'center', '',"\t\t\t");
        }
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->getRightString($fieldName);
            if (1 == $fields[$f]->getVar('field_user')) {
                switch ($fieldElement) {
                    case 3:
                    case 4:
                        $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName . '_short');
                        $td     .= $this->hc->getHtmlTableData($double, 'center', '',"\t\t\t");
                        break;
                    case 5:
                        $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $src    = $this->sc->getSmartyNoSimbol('xoModuleIcons16') . $double . '.png';
                        $img    = $this->hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'','');
                        $td     .= $this->hc->getHtmlTableData($img, 'center', '',"\t\t\t");
                        break;
                    case 9:
                        // This is to be reviewed, as it was initially to style = "backgroung-color: #"
                        // Now with HTML5 is not supported inline style in the parameters of the HTML tag
                        // Old code was <span style="background-color: #<{\$list.{$rpFieldName}}>;">...
                        $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $color  = "<span style='background-color:{$double};'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                        $td     .= $this->hc->getHtmlTableData($color, 'center', '',"\t\t\t");
                        break;
                    case 10:
                        $src = $this->sc->getSmartyNoSimbol('xoModuleIcons32');
                        $src .= $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $this->hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'','');
                        $td  .= $this->hc->getHtmlTableData($img, 'center', '',"\t\t\t");
                        break;
                    case 13:
                        $single = $this->sc->getSmartySingleVar($moduleDirname . '_upload_url');
                        $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img    = $this->hc->getHtmlTag('img', ['src' => $single . "/images/{$tableName}/" . $double, 'alt' => $tableName, 'style' => 'max-width:100px'], '', true, '', '');
                        $td     .= $this->hc->getHtmlTableData($img, 'center', '',"\t\t\t");
                        break;
                    case 16:
                        $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $src    = $this->sc->getSmartyNoSimbol('$modPathIcon16') . 'status' . $double . '.png';
                        $imgAlt = $this->sc->getSmartyDoubleVar($tableSoleName, 'status_text');
                        $img    = $this->hc->getHtmlTag('img', ['src' => $src, 'alt' => $imgAlt, 'title' => $imgAlt], '', true,'','');
                        $td     .= $this->hc->getHtmlTableData($img, 'center', '',"\t\t\t");
                        break;
                    default:
                        if (0 != $f) {
                            $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $td     .= $this->hc->getHtmlTableData($double, 'center', '',"\t\t\t");
                        }
                        break;
                }
            }
        }
        $cycle   = $this->sc->getSmartyNoSimbol('cycle values=\'odd, even\'');
        $tr      = $this->hc->getHtmlTableRow($td, $cycle, "\t\t");
        $foreach = $this->sc->getSmartyForeach($tableSoleName, $tableName . '_list', $tr, '','', "\t\t");
        $tbody   = $this->hc->getHtmlTableTbody($foreach,'' , "\t");

        return $tbody;
    }

    /**
     * @private function getTemplatesUserPrintTable
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param        $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesUserPrintTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $tbody = $this->getTemplatesUserPrintTableThead($tableSoleName, $tableAutoincrement, $fields, $language);
        $tbody .= $this->getTemplatesUserPrintTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields);

        return $this->hc->getHtmlTable($tbody, 'table table-bordered', '');
    }

    /**
     * @private function getTemplatesUserPrint
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param        $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesUserPrint($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $htmlTable = $this->getTemplatesUserPrintTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language);

        return $htmlTable;
    }

    /**
     * @private function getTemplatesUserPrintFooter
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesUserPrintFooter($moduleDirname)
    {
        $ret = $this->hc->getHtmlComment('Footer', '', "\n");
        $ret .= $this->sc->getSmartyIncludeFile($moduleDirname, 'footer', false);

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
        $language      = $this->getLanguage($moduleDirname, 'MA', '', false);
        $fields        = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order');
        $content       = $this->getTemplatesUserPrintHeader($moduleDirname);
        $content       .= $this->getTemplatesUserPrint($moduleDirname, $table->getVar('table_name'), $table->getVar('table_solename'), $table->getVar('table_autoincrement'), $fields, $language);
        $content       .= $this->getTemplatesUserPrintFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
