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
 * Class TemplatesAdminPages.
 */
class TemplatesAdminPages extends Files\CreateFile
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
     * @return TemplatesAdminPages
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
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesAdminPagesHeader
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesAdminPagesHeader($moduleDirname)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret = $hc->getHtmlComment('Header', '',"\n");
        $ret .= $sc->getSmartyIncludeFile($moduleDirname, 'header', true, '', '', "\n\n");

        return $ret;
    }

    /**
     * @private  function getTemplatesAdminPagesTableThead
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminPagesTableThead($tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc         = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc         = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $th         = '';
        $langHeadId = mb_strtoupper($tableSoleName) . '_ID';
        if (1 == $tableAutoincrement) {
            $lang = $sc->getSmartyConst($language, $langHeadId);
            $th   .= $hc->getHtmlTag('th', ['class' => 'center'], $lang, false, "\t\t\t\t");
        }
        foreach (array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
            $rpFieldName   = $this->getRightString($fieldName);
            $langFieldName = mb_strtoupper($tableSoleName) . '_' . mb_strtoupper($rpFieldName);
            if (1 == $fields[$f]->getVar('field_inlist')) {
                $lang = $sc->getSmartyConst($language, $langFieldName);
                $th   .= $hc->getHtmlTag('th', ['class' => 'center'], $lang, false, "\t\t\t\t");
            }
        }

        $lang = $sc->getSmartyConst($language, 'FORM_ACTION');
        $th   .= $hc->getHtmlTag('th', ['class' => 'center width5'], $lang, false, "\t\t\t\t");
        $tr   = $hc->getHtmlTableRow($th, 'head', "\t\t\t");
        $ret  = $hc->getHtmlTableThead($tr, '', "\t\t");

        return $ret;
    }

    /**
     * @private  function getTemplatesAdminPagesTableTBody
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @return string
     * @internal param string $language
     */
    private function getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $sc->getSmartyDoubleVar($tableSoleName, 'id');
            $td     .= $hc->getHtmlTableData($double, 'center', '',"\t\t\t\t");
        }
        $fieldId = '';
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->getRightString($fieldName);
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_inlist')) {
                switch ($fieldElement) {
                    case 3:
                    case 4:
                        $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName . '_short');
                        $td     .= $hc->getHtmlTableData($double, 'center', '',"\t\t\t\t");
                        break;
                    case 5:
                        $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $src    = $sc->getSmartyNoSimbol('xoModuleIcons16') . $double . '.png';
                        $img    = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'','');
                        $td     .= $hc->getHtmlTableData($img, 'center', '',"\t\t\t\t");
                        break;
                    case 9:
                        // This is to be reviewed, as it was initially to style = "backgroung-color: #"
                        // Now with HTML5 is not supported inline style in the parameters of the HTML tag
                        // Old code was <span style="background-color: #<{\$list.{$rpFieldName}}>;">...
                        $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $color  = "<span style='background-color:{$double};'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                        $td     .= $hc->getHtmlTableData($color, 'center', '',"\t\t\t\t");
                        break;
                    case 10:
                        $src = $sc->getSmartyNoSimbol('xoModuleIcons32');
                        $src .= $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'','');
                        $td  .= $hc->getHtmlTableData($img, 'center', '',"\t\t\t\t");
                        break;
                    case 13:
                        $single = $sc->getSmartySingleVar($moduleDirname . '_upload_url');
                        $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img    = $hc->getHtmlTag('img', ['src' => $single . "/images/{$tableName}/" . $double, 'alt' => $tableName, 'style' => 'max-width:100px'], '', true, '', '');
                        $td     .= $hc->getHtmlTableData($img, 'center', '',"\t\t\t\t");
                        break;
                    case 16:
                        $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $src = $sc->getSmartyNoSimbol('$modPathIcon16') . '/status' . $double . '.png';
                        $img = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'','');
                        $td     .= $hc->getHtmlTableData($img, 'center', '',"\t\t\t\t");
                        break;
                    default:
                        if (0 != $f) {
                            $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $td     .= $hc->getHtmlTableData($double, 'center', '',"\t\t\t\t");
                        }
                        break;
                }
            }
        }
        $lang    = $sc->getSmartyConst('', '_EDIT');
        $double  = $sc->getSmartyDoubleVar($tableSoleName, 'id');
        $src     = $sc->getSmartyNoSimbol('xoModuleIcons16 edit.png');
        $img     = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true,'', '');
        $anchor  = $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=edit&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t\t");
        $lang    = $sc->getSmartyConst('', '_DELETE');
        $double  = $sc->getSmartyDoubleVar($tableSoleName, 'id');
        $src     = $sc->getSmartyNoSimbol('xoModuleIcons16 delete.png');
        $img     = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true, '', '');
        $anchor  .= $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=delete&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t\t");
        $td      .= $hc->getHtmlTag('td', ['class' => 'center  width5'], "\n" . $anchor . "\t\t\t\t", false, "\t\t\t\t");
        $cycle   = $sc->getSmartyNoSimbol('cycle values=\'odd, even\'');
        $tr      = $hc->getHtmlTableRow($td, $cycle, "\t\t\t");
        $foreach = $sc->getSmartyForeach($tableSoleName, $tableName . '_list', $tr, '','', "\t\t\t");
        $tbody   = $hc->getHtmlTableTbody($foreach,'' , "\t\t");

        return $sc->getSmartyConditions($tableName . '_count', '', '', $tbody, '', false, false, "\t\t");
    }

    /**
     * @private function getTemplatesAdminPagesTable
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminPagesTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $tbody = $this->getTemplatesAdminPagesTableThead($tableSoleName, $tableAutoincrement, $fields, $language);
        $tbody .= $this->getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields);

        return $hc->getHtmlTable($tbody, 'table table-bordered', "\t");
    }

    /**
     * @private function getTemplatesAdminPages
     * @param string $moduleDirname
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $fields
     * @param string $language
     * @return string
     */
    private function getTemplatesAdminPages($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc        = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc        = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $htmlTable = $this->getTemplatesAdminPagesTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language);
        $htmlTable .= $hc->getHtmlTag('div', ['class' => 'clear'], '&nbsp;', false, "\t");
        $single    = $sc->getSmartySingleVar('pagenav');
        $div       = $hc->getHtmlTag('div', ['class' => 'xo-pagenav floatright'], $single, false, "\t\t");
        $div       .= $hc->getHtmlTag('div', ['class' => 'clear spacer'], '', false, "\t\t", "\n");
        $htmlTable .= $sc->getSmartyConditions('pagenav', '', '', $div, '', '', '', "\t" );
        $ifList    = $sc->getSmartyConditions($tableName . '_list', '', '', $htmlTable);
        $single    = $sc->getSmartySingleVar('form', "\t", "\n");
        $ifList    .= $sc->getSmartyConditions('form', '', '', $single);
        $single    = $sc->getSmartySingleVar('error');
        $strong    = $hc->getHtmlTag('strong', [], $single, false, '', '');
        $div       = $hc->getHtmlTag('div', ['class' => 'errorMsg'], $strong, false, "\t", "\n");
        $ifList    .= $sc->getSmartyConditions('error', '', '', $div);
        $ifList    .= $hc->getHtmlEmpty('', '', "\n");;

        return $ifList;
    }

    /**
     * @private function getTemplatesAdminPagesFooter
     * @param string $moduleDirname
     * @return string
     */
    private function getTemplatesAdminPagesFooter($moduleDirname)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $ret = $hc->getHtmlComment('Footer', '', "\n");
        $ret .= $sc->getSmartyIncludeFile($moduleDirname, 'footer', true);

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
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $fields        = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order');
        $content       = $this->getTemplatesAdminPagesHeader($moduleDirname);
        $content       .= $this->getTemplatesAdminPages($moduleDirname, $table->getVar('table_name'), $table->getVar('table_solename'), $table->getVar('table_autoincrement'), $fields, $language);
        $content       .= $this->getTemplatesAdminPagesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
