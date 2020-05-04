<?php

namespace XoopsModules\Tdmcreate\Files\Templates\Blocks\Defstyle;

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
 * Class TemplatesBlocks.
 */
class TemplatesBlocks extends Files\CreateFile
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
     * @return TemplatesBlocks
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
        $hc     = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc     = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $th     = '';
		if (1 == $tableAutoincrement) {
            $th .= $hc->getHtmlTableHead('&nbsp;', '', '', "\t\t\t");
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            if (1 === (int)$fields[$f]->getVar('field_block')) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $stuFieldName = mb_strtoupper($fieldName);
                $lang         = $sc->getSmartyConst($language, $stuFieldName);
                $th           .= $hc->getHtmlTableHead($lang, 'center', '', "\t\t\t");
            }
        }
        $tr = $hc->getHtmlTableRow($th, 'head', "\t\t");

        return $hc->getHtmlTableThead($tr, '', "\t");
    }

    /**
     * @private  function getTemplatesBlocksTableTbody
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @return string
     */
    private function getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement)
    {
        $hc  = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc  = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $sc->getSmartyDoubleVar($tableSoleName, 'id');
            $td     .= $hc->getHtmlTableData($double, 'center',  '', "\t\t\t");
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            if (1 === (int)$fields[$f]->getVar('field_block')) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $rpFieldName  = $this->getRightString($fieldName);
                if (1 == $fields[$f]->getVar('field_inlist')) {
                    switch ($fieldElement) {
                        case 9:
                            // This is to be reviewed, as it was initially to style = "backgroung-color: #"
                            // Now with HTML5 is not supported inline style in the parameters of the HTML tag
                            // Old code was <span style="background-color: #<{\$list.{$rpFieldName}}>;">...
                            $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $span   = $hc->getHtmlTag('span', [], $double);
                            $td     .= $hc->getHtmlTableData($span, 'center',  '', "\t\t\t");
                            /*$ret .= <<<EOT
                        <td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n
    EOT;*/
                            break;
                        case 10:
                            $src = $sc->getSmartyNoSimbol('xoModuleIcons32');
                            $src .= $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true, '', '');
                            $td  .= $hc->getHtmlTableData($img, 'center',  '', "\t\t\t");
                            break;
                        case 13:
                            $single = $sc->getSmartySingleVar($moduleDirname . '_upload_url');
                            $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img    = $hc->getHtmlTag('img', ['src' => $single . "/images/{$tableName}/" . $double, 'alt' => $tableName], '', true, '', '');
                            $td     .= $hc->getHtmlTableData($img, 'center',  '', "\t\t\t");
                            break;
                        default:
                            if (0 != $f) {
                                $double = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                                $td     .= $hc->getHtmlTableData($double, 'center',  '', "\t\t\t");
                            }
                            break;
                    }
                }
            }
        }
        // TODO: allow edit only for admins
		// $lang    = $sc->getSmartyConst('', '_EDIT');
		// $double  = $sc->getSmartyDoubleVar($tableSoleName, 'id');
		// $src     = $sc->getSmartyNoSimbol('xoModuleIcons32 edit.png');
		// $img     = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true, '', '');
		// $anchor  = $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=edit&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t");
		// $lang    = $sc->getSmartyConst('', '_DELETE');
		// $double  = $sc->getSmartyDoubleVar($tableSoleName, 'id');
		// $src     = $sc->getSmartyNoSimbol('xoModuleIcons32 delete.png');
		// $img     = $hc->getHtmlTag('img', ['src' => $src . $double, 'alt' => $tableName], '', true, '', '');
		// $anchor  .= $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=delete&amp;{$fieldId}=" . $double, 'title' => $lang], $img, false, "\t\t\t\t");
		// $td      .= $hc->getHtmlTag('td', ['class' => 'center'], "\n" . $anchor . "\t\t\t", false, "\t\t\t");
		$cycle   = $sc->getSmartyNoSimbol('cycle values="odd, even"');
		$tr 	 = $hc->getHtmlTableRow($td, $cycle, "\t\t");
        $foreach = $sc->getSmartyForeach($tableSoleName, 'block', $tr, '','', "\t\t");
        $tbody   = $hc->getHtmlTableTbody($foreach,'' , "\t");

        return $sc->getSmartyConditions("block", '', '', $tbody, false, true, true, "\t");
    }

    /**
     * @private  function getTemplatesBlocksTfoot
     * @return string
     */
    private function getTemplatesBlocksTableTfoot()
    {
        $hc = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $td = $hc->getHtmlTag('td', [], "&nbsp;", false, '', '');
        $tr = $hc->getHtmlTag('tr', [], $td, false, '', '');

        return $hc->getHtmlTag('tfoot', [], $tr, false, "\t");
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
        $hc     = Tdmcreate\Files\CreateHtmlCode::getInstance();
        $sc     = Tdmcreate\Files\CreateSmartyCode::getInstance();
        $tbody  = $this->getTemplatesBlocksTableThead($tableId, $tableMid, $language, $tableAutoincrement);
        $tbody  .= $this->getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement);
        $tbody  .= $this->getTemplatesBlocksTableTfoot();
        $single = $sc->getSmartySingleVar('table_type');

        return $hc->getHtmlTable($tbody, 'table table-' . $single);
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
        $language           = $this->getLanguage($moduleDirname, 'MB');
        $content            = $this->getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);

        $this->create($moduleDirname, 'templates/blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
