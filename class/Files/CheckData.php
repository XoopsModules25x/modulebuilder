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

//include \dirname(__DIR__) . '/autoload.php';

/**
 * Class CheckData.
 */
class CheckData
{
    /**
     * @var mixed
     */
    private $cf = null;

    /**
     * @var mixed
     */
    private $modId = null;

    /**
     * @var mixed
     */
    private $tables = null;

    /**
     * @var mixed
     */
    private $infos = [];



    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        $this->cf = Modulebuilder\Files\CreateFile::getInstance();
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
        $this->modId  = $module->getVar('mod_id');
        $this->tables = $this->cf->getTableTables($this->modId);
        $this->infos = [];

        $this->getCheckBlock();
        $this->getCheckBroken();
        $this->getCheckComments();
        $this->getCheckUserpage();
        $this->getCheckRating();

        return $this->infos;
    }

    /**
     * @public function getCheckBroken
     *
     * @return array|bool
     */
    private function getCheckBroken()
    {
        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_broken')) {
                $tableId = $this->tables[$t]->getVar('table_id');
                $tableName = $this->tables[$t]->getVar('table_name');
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                $fieldSatus = '';

                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (16 == $fields[$f]->getVar('field_element')) {
                        $fieldSatus = $fieldName;
                    }
                }
                // check whether each table with handling "broken" has also a field "status"
                if ('' == $fieldSatus) {
                    $info = \str_replace('%t', $tableName, _AM_MODULEBUILDER_BUILDING_CHECK_BROKEN1);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }

        return true;
    }

    /**
     * @private function getCheckUserpage
     *
     * @return array|bool
     */
    private function getCheckUserpage()
    {
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);

            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                // check fields for parameters
                if ($f > 0) {
                    $fieldParams = (int)$fields[$f]->getVar('field_parent') + (int)$fields[$f]->getVar('field_admin') + (int)$fields[$f]->getVar('field_inlist') + (int)$fields[$f]->getVar('field_inform')
                        + (int)$fields[$f]->getVar('field_user') + (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot')
                        + (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot') + (int)$fields[$f]->getVar('field_block')
                        + (int)$fields[$f]->getVar('field_main') + (int)$fields[$f]->getVar('field_search') + (int)$fields[$f]->getVar('field_required');
                    if (0 == $fieldParams) {
                        $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS1);
                        $this->infos[] = ['icon' => 'error', 'info' => $info];
                    }
                }
            }
        }

        //check user file no usage in index or item
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);

            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot')
                            + (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        if (0 == $fieldParams) {
                            $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS2);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
        }
        //check user file index multiple usage
        //check user file item multiple usage
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);

            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot');
                        if ($fieldParams > 1) {
                            $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS3);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                        $fieldParams = (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        if ($fieldParams > 1) {
                            $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS3);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
        }

        //check user submit or rate or broken, but table not for user side
        foreach (\array_keys($this->tables) as $t) {
            $tableName = $this->tables[$t]->getVar('table_name');
            if ((0 == $this->tables[$t]->getVar('table_user')) && (1 == $this->tables[$t]->getVar('table_submit') || 1 == $this->tables[$t]->getVar('table_broken') || 1 == $this->tables[$t]->getVar('table_rate'))) {
                $info = \str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_BUILDING_CHECK_USERPAGE1);
                $this->infos[] = ['icon' => 'error', 'info' => $info];
            }
        }
        return true;
    }

    /**
     * @private function getCheckBlock
     *
     * @return array|bool
     */
    private function getCheckBlock()
    {
        //use in block but no field selected
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);
            $count = 0;
            if (1 == $this->tables[$t]->getVar('table_blocks')) {
                foreach (\array_keys($fields) as $f) {
                    if (1 == $fields[$f]->getVar('field_block')) {
                        $count++;
                    }
                }
                if (0 == $count) {
                    $info = \str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_BUILDING_CHECK_BLOCK1);
                    $this->infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }
        //use in block but no field date
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');

            $count = 0;
            if (1 == $this->tables[$t]->getVar('table_blocks')) {
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                foreach (\array_keys($fields) as $f) {
                    if (15 == $fields[$f]->getVar('field_element') || 21 == $fields[$f]->getVar('field_element')) {
                        $count++;
                    }
                }
                if (0 == $count) {
                    $info = \str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_BUILDING_CHECK_BLOCK2);
                    $this->infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }
        return true;
    }

    /**
     * @private function getCheckComments
     *
     * @return array|bool
     */
    private function getCheckComments()
    {
        //use comments in multiple tables
        $count         = 0;
        $tableComments = [];
        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_comments')) {
                $count++;
                $tableComments[] = $this->tables[$t]->getVar('table_name');
            }
        }
        if ($count > 1) {
            $tablesComments = \implode(', ', $tableComments);
            $info = \str_replace('%t', $tablesComments, _AM_MODULEBUILDER_BUILDING_CHECK_COMMENTS1);
            $this->infos[] = ['icon' => 'error', 'info' => $info];
        }

        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_comments')) {
                $tableId = $this->tables[$t]->getVar('table_id');
                $tableName = $this->tables[$t]->getVar('table_name');
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                $fieldComments = '';

                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if ($fieldName == $this->tables[$t]->getVar('table_fieldname') . '_comments') {
                        $fieldComments = $fieldName;
                    }
                }
                // check whether each table with handling "comments" has also a field "comments"
                if ('' == $fieldComments) {
                    $info = \str_replace(['%f', '%t'], [$this->tables[$t]->getVar('table_fieldname') . '_comments', $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_COMMENTS2);
                    $this->infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }

        return true;
    }

    /**
     * @private function getCheckComments
     *
     * @return array|bool
     */
    private function getCheckRating()
    {
        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_rate')) {
                $tableId = $this->tables[$t]->getVar('table_id');
                $tableName = $this->tables[$t]->getVar('table_name');
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                $fieldRatings = '';

                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if ($fieldName == $this->tables[$t]->getVar('table_fieldname') . '_ratings') {
                        $fieldRatings = $fieldName;
                    }
                }
                // check whether each table with handling "rating" has also a field "rating"
                if ('' == $fieldRatings) {
                    $info = \str_replace(['%f', '%t'], [$this->tables[$t]->getVar('table_fieldname') . '_ratings', $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_RATINGS1);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }
        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_rate')) {
                $tableId = $this->tables[$t]->getVar('table_id');
                $tableName = $this->tables[$t]->getVar('table_name');
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                $fieldVotes = '';

                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if ($fieldName == $this->tables[$t]->getVar('table_fieldname') . '_votes') {
                        $fieldVotes = $fieldName;
                    }
                }
                // check whether each table with handling "rating" has also a field "votes"
                if ('' == $fieldVotes) {
                    $info = \str_replace(['%f', '%t'], [$this->tables[$t]->getVar('table_fieldname') . '_votes', $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_RATINGS1);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }

        return true;
    }
}
