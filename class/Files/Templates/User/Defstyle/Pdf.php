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
 * class Pdf.
 */
class Pdf extends Files\CreateFile
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
     * @return Pdf
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
     *
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
        $this->setTable($table);
    }

    /**
     * @private function getTemplatesUserPagesItemPanel
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $tableRate
     * @param        $tableBroken
     * @param        $language
     * @return string
     */
    private function getTemplatesUserPdfBody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableRate, $tableBroken, $language)
    {
        $fields  = $this->getTableFields($tableMid, $tableId);
        $ret     = '';
        $content_header = $this->sc->getSmartySingleVar('content_header');
        $ret     .= $this->hc->getHtmlDiv($content_header, 'panel-heading', '',"\n", false);
        $retElem = '';
        foreach (\array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    $fieldName   = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $langConst   = \mb_strtoupper($tableSoleName) . '_' . \mb_strtoupper($rpFieldName);
                    $lang        = $this->sc->getSmartyConst($language, $langConst);
                    $retElem     .= $this->hc->getHtmlDiv($lang . ': ' , 'col-sm-3',"\t", "\n", false);
                    switch ($fieldElement) {
                        default:
                            //case 3:
                            //case 4:
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem     .= $this->hc->getHtmlDiv($doubleVar, 'col-sm-8', "\t", "\n", false);
                            break;
                        case 10:
                            $singleVar   = $this->sc->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $this->hc->getHtmlImage($singleVar . '/' . $doubleVar, (string)$tableName);
                            $retElem     .= $this->hc->getHtmlDiv($img, 'col-sm-8', "\t", "\n", false);
                            unset($img);
                            break;
                        case 13:
                            $singleVar   = $this->sc->getSmartySingleVar($moduleDirname . '_upload_url');
                            $doubleVar   = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $this->hc->getHtmlImage($singleVar . "/images/{$tableName}/" . $doubleVar, (string)$tableName);
                            $retElem     .= $this->hc->getHtmlDiv($img, 'col-sm-9',"\t", "\n", false);
                            unset($img);
                            break;
                    }
                }
            }
        }
        $ret .= $this->hc->getHtmlDiv($retElem, 'panel-body');

        return $ret;
    }

    /**
     * @public   function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $table  = $this->getTable();
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $tableId         = $table->getVar('table_id');
        $tableMid        = $table->getVar('table_mid');
        $tableName       = $table->getVar('table_name');
        $tableSoleName   = $table->getVar('table_solename');
        $content       = $this->getTemplatesUserPdfBody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableRate, $tableBroken, $language);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
