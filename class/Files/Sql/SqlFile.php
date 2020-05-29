<?php

namespace XoopsModules\Modulebuilder\Files\Sql;

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
 * Class SqlFile.
 */
class SqlFile extends Files\CreateFile
{
    /**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return SqlFile
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
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getHeaderSqlComments
     *
     * @param $moduleName
     *
     * @return string
     */
    private function getHeaderSqlComments($moduleName)
    {
        $date          = date('D M d, Y');
        $time          = date('H:i:s');
        $serverName    = \Xmf\Request::getString('SERVER_NAME', '', 'SERVER');
        $serverVersion = $GLOBALS['xoopsDB']->getServerVersion();
        $phpVersion    = PHP_VERSION;
        // Header Sql Comments
        $ret             = null;
        $arrayServerInfo = [
            "# SQL Dump for {$moduleName} module",
            '# PhpMyAdmin Version: 4.0.4',
            '# http://www.phpmyadmin.net',
            '#',
            "# Host: {$serverName}",
            "# Generated on: {$date} to {$time}",
            "# Server version: {$serverVersion}",
            "# PHP Version: {$phpVersion}\n",
        ];
        foreach ($arrayServerInfo as $serverInfo) {
            $ret .= $this->getSimpleString($serverInfo);
        }

        return $ret;
    }

