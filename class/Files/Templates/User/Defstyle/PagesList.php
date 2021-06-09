<?php

namespace XoopsModules\Modulebuilder\Files\Templates\User\Defstyle;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\{
    Files,
    Constants
};

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
 * class PagesList.
 */
class PagesList extends Files\CreateFile
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
     * @return PagesList
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
     * @param        $tables
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesUserPagesListPanel
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $language
     * @return string
     */
    private function getTemplatesUserPagesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $fields  = $this->getTableFields($tableMid, $tableId);
        $ret     = '';
        $retNumb = '';
        foreach (\array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_ihead')) {
                    switch ($fieldElement) {
                        default:
                        //case 2:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retNumb     .= $this->hc->getHtmlHNumb($doubleVar, '3', 'panel-title', "\t");
                            break;
                    }
                }
            }
        }
        $ret     .= $this->hc->getHtmlDiv($retNumb, 'panel-heading');
        $retElem   = '';
        $fieldId   = '';
        $keyDouble = '';
        foreach (\array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
                $keyDouble = $this->sc->getSmartyDoubleVar($tableSoleName, $fieldId);
            }
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_ibody')) {
                    switch ($fieldElement) {
                        default:
                        //case 3:
                        //case 4:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem     .= $this->hc->getHtmlSpan($doubleVar, 'col-sm-9 justify', "\t");
                            break;
                        case Constants::FIELD_ELE_IMAGELIST:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar   = $this->sc->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $this->hc->getHtmlImage($singleVar . '/' . $doubleVar, (string)$tableName);
                            $retElem     .= $this->hc->getHtmlSpan($img, 'col-sm-3', "\t");
                            unset($img);
                            break;
                        case Constants::FIELD_ELE_UPLOADIMAGE:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar   = $this->sc->getSmartySingleVar($moduleDirname . '_upload_url');
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $this->hc->getHtmlImage($singleVar . "/images/{$tableName}/" . $doubleVar, (string)$tableName);
                            $retElem     .= $this->hc->getHtmlSpan($img, 'col-sm-3',"\t");
                            unset($img);
                            break;
                    }
                }
            }
        }


        $ret     .= $this->hc->getHtmlDiv($retElem, 'panel-body');
        $retFoot = '';
        foreach (\array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_ifoot')) {
                    $fieldName   = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $langConst   = \mb_strtoupper($tableSoleName) . '_' . \mb_strtoupper($rpFieldName);
                    $lang        = $this->sc->getSmartyConst($language, $langConst);
                    $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                    $retFoot     .= $this->hc->getHtmlSpan($lang . ': ' . $doubleVar, 'block-pie justify',"\t");
                }
            }
        }
        $lang        = $this->sc->getSmartyConst($language, 'DETAILS');
        $anchor =  $this->hc->getHtmlAnchor($tableName . ".php?op=show&amp;{$fieldId}=" . $keyDouble, $lang, $lang, '', 'btn btn-primary', '', '', '');
        $retFoot     .= $this->hc->getHtmlSpan($anchor, 'col-sm-12',"\t");
        $ret .= $this->hc->getHtmlDiv($retFoot, 'panel-foot');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table  = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $filename      = $this->getFileName();
        $language      = $this->getLanguage($moduleDirname, 'MA', '', false);
        $content       = '';
        $tableId         = $table->getVar('table_id');
        $tableMid        = $table->getVar('table_mid');
        $tableName       = $table->getVar('table_name');
        $tableSoleName   = $table->getVar('table_solename');
        $tableCategory[] = $table->getVar('table_category');
        if (\in_array(0, $tableCategory)) {
            $content .= $this->getTemplatesUserPagesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
        }

        $this->create($moduleDirname, 'templates', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
