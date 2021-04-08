<?php

namespace XoopsModules\Modulebuilder\Files;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Constants;

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
        $this->getCheckReads();
        $this->getCheckSQL();

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
                    if (Constants::FIELD_ELE_SELECTSTATUS == $fields[$f]->getVar('field_element')) {
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
        //check field params: minimum one param is selected
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);

            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                // check fields for parameters
                if ($f > 0) {
                    $fieldParams = (int)$fields[$f]->getVar('field_parent')
                                   + (int)$fields[$f]->getVar('field_admin')
                                   + (int)$fields[$f]->getVar('field_inlist')
                                   + (int)$fields[$f]->getVar('field_inform')
                                   + (int)$fields[$f]->getVar('field_user')
                                   + (int)$fields[$f]->getVar('field_ihead')
                                   + (int)$fields[$f]->getVar('field_ibody')
                                   + (int)$fields[$f]->getVar('field_ifoot')
                                   + (int)$fields[$f]->getVar('field_thead')
                                   + (int)$fields[$f]->getVar('field_tbody')
                                   + (int)$fields[$f]->getVar('field_tfoot')
                                   + (int)$fields[$f]->getVar('field_block')
                                   + (int)$fields[$f]->getVar('field_main')
                                   + (int)$fields[$f]->getVar('field_search')
                                   + (int)$fields[$f]->getVar('field_required');
                    if (0 == $fieldParams) {
                        $info          = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS1);
                        $this->infos[] = ['icon' => 'error', 'info' => $info];
                    }
                }
            }
        }

        //check field params: user file no usage in index or item
        foreach (\array_keys($this->tables) as $t) {
            $tableId = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields = $this->cf->getTableFields($this->modId, $tableId);

            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot') + (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        if (0 == $fieldParams) {
                            $info          = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS2);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
        }
        //check field params:  user file index multiple usage
        //check field params:  user file item multiple usage
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

        //check table params: user submit or rate or broken, but table not for user side
        foreach (\array_keys($this->tables) as $t) {
            $tableName = $this->tables[$t]->getVar('table_name');
            if ((0 == $this->tables[$t]->getVar('table_user')) && (1 == $this->tables[$t]->getVar('table_submit') || 1 == $this->tables[$t]->getVar('table_broken') || 1 == $this->tables[$t]->getVar('table_rate'))) {
                $info = \str_replace(['%t'], [$tableName], _AM_MODULEBUILDER_BUILDING_CHECK_USERPAGE1);
                $this->infos[] = ['icon' => 'error', 'info' => $info];
            }
        }

        //check field/table params:  params for index file, but table param table_index = 0
        //check field/table params:  params for user file, but table param table_user = 0
        foreach (\array_keys($this->tables) as $t) {
            $tableId    = $this->tables[$t]->getVar('table_id');
            $tableName  = $this->tables[$t]->getVar('table_name');
            $tableIndex = (int)$this->tables[$t]->getVar('table_index');
            $tableUser  = (int)$this->tables[$t]->getVar('table_user');
            $fields     = $this->cf->getTableFields($this->modId, $tableId);

            $fieldParamsIndex = 0;
            $fieldParamsUser  = 0;
            $fieldName        = '';
            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                if (1 == $fields[$f]->getVar('field_user')) {
                    // check fields for parameters
                    if ($f > 0) {
                        $fieldParams = (int)$fields[$f]->getVar('field_ihead') + (int)$fields[$f]->getVar('field_ibody') + (int)$fields[$f]->getVar('field_ifoot');
                        $fieldParamsIndex += $fieldParams;
                        if ($fieldParams >= 1 && 0 == $tableIndex) {
                            $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS5);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                        $fieldParams = (int)$fields[$f]->getVar('field_thead') + (int)$fields[$f]->getVar('field_tbody') + (int)$fields[$f]->getVar('field_tfoot');
                        $fieldParamsUser += $fieldParams;
                        if ($fieldParams >= 1 && 0 == $tableUser) {
                            $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS7);
                            $this->infos[] = ['icon' => 'warning', 'info' => $info];
                        }
                    }
                }
            }
            if (0 == $fieldParamsIndex && 1 == $tableIndex) {
                $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS4);
                $this->infos[] = ['icon' => 'warning', 'info' => $info];
            }
            if (0 == $fieldParamsUser && 1 == $tableUser) {
                $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_FIELDS6);
                $this->infos[] = ['icon' => 'warning', 'info' => $info];
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
                    if (Constants::FIELD_ELE_TEXTDATESELECT == $fields[$f]->getVar('field_element') || Constants::FIELD_ELE_DATETIME == $fields[$f]->getVar('field_element')) {
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
                $fieldComments = 0;
                foreach (\array_keys($fields) as $f) {
                    if (Constants::FIELD_ELE_TEXTCOMMENTS == (int)$fields[$f]->getVar('field_element')) {
                        $fieldComments++;
                    }
                }
                // check whether each table with handling "comments" has also a field "comments"
                if (0 == $fieldComments) {
                    $info = \str_replace('%t', $tableName, _AM_MODULEBUILDER_BUILDING_CHECK_COMMENTS2);
                    $this->infos[] = ['icon' => 'warning', 'info' => $info];
                }
            }
        }

        return true;
    }

    /**
     * @private function getCheckRatings
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
                $fieldRatings = 0;
                $fieldVotes = 0;
                foreach (\array_keys($fields) as $f) {
                    if (Constants::FIELD_ELE_TEXTRATINGS == (int)$fields[$f]->getVar('field_element')) {
                        $fieldRatings++;
                    }
                    if (Constants::FIELD_ELE_TEXTVOTES == (int)$fields[$f]->getVar('field_element')) {
                        $fieldVotes++;
                    }
                }
                // check whether each table with handling "rating" has also a field "rating"
                if (0 == (int)$fieldRatings) {
                    $info = \str_replace('%t', $tableName, _AM_MODULEBUILDER_BUILDING_CHECK_RATINGS1);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
                // check whether each table with handling "rating" has also a field "votes"
                if (0 == (int)$fieldVotes) {
                    $info = \str_replace('%t', $tableName, _AM_MODULEBUILDER_BUILDING_CHECK_RATINGS2);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }

        return true;
    }

    /**
     * @private function getCheckReads
     *
     * @return array|bool
     */
    private function getCheckReads()
    {
        foreach (\array_keys($this->tables) as $t) {
            if (1 == $this->tables[$t]->getVar('table_reads')) {
                $tableId = $this->tables[$t]->getVar('table_id');
                $tableName = $this->tables[$t]->getVar('table_name');
                $fields = $this->cf->getTableFields($this->modId, $tableId);
                $fieldReads = 0;
                foreach (\array_keys($fields) as $f) {
                    if (Constants::FIELD_ELE_TEXTREADS == (int)$fields[$f]->getVar('field_element')) {
                        $fieldReads++;
                    }
                }
                // check whether each table with handling "reads" has also a field "reads"
                if (0 == (int)$fieldReads) {
                    $info = \str_replace('%t', $tableName, _AM_MODULEBUILDER_BUILDING_CHECK_READS1);
                    $this->infos[] = ['icon' => 'error', 'info' => $info];
                }
            }
        }

        return true;
    }

    /**
     * @private function getCheckSql
     *
     * @return array|bool
     */
    private function getCheckSql()
    {
        foreach (\array_keys($this->tables) as $t) {
            $tableId   = $this->tables[$t]->getVar('table_id');
            $tableName = $this->tables[$t]->getVar('table_name');
            $fields    = $this->cf->getTableFields($this->modId, $tableId);
            foreach (\array_keys($fields) as $f) {
                $fieldName = $fields[$f]->getVar('field_name');
                $fieldType = $fields[$f]->getVar('field_type');
                if (6 == $fieldType || 7 == $fieldType || 8 == $fieldType) {
                    $fieldValue = $fields[$f]->getVar('field_value');
                    if (0 == \strpos($fieldValue,',')) {
                        $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_SQL1);
                        $this->infos[] = ['icon' => 'error', 'info' => $info];
                    }
                    $fieldDefault = $fields[$f]->getVar('field_default');
                    if (0 == \strpos($fieldDefault,'.')) {
                        $info = \str_replace(['%f', '%t'], [$fieldName, $tableName], _AM_MODULEBUILDER_BUILDING_CHECK_SQL2);
                        $this->infos[] = ['icon' => 'warning', 'info' => $info];
                    }
                }
            }
        }

        return true;
    }
}