    /**
     * @private function getHeadDatabaseTable
     * @param     $moduleDirname
     * @param     $tableName
     * @param int $fieldsNumb
     *
     *  Unused IF NOT EXISTS
     *
     * @return string
     */
    private function getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb)
    {
        $ret          = null;
        $arrayDbTable = [
            '#',
            "# Structure table for `{$moduleDirname}_{$tableName}` {$fieldsNumb}",
            '#',
            "\nCREATE TABLE `{$moduleDirname}_{$tableName}` (",
        ];
        foreach ($arrayDbTable as $dbTable) {
            $ret .= $this->getSimpleString($dbTable);
        }

        return $ret;
    }

    /**
     * @private function getDatabaseTables
     *
     * @param $module
     *
     * @return null|string
     */
    private function getDatabaseTables($module)
    {
        $ret           = null;
        $moduleDirname = mb_strtolower($module->getVar('mod_dirname'));
        $tables        = $this->getTableTables($module->getVar('mod_id'), 'table_order ASC, table_id');
        foreach (array_keys($tables) as $t) {
            $tableId            = $tables[$t]->getVar('table_id');
            $tableMid           = $tables[$t]->getVar('table_mid');
            $tableName          = $tables[$t]->getVar('table_name');
            $tableAutoincrement = $tables[$t]->getVar('table_autoincrement');
            $fieldsNumb         = $tables[$t]->getVar('table_nbfields');
            $ret                .= $this->getDatabaseFields($moduleDirname, $tableMid, $tableId, $tableName, $tableAutoincrement, $fieldsNumb);
        }

        return $ret;
    }

    /**
     * @private function getDatabaseFields
     *
     * @param $moduleDirname
     * @param $tableMid
     * @param $tableId
     * @param $tableName
     * @param $tableAutoincrement
     * @param $fieldsNumb
     * @return null|string
     */
    private function getDatabaseFields($moduleDirname, $tableMid, $tableId, $tableName, $tableAutoincrement, $fieldsNumb)
    {
        $helper        = Modulebuilder\Helper::getInstance();
        $ret           = null;
        $j             = 0;
        $comma         = [];
        $row           = [];
        //$type          = '';
        $fieldTypeName = '';
        $fields = $this->getTableFields($tableMid, $tableId, 'field_id ASC, field_name');
        foreach (array_keys($fields) as $f) {
            // Creation of database table
            $ret            = $this->getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb);
            $fieldName      = $fields[$f]->getVar('field_name');
            $fieldType      = $fields[$f]->getVar('field_type');
            $fieldValue     = str_replace('&#039;','', $fields[$f]->getVar('field_value')); //remove single quotes
            $fieldAttribute = $fields[$f]->getVar('field_attribute');
            $fieldNull      = $fields[$f]->getVar('field_null');
            $fieldDefault   = str_replace('&#039;','', $fields[$f]->getVar('field_default')); //remove single quotes
            $fieldKey       = $fields[$f]->getVar('field_key');
            if ($fieldType > 1) {
                $fType         = $helper->getHandler('Fieldtype')->get($fieldType);
                $fieldTypeName = $fType->getVar('fieldtype_name');
            } else {
                $fieldType = null;
            }
            if ($fieldAttribute > 1) {
                $fAttribute     = $helper->getHandler('Fieldattributes')->get($fieldAttribute);
                $fieldAttribute = $fAttribute->getVar('fieldattribute_name');
            } else {
                $fieldAttribute = null;
            }
            if ($fieldNull > 1) {
                $fNull     = $helper->getHandler('Fieldnull')->get($fieldNull);
                $fieldNull = $fNull->getVar('fieldnull_name');
            } else {
                $fieldNull = null;
            }
            if (!empty($fieldName)) {
                switch ($fieldType) {
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $type = $fieldTypeName . '(' . $fieldValue . ')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '0'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 6:
                    case 7:
                    case 8:
                        $type = $fieldTypeName . '(' . $fieldValue . ')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '0.00'"; // From MySQL 5.7 Manual
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 9:
                    case 10:
                        $fValues = str_replace(',', "', '", str_replace(' ', '', $fieldValue));
                        $type    = $fieldTypeName . '(\'' . $fValues . '\')'; // Used with comma separator
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                    case 11:
                        $type = $fieldTypeName . '(' . $fieldValue . ')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT 'my@email.com'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 12:
                        $type = $fieldTypeName . '(' . $fieldValue . ')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT 'http:\\'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 13:
                    case 14:
                        $type    = $fieldTypeName . '(' . $fieldValue . ')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                    case 15:
                    case 16:
                    case 17:
                    case 18:
                        $type    = $fieldTypeName;
                        $default = null;
                        break;
                    case 19:
                    case 20:
                    case 21:
                    case 22:
                        $type    = $fieldTypeName . '(' . $fieldValue . ')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                    case 23:
                        $type = $fieldTypeName;
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '1970'"; // From MySQL 5.7 Manual
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    default:
                        $type    = $fieldTypeName . '(' . $fieldValue . ')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                }
                if ((0 == $f) && (1 == $tableAutoincrement)) {
                    $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, null, 'AUTO_INCREMENT');
                    $comma[$j] = $this->getKey(2, $fieldName);
                    ++$j;
                } elseif ((0 == $f) && (0 == $tableAutoincrement)) {
                    $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                    $comma[$j] = $this->getKey(2, $fieldName);
                    ++$j;
                } else {
                    if (3 == $fieldKey || 4 == $fieldKey || 5 == $fieldKey || 6 == $fieldKey) {
                        switch ($fieldKey) {
                            case 3:
                                $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(3, $fieldName);
                                ++$j;
                                break;
                            case 4:
                                $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(4, $fieldName);
                                ++$j;
                                break;
                            case 5:
                                $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(5, $fieldName);
                                ++$j;
                                break;
                            case 6:
                                $row[]     = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(6, $fieldName);
                                ++$j;
                                break;
                        }
                    } else {
                        $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                    }
                }
            }
        }
        // ================= COMMA ================= //
        for ($i = 0; $i < $j; ++$i) {
            if ($i != $j - 1) {
                $row[] = $comma[$i] . ',';
            } else {
                $row[] = $comma[$i];
            }
        }
        // ================= COMMA CICLE ================= //
        $ret .= implode("\n", $row);
        unset($j);
        $ret .= $this->getFootDatabaseTable();

        return $ret;
    }

    /**
     * @private function getFootDatabaseTable
     *
     * @param null
     *
     * @return string
     */
    private function getFootDatabaseTable()
    {
        return "\n) ENGINE=InnoDB;\n\n";
    }

    /**
     * @private function getFieldRow
     *
     * @param $fieldName
     * @param $fieldTypeValue
     * @param $fieldAttribute
     * @param $fieldNull
     * @param $fieldDefault
     * @param $autoincrement
     *
     * @return string
     */
    private function getFieldRow($fieldName, $fieldTypeValue, $fieldAttribute = null, $fieldNull = null, $fieldDefault = null, $autoincrement = null)
    {
        $retAutoincrement  = "  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$autoincrement},";
        $retFieldAttribute = "  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$fieldDefault},";
        $fieldDefault      = "  `{$fieldName}` {$fieldTypeValue} {$fieldNull} {$fieldDefault},";
        $retShort          = "  `{$fieldName}` {$fieldTypeValue},";

        $ret = $retShort;
        if (null != $autoincrement) {
            $ret = $retAutoincrement;
        } elseif (null != $fieldAttribute) {
            $ret = $retFieldAttribute;
        } elseif (null === $fieldAttribute) {
            $ret = $fieldDefault;
        }

        return $ret;
    }

    /**
     * @private function getKey
     *
     * @param $key
     * @param $fieldName
     * @return string
     */
    private function getKey($key, $fieldName)
    {
        $ret = null;
        switch ($key) {
            case 2: // PRIMARY KEY
                $ret = "  PRIMARY KEY (`{$fieldName}`)";
                break;
            case 3: // UNIQUE KEY
                $ret = "  UNIQUE KEY `{$fieldName}` (`{$fieldName}`)";
                break;
            case 4: // KEY
                $ret = "  KEY `{$fieldName}` (`{$fieldName}`)";
                break;
            case 5: // INDEX
                $ret = "  INDEX (`{$fieldName}`)";
                break;
            case 6: // FULLTEXT KEY
                $ret = "  FULLTEXT KEY `{$fieldName}` (`{$fieldName}`)";
                break;
        }

        return $ret;
    }

    /**
     * @private function getComma
     *
     * @param $row
     * @param $comma
     *
     * @return string
     */
    private function getComma($row, $comma = null)
    {
        return " {$row}{$comma}";
    }

    /**
     * @public function render
     *
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleName    = mb_strtolower($module->getVar('mod_name'));
        $moduleDirname = mb_strtolower($module->getVar('mod_dirname'));
        $content       = $this->getHeaderSqlComments($moduleName);
        $content       .= $this->getDatabaseTables($module);

        $this->create($moduleDirname, 'sql', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
