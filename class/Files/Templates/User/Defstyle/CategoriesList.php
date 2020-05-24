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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class CategoriesList.
 */
class CategoriesList extends Files\CreateFile
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
     * @return bool|CategoriesList
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
     * @private function getTemplatesUserCategoriesListHeader
     * @return string
     */
    private function getTemplatesUserCategoriesListStartTable()
    {
        $ret = <<<EOT
<div class="table-responsive">
    <table class="table table-<{\$table_type}>">\n
EOT;

        return $ret;
    }

    /**
     * @private function getTemplatesUserCategoriesListThead
     * @param        $table
     * @return string
     */
    private function getTemplatesUserCategoriesListThead($table)
    {
        $ret    = <<<EOT
		<thead>
			<tr>\n
EOT;
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_thead')) {
                    $fieldName   = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $ret         .= <<<EOT
				<th><{\$list.{$rpFieldName}}></th>\n
EOT;
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</thead>\n
EOT;

        return $ret;
    }

    /**
     * @private function getTemplatesUserCategoriesListTbody
     * @param string $moduleDirname
     * @param string $table
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListTbody($moduleDirname, $table)
    {
        $tableName = $table->getVar('table_name');
        $ret       = <<<EOT
		<tbody>
			<tr>\n
EOT;
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 10:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $ret         .= <<<EOT
				<td class="center pad5"><img src="<{\$xoops_icons32_url}>/<{\$list.{$rpFieldName}}>" alt="{$tableName}" /></td>\n
EOT;
                            break;
                        case 13:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $ret         .= <<<EOT
				<td class="center pad5"><img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$rpFieldName}}>" alt="{$tableName}" /></td>\n
EOT;
                            break;
                        case 2:
                        case 3:
                        case 4:
                            $fieldName   = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $ret         .= <<<EOT
				<td class="justify pad5"><{\$list.{$rpFieldName}}></td>\n
EOT;
                            break;
                    }
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</tbody>\n
EOT;

        return $ret;
    }

    /**
     * @private function getTemplatesUserCategoriesListTfoot
     * @param string $table
     * @return string
     */
    private function getTemplatesUserCategoriesListTfoot($table)
    {
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $ret       = <<<EOT
		<tfoot>
			<tr>\n
EOT;

        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tfoot')) {
                    $fieldName   = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $ret         .= <<<EOT
				<td class="center"><{\$list.{$rpFieldName}}></td>\n
EOT;
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</tfoot>\n
EOT;

        return $ret;
    }

    /**
     * @private function getTemplatesUserCategoriesListEndTable
     * @param null
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListEndTable()
    {
        $ret = <<<EOT
	</table>
</div>\n
EOT;

        return $ret;
    }

    /**
     * @private function getTemplatesUserCategoriesListPanel
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @return string
     */
    private function getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName)
    {
        $hc      = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc      = Modulebuilder\Files\CreateSmartyCode::getInstance();
        $fields  = $this->getTableFields($tableMid, $tableId);
        $ret     = '';
        $retElem = '';
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 2:
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar   = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem     .= $hc->getHtmlSpan($doubleVar, 'col-sm-2') . PHP_EOL;
                            break;
                        case 3:
                        case 4:
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar   = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem     .= $hc->getHtmlSpan($doubleVar, 'col-sm-3 justify') . PHP_EOL;
                            break;
                        case 10:
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar   = $sc->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar   = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $hc->getHtmlImage($singleVar . '/' . $doubleVar, (string)$tableName);
                            $retElem     .= $hc->getHtmlSpan($img, 'col-sm-3') . PHP_EOL;
                            unset($img);
                            break;
                        case 13:
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar   = $sc->getSmartySingleVar($moduleDirname . '_upload_url');
                            $doubleVar   = $sc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img         = $hc->getHtmlImage($singleVar . "/images/{$tableName}/" . $doubleVar, (string)$tableName);
                            $retElem     .= $hc->getHtmlSpan($img, 'col-sm-3') . PHP_EOL;
                            unset($img);
                            break;
                    }
                }
            }
        }
        $ret .= $hc->getHtmlDiv($retElem, 'panel-body') . PHP_EOL;

        return $ret;
    }

    /**
     * @public function render
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $tables        = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $moduleDirname = $module->getVar('mod_dirname');
        //$language = $this->getLanguage($moduleDirname, 'MA');
        $content  = '';
        foreach (array_keys($tables) as $t) {
            $tableId         = $tables[$t]->getVar('table_id');
            $tableMid        = $tables[$t]->getVar('table_mid');
            $tableName       = $tables[$t]->getVar('table_name');
            $tableSoleName   = $tables[$t]->getVar('table_solename');
            $tableCategory[] = $tables[$t]->getVar('table_category');
            if (in_array(1, $tableCategory)) {
                $content .= $this->getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName);
            }
        }

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
