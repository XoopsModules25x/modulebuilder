<?php

namespace XoopsModules\Modulebuilder\Files;

use XoopsModules\Modulebuilder;

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

//include dirname(__DIR__) . '/autoload.php';

/**
 * Class CheckData.
 */
class CheckData
{
    /**
     * @public function constructor class
     *
     * @param null
     */
    public function __construct()
    {
        //parent::__construct();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return Modulebuilder\Files\CheckData
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
     * @public function getCheckResult
     *
     * @param $module
     * @return array
     */
    public function getCheckPreBuilding($module)
    {
        $cf     = Modulebuilder\Files\CreateFile::getInstance();

        $modId  = $module->getVar('mod_id');
        $tables = $cf->getTableTables($modId);
        $infos = [];

        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_broken')) {
                $tableId = $tables[$t]->getVar('table_id');
                $tableName = $tables[$t]->getVar('table_name');
                $fields = $cf->getTableFields($modId, $tableId);
                $fieldSatus = '';

                foreach (array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (16 == $fields[$f]->getVar('field_element')) {
                        $fieldSatus = $fieldName;
                    }
                }
                // check whether each table with handling "broken" has also a field "status"
                if ('' == $fieldSatus) {
                    $info = str_replace('%t', $tableName, _AM_MODULEBUILDER_CHECKPREBUILD_BROKEN1);
                    $infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }

        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');
            $fields = $cf->getTableFields($modId, $tableId);

            foreach (array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                // check fields for parameters
                if ($f > 0) {
                    $fieldParams = (int)$fields[$f]->getVar('field_parent') + (int)$fields[$f]->getVar('field_admin') + (int)$fields[$f]->getVar('field_inlist') + (int)$fields[$f]->getVar('field_inform')
                        + (int)$fields[$f]->getVar('field_user') + (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot')
                        + (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot') + (int)$fields[$f]->getVar('field_block')
                        + (int)$fields[$f]->getVar('field_main') + (int)$fields[$f]->getVar('field_search') + (int)$fields[$f]->getVar('field_required');
                    if (0 == $fieldParams) {
                        $info = str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_CHECKPREBUILD_FIELDS1);
                        $infos[] = ['icon' => 'error', 'info' => $info];
                    }
                }
            }
        }

        //check user file no usage in index or item
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');
            $fields = $cf->getTableFields($modId, $tableId);

            foreach (array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot')
                            + (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        if (0 == $fieldParams) {
                            $info = str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_CHECKPREBUILD_FIELDS2);
                            $infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
        }
        //check user file index multiple usage
        //check user file item multiple usage
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');
            $fields = $cf->getTableFields($modId, $tableId);

            foreach (array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot');
                        if ($fieldParams > 1) {
                            $info = str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_CHECKPREBUILD_FIELDS3);
                            $infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                        $fieldParams = (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        if ($fieldParams > 1) {
                            $info = str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_CHECKPREBUILD_FIELDS3);
                            $infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
        }

        //use in block but not field selected
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');
            $fields = $cf->getTableFields($modId, $tableId);
            $count = 0;
            if (1 == $tables[$t]->getVar('table_blocks')) {
                foreach (array_keys($fields) as $f) {
                    if (1 == $fields[$f]->getVar('field_block')) {
                        $count++;
                    }
                }
                if (0 == $count) {
                    $info = str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_CHECKPREBUILD_BLOCK1);
                    $infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }
        //use in block but not field date
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');

            $count = 0;
            if (1 == $tables[$t]->getVar('table_blocks')) {
                $fields = $cf->getTableFields($modId, $tableId);
                foreach (array_keys($fields) as $f) {
                    if (15 == $fields[$f]->getVar('field_element') || 21 == $fields[$f]->getVar('field_element')) {
                        $count++;
                    }
                }
                if (0 == $count) {
                    $info = str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_CHECKPREBUILD_BLOCK2);
                    $infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }
        //use comments in multiple tables
        $count         = 0;
        $tableComments = [];
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_comments')) {
                $count++;
                $tableComments[] = $tables[$t]->getVar('table_name');
            }
        }
        if ($count > 1) {
            $tablesComments = implode(', ', $tableComments);
            $info = str_replace('%t', $tablesComments, _AM_MODULEBUILDER_CHECKPREBUILD_COMMENTS1);
            $infos[] = ['icon' => 'error', 'info' => $info];
        }

        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_comments')) {
                $tableId = $tables[$t]->getVar('table_id');
                $tableName = $tables[$t]->getVar('table_name');
                $fields = $cf->getTableFields($modId, $tableId);
                $fieldComments = '';

                foreach (array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if ($fieldName == $tables[$t]->getVar('table_fieldname') . '_comments') {
                        $fieldComments = $fieldName;
                    }
                }
                // check whether each table with handling "broken" has also a field "status"
                if ('' == $fieldComments) {
                    $info = str_replace(['%f', '%t'], [$tables[$t]->getVar('table_fieldname') . '_comments', $tableName], _AM_MODULEBUILDER_CHECKPREBUILD_COMMENTS2);
                    $infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }

        return $infos;
    }
}
