<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Templates\Blocks\Defstyle;

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */

/**
 * Class TemplatesBlocksSpotlight.
 */
class TemplatesBlocksSpotlight extends Files\CreateFile
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
     * @return false|TemplatesBlocks|TemplatesBlocksSpotlight
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
     * @private  function getTemplatesBlocksTableThead
     * @param        $tableId
     * @param        $tableMid
     * @param string $language
     * @param $tableAutoincrement
     * @return string
     */
    private function getTemplatesBlocksTableThead($tableId, $tableMid, $language, $tableAutoincrement)
    {
        $th     = '';
		if (1 == $tableAutoincrement) {
            $th .= $this->hc->getHtmlTableHead('&nbsp;', '', '', "\t\t\t");
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (\array_keys($fields) as $f) {
            if (1 === (int)$fields[$f]->getVar('field_block')) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $stuFieldName = \mb_strtoupper($fieldName);
                $lang         = $this->sc->getSmartyConst($language, $stuFieldName);
                $th           .= $this->hc->getHtmlTableHead($lang, 'center', '', "\t\t\t");
            }
        }
        $tr = $this->hc->getHtmlTableRow($th, 'head', "\t\t");

        return $this->hc->getHtmlTableThead($tr, '', "\t");
    }

    /**
     * @private  function getTemplatesBlocksTableTbody
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param        $language
     * @return string
     */
    private function getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $td = '';
        $fieldId = '';
        $stuTableSoleName = \mb_strtoupper($tableSoleName);
        if (1 == $tableAutoincrement) {
            $double = $this->sc->getSmartyDoubleVar($tableSoleName, 'id');
            $td     .= $this->hc->getHtmlTableData($double, 'center',  '', "\t\t\t");
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (\array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
            if (1 === (int)$fields[$f]->getVar('field_block')) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $rpFieldName  = $this->getRightString($fieldName);
                if (1 == $fields[$f]->getVar('field_inlist')) {
                    switch ($fieldElement) {
                        case Constants::FIELD_ELE_COLORPICKER:
                            $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $span   = $this->hc->getHtmlTag('span', [], $double, false, '', '');
                            $td     .= $this->hc->getHtmlTableData($span, 'center',  '', "\t\t\t");
                            break;
                        case Constants::FIELD_ELE_IMAGELIST:
                            $src = $this->sc->getSmartyNoSimbol('xoModuleIcons32');
                            $src .= $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $this->hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true, '', '');
                            $td  .= $this->hc->getHtmlTableData($img, 'center',  '', "\t\t\t");
                            break;
                        case Constants::FIELD_ELE_UPLOADIMAGE:
                            $single = $this->sc->getSmartySingleVar($moduleDirname . '_upload_url');
                            $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img    = $this->hc->getHtmlTag('img', ['src' => $single . "/images/{$tableName}/" . $double, 'alt' => $tableName], '', true, '', '');
                            $td     .= $this->hc->getHtmlTableData($img, 'center',  '', "\t\t\t");
                            break;
                        case Constants::FIELD_ELE_TEXTAREA:
                        case Constants::FIELD_ELE_DHTMLTEXTAREA:
                            $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName . '_short');
                            $td     .= $this->hc->getHtmlTableData($double, 'center', '', "\t\t\t");
                            break;
                        case Constants::FIELD_ELE_SELECTSTATUS:
                        case Constants::FIELD_ELE_RADIOYN:
                        case Constants::FIELD_ELE_SELECTUSER:
                        case Constants::FIELD_ELE_DATETIME:
                        case Constants::FIELD_ELE_TEXTDATESELECT:
                            $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName . '_text');
                            $td     .= $this->hc->getHtmlTableData($double, 'center', '', "\t\t\t");
                            break;
                        default:
                            if (0 != $f) {
                                $double = $this->sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                                $td     .= $this->hc->getHtmlTableData($double, 'center',  '', "\t\t\t");
                            }
                            break;
                    }
                }
            }
        }
        // TODO: allow edit only for admins
		// $lang    = $this->sc->getSmartyConst('', '_EDIT');
		// $double  = $this->sc->getSmartyDoubleVar($tableSoleName, 'id');
		// $src     = $this->sc->getSmartyNoSimbol('xoModuleIcons32 edit.png');
		// $img     = $this->hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true, '', '');
		// $anchor  = $this->hc->getHtmlTag('a', ['href' => $tableName . ".php?op=edit&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t");
		// $lang    = $this->sc->getSmartyConst('', '_DELETE');
		// $double  = $this->sc->getSmartyDoubleVar($tableSoleName, 'id');
		// $src     = $this->sc->getSmartyNoSimbol('xoModuleIcons32 delete.png');
		// $img     = $this->hc->getHtmlTag('img', ['src' => $src . $double, 'alt' => $tableName], '', true, '', '');
		// $anchor  .= $this->hc->getHtmlTag('a', ['href' => $tableName . ".php?op=delete&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t");
		// $td      .= $this->hc->getHtmlTag('td', ['class' => 'center'], "\n" . $anchor . "\t\t\t", false, "\t\t\t");
        $double  = $this->sc->getSmartyDoubleVar($tableSoleName, 'id');
        $lang    = $this->sc->getSmartyConst($language, $stuTableSoleName . '_GOTO');
        $single = $this->sc->getSmartySingleVar($moduleDirname . '_url');
        $anchor  = $this->hc->getHtmlAnchor($single . '/' . $tableName . ".php?op=show&amp;{$fieldId}=" . $double, $lang, $lang);
        $td     .= $this->hc->getHtmlTableData($anchor, 'center',  '', "\t\t\t");
		$cycle   = $this->sc->getSmartyNoSimbol('cycle values="odd, even"');
		$tr 	 = $this->hc->getHtmlTableRow($td, $cycle, "\t\t");
        $foreach = $this->sc->getSmartyForeach($tableSoleName, 'block', $tr, '','', "\t\t");
        $tbody   = $this->hc->getHtmlTableTbody($foreach,'' , "\t");

        return $this->sc->getSmartyConditions("block|default:0", ' > ', '0', $tbody, false, true, true, "\t");
    }

    /**
     * @private  function getTemplatesBlocksTfoot
     * @return string
     */
    private function getTemplatesBlocksTableTfoot()
    {
        $td = $this->hc->getHtmlTag('td', [], "&nbsp;", false, '', '');
        $tr = $this->hc->getHtmlTag('tr', [], $td, false, '', '');

        return $this->hc->getHtmlTag('tfoot', [], $tr, false, "\t");
    }

    /**
     * @private  function getTemplatesBlocksTable
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $language
     * @return string
     */
    private function getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $tbody  = $this->getTemplatesBlocksTableThead($tableId, $tableMid, $language, $tableAutoincrement);
        $tbody  .= $this->getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);
        $tbody  .= $this->getTemplatesBlocksTableTfoot();
        $single = $this->sc->getSmartySingleVar('table_type');

        return $this->hc->getHtmlTable($tbody, 'table table-responsive table-' . $single);
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language           = $this->getLanguage($moduleDirname, 'MB', '', false);
        $content            = $this->getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);

        $this->create($moduleDirname, 'templates/blocks', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
