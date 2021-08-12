<?php

namespace XoopsModules\Modulebuilder\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use XoopsModules\Modulebuilder\Common;

/**
 * Class to compare current DB table structure with sql/mysql.sql
 *
 * @category  Table Checker
 * @author    Goffy <webmmaster@wedega.com>
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */

class TableChecker extends \XoopsObject
{

    /**
     * @var mixed
     */
    private $after = null;

    /**
     * @var mixed
     */
    private $mydirname = null;

    /**
     * @var mixed
     */
    private $result = [];

    /**
     * @var mixed
     */
    private $checktype = null;
    public const CHECKTYPE_REPORT = 0; //report only
    public const CHECKTYPE_UPDATE = 1; //update only
    public const CHECKTYPE_UPDATE_REPORT = 2; //update and report


    /**
     * @param \XoopsModules\Modulebuilder\Common\TableChecker|null
     */
    public function __construct($mydirname, $checktype = 0)
    {
        $this->mydirname = $mydirname;
        $this->checktype = $checktype;
        $this->result = [];
    }

    /**
     *
     */
    public function processSQL()
    {

        $tabledefs = $this->readSQLFile();

        $this->result[] = 'Tables found in sql:' . \count($tabledefs);

        foreach ($tabledefs as $tabledef) {
            //echo '<br>' . $tabledef['name'];
            //check whether table exist or not
            $table   = $tabledef['name'];
            $check   = $GLOBALS['xoopsDB']->queryF("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table'");
            $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
            if ($numRows) {
                //table exist
                $this->result[] = 'Table exist:' . $table;
                $ret = $this->checkTableFields($table, $tabledef['fields']);
            } else {
                if ($this::CHECKTYPE_UPDATE == $this->checktype || $this::CHECKTYPE_UPDATE_REPORT == $this->checktype) {
                    // create new table
                    $sql = $tabledef['sql'];
                    if ($this->result = $GLOBALS['xoopsDB']->queryF($sql)) {
                        $this->result[] = 'Table created:' . $table;
                    } else {
                        \xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
                        $this->result[] = 'Error creating table:' . $table;
                    }
                } else {
                    $this->result[] = 'Table do not exist:' . $table . ' (creation not activated)';
                }
            }
        }

        if (self::CHECKTYPE_REPORT == $this->checktype || self::CHECKTYPE_UPDATE_REPORT == $this->checktype) {
            return $this->result;
        }
    }

    /**
     *
     */
    private function readSQLFile()
    {
        $tabledefs = [];

        $moduleHandler = \xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($this->mydirname);
        $module->loadInfoAsVar($this->mydirname);
        $sqlfile = $module->getInfo('sqlfile');
        $sql_file_path = \XOOPS_ROOT_PATH . '/modules/' . $this->mydirname . '/' . $sqlfile[\XOOPS_DB_TYPE];

        if (\file_exists($sql_file_path)) {
            require_once \XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sqlutil = new \SqlUtility();
            $pieces = [];
            $sql_query = \trim(file_get_contents($sql_file_path));
            $sqlutil->splitMySqlFile($pieces, $sql_query);

            $countTable = 0;
            foreach ($pieces as $piece) {
                $singleSql = $sqlutil->prefixQuery($piece, $GLOBALS['xoopsDB']->prefix());
                $lines = \preg_split('/\r\n|\n|\r/', $piece);
                //var_dump($lines);
                $needle1 = 'create table';
                if ($needle1 == \mb_strtolower($singleSql[1])) {
                    $countLine = 0;
                    $tabledefs[$countTable]['sql'] = $singleSql[0];
                    $tabledefs[$countTable]['name'] = $GLOBALS['xoopsDB']->prefix() . '_' . $singleSql[4];
                    $this->after = '';
                    foreach($lines as $line) {
                        if ($countLine > 0) {
                            $needle2 = 'primary key';
                            $needle3 = 'unique key';
                            $needle4 = 'key';
                            if (0 === \stripos(\trim($line), $needle2)) {
                                $tabledefs[$countTable][$needle2] = $line;
                            } elseif (0 === \stripos(\trim($line), $needle3)) {
                                $tabledefs[$countTable][$needle3] = $line;
                            } elseif (0 === \stripos(\trim($line), $needle4)) {
                                $tabledefs[$countTable][$needle4] = $line;
                            } else {
                                if (\strpos($line, '`') > 0) {
                                    $tabledefs[$countTable]['fields'][] = $this->extractField($line);
                                }
                            }
                        }
                        $countLine++;
                    }
                    $countTable++;
                }
            }
            //var_dump($tabledefs);
        } else {
            $this->result[] = 'File do not exist:' . $sql_file_path;
        }

        return $tabledefs;
    }


    private function extractKey($line) {
        //todo: split string into single keys
        $needle = '(';
        $key_text = \substr($line, \strpos($line, $needle, 0) + 1);
        $needle = ')';
        $key_text = \substr($key_text, 0, \strpos($key_text, $needle, 0));

        return $key_text;

    }

    private function extractField($line) {
        //todo
        $counter = 0;
        $clean = mb_substr(\trim($line), 0, -1);
        $params = \array_values(\array_filter(\explode(' ', $clean)));
        $field['sql'] = $clean;
        $field['name'] = \trim($params[$counter], '`');
        $counter++;
        $field['type'] = $params[$counter];
        $counter++;
        if ('unsigned' == \mb_strtolower($params[$counter])) {
            $field['unsigned'] = $params[$counter];
            $counter++;
        }
        if ('not' == \mb_strtolower($params[$counter]) && 'null' == \mb_strtolower($params[$counter + 1])) {
            $field['null'] = $params[$counter] . ' ' . $params[$counter + 1];
            $counter = $counter+2;
        }
        if (\count($params) > $counter) {
            if ('auto_increment' == \mb_strtolower($params[$counter])) {
                $field['auto_increment'] = $params[$counter];
                $counter++;
            }
        }
        if (\count($params) > $counter) {
            if ('default' == \mb_strtolower($params[$counter])) {
                $field['default'] = $params[$counter] . ' ' . $params[$counter + 1];
                $counter = $counter + 2;
            }
        }

        $field['after'] = $this->after;
        $this->after = $field['name'];

        return $field;

    }

    private function checkTableFields($table, $fields)
    {
        //to be created
        foreach ($fields as $field) {
            //check whether column exist or not
            $fieldname = $field['name'];
            $check     = $GLOBALS['xoopsDB']->queryF("SHOW COLUMNS FROM `$table` LIKE '$fieldname'");
            $numRows   = $GLOBALS['xoopsDB']->getRowsNum($check);
            if ($numRows) {
                //field exist
                $this->checkField($table, $field);
            } else {
                if (self::CHECKTYPE_UPDATE == $this->checktype || self::CHECKTYPE_UPDATE_REPORT == $this->checktype) {
                    // create new field
                    $sql = "ALTER TABLE `$table` ADD " . $field['sql'];
                    if ('' !== (string)$field['after']) {
                        $sql .=  ' AFTER `' . $field['after'] . '`;';
                    }
                    if ($result = $GLOBALS['xoopsDB']->queryF($sql)) {
                        $this->result[] = 'Field added:' . $fieldname;
                    } else {
                        \xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
                        $this->result[] = "Error when adding '$fieldname' to table '$table'.";
                    }
                } else {
                    $this->result[] = 'Field do not exist:' . $fieldname . ' (creation not activated)';
                }
            }
        }

        return true;
    }

    private function checkField($table, $field)
    {
        //to be created
        $this->result[] = 'Field exist:' . $field['name'] . ' - no changes';

        return true;
    }
}
