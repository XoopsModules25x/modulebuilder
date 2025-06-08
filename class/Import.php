<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder;

use Xmf\Request;

/**
 * class Import
 */
class Import
{
    public static function importModule(): array
    {
        $ret = [];
        $moduleName    = Request::getString('moduleName',  'newModule', 'POST');
        $moduleNewName = Request::getString('moduleNewName',  $moduleName, 'POST');
        $moduleDirname = \preg_replace('/[^a-zA-Z0-9]\s+/', '', \mb_strtolower($moduleNewName));
        if ('' === $moduleDirname) {
            $ret['result'] = false;
            $ret['error'] = \_AM_MODULEBUILDER_ERROR_MNAME;

            return $ret;
        }

        $helper         = Helper::getInstance();
        $modulesHandler = $helper->getHandler('Modules');
        $newModuleObj   = $modulesHandler->create();

        $newModuleObj->setVar('mod_name', $moduleNewName);
        $newModuleObj->setVar('mod_release', date('Y-m-d'));
        $newModuleObj->setVar('mod_dirname', $moduleDirname);

        $newModuleObj->setVar('mod_version', $helper->getConfig('version')); //$GLOBALS['xoopsModuleConfig']['version']);

        $newModuleObj->setVar('mod_since', $helper->getConfig('since'));
        $newModuleObj->setVar('mod_min_php', $helper->getConfig('min_php'));
        $newModuleObj->setVar('mod_min_xoops', $helper->getConfig('min_xoops'));
        $newModuleObj->setVar('mod_min_admin', $helper->getConfig('min_admin'));
        $newModuleObj->setVar('mod_min_mysql', $helper->getConfig('min_mysql'));

        $newModuleObj->setVar('mod_description', $helper->getConfig('description'));
        $newModuleObj->setVar('mod_author', $helper->getConfig('author'));
        $newModuleObj->setVar('mod_author_mail', $helper->getConfig('author_email'));
        $newModuleObj->setVar('mod_author_website_url', $helper->getConfig('author_website_url'));
        $newModuleObj->setVar('mod_author_website_name', $helper->getConfig('author_website_name'));
        $newModuleObj->setVar('mod_credits', $helper->getConfig('credits'));
        $newModuleObj->setVar('mod_license', $helper->getConfig('license'));
        $newModuleObj->setVar('mod_display_admin', $helper->getConfig('display_admin'));
        $newModuleObj->setVar('mod_display_user', $helper->getConfig('display_user'));
        $newModuleObj->setVar('mod_active_search', $helper->getConfig('active_search'));
        $newModuleObj->setVar('mod_active_comments', $helper->getConfig('active_comments'));
        $newModuleObj->setVar('mod_release_info', $helper->getConfig('release_info'));
        $newModuleObj->setVar('mod_release_file', $helper->getConfig('release_file'));
        $newModuleObj->setVar('mod_manual', $helper->getConfig('manual'));
        $newModuleObj->setVar('mod_manual_file', $helper->getConfig('manual_file'));
        $newModuleObj->setVar('mod_image', 'empty.png');
        $newModuleObj->setVar('mod_demo_site_url', $helper->getConfig('demo_site_url'));
        $newModuleObj->setVar('mod_demo_site_name', $helper->getConfig('demo_site_name'));
        $newModuleObj->setVar('mod_support_url', $helper->getConfig('support_url'));
        $newModuleObj->setVar('mod_support_name', $helper->getConfig('support_name'));
        $newModuleObj->setVar('mod_website_url', $helper->getConfig('website_url'));
        $newModuleObj->setVar('mod_website_name', $helper->getConfig('website_name'));
        $newModuleObj->setVar('mod_status', $helper->getConfig('status'));

        $newModuleObj->setVar('mod_admin', $helper->getConfig('display_admin'));
        $newModuleObj->setVar('mod_user', $helper->getConfig('display_user'));
        $newModuleObj->setVar('mod_search', $helper->getConfig('active_search'));
        $newModuleObj->setVar('mod_comments', $helper->getConfig('active_comments'));
        $newModuleObj->setVar('mod_notifications', $helper->getConfig('active_notifications'));
        $newModuleObj->setVar('mod_permissions', $helper->getConfig('active_permissions'));
        $newModuleObj->setVar('mod_donations', $helper->getConfig('donations'));
        $newModuleObj->setVar('mod_subversion', $helper->getConfig('subversion'));

        if ($modulesHandler->insert($newModuleObj)) {
            // get the ID of the new module
            $criteria     = new \Criteria('mod_name', $moduleNewName);
            $moduleObject = $modulesHandler->getObjects($criteria, false, true);
            $moduleId     = $moduleObject[0]->getVar('mod_id');
            $tables = self::importTables($moduleId, $moduleName);
            if (null === $tables) {
                $ret['result'] = false;
                $ret['error'] = \_AM_MODULEBUILDER_ERROR_IMPTABLES;
            } else {
                $ret['result'] = true;
                $ret['tables'] = $tables;
            }
        } else {
            $ret['result'] = false;
            $ret['error'] = \_AM_MODULEBUILDER_ERROR_MCREATE . $GLOBALS['xoopsDB']->error();
        }

        return $ret;
    }

    /**
     * @param $moduleId
     * @param $moduleName
     * @return array|null
     */
    public static function importTables($moduleId, $moduleName): ?array
    {
        $helper        = Helper::getInstance();
        $tablesHandler = $helper->getHandler('Tables');
        $fieldsHandler = $helper->getHandler('Fields');

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($moduleName);
        $moduleTables  = $module->getInfo('tables');

        $tables = null;

        if (false !== $moduleTables && is_array($moduleTables)) {
            $currentTableNumber = 0;
            $tables             = [];
            foreach ($moduleTables as $table) {
                //create a new tablesholder
                $newTable = $tablesHandler->create();
                $newTable->setVar('table_mid', $moduleId);

                $newTable->setVar('table_name', $table);
                $newTable->setVar('table_image', 'alert.png');

                //get all the fields for this table
                $importedFields = self::importFields($table);

                //set the number of fields for this table
                $countFields = count($importedFields);
                $newTable->setVar('table_nbfields', $countFields);
                $newTable->setVar('table_order', $currentTableNumber);
                $tablesHandler->insert($newTable);

                $currentFieldNumber = 0;
                foreach ($importedFields as $t) {
                    $fieldsObj = $fieldsHandler->create();
                    $fieldsObj->setVar('field_mid', $moduleId);
                    $fieldsObj->setVar('field_tid', $newTable->getVar('table_id'));
                    $fieldsObj->setVar('field_order', $currentFieldNumber);
                    $fieldsObj->setVar('field_name', $t['Field']);

                    $type = '1';
                    if (isset($t['Type'])) {
                        $types = [
                            2  => 'INT',
                            3  => 'TINYINT',
                            4  => 'MEDIUMINT',
                            5  => 'SMALLINT',
                            6  => 'FLOAT',
                            7  => 'DOUBLE',
                            8  => 'DECIMAL',
                            9  => 'SET',
                            10 => 'ENUM',
                            11 => 'EMAIL',
                            12 => 'URL',
                            13 => 'CHAR',
                            14 => 'VARCHAR',
                            15 => 'TEXT',
                            16 => 'TINYTEXT',
                            17 => 'MEDIUMTEXT',
                            18 => 'LONGTEXT',
                            19 => 'DATE',
                            20 => 'DATETIME',
                            21 => 'TIMESTAMP',
                            22 => 'TIME',
                            23 => 'YEAR',
                        ];
                        $type  = array_search(mb_strtolower($t['Type']), array_map('strtolower', $types), true);
                    }
                    $fieldsObj->setVar('field_type', $type);
                    $fieldsObj->setVar('field_value', $t['Len']);

                    $attr = '1';
                    if (isset($t['Signed'])) {
                        $attribs = [
                            2 => 'BINARY',
                            3 => 'UNSIGNED',
                            4 => 'UNSIGNED_ZEROFILL',
                            5 => 'SMALLINT',
                            6 => 'CURRENT_TIMESTAMP',
                        ];
                        $attr    = array_search(mb_strtolower($t['Signed']), array_map('strtolower', $attribs), true);
                    }
                    $fieldsObj->setVar('field_attribute', $attr);

                    //                    $fieldsObj->setVar('field_null', $t['Null'] ?? '');
                    $null = '1';
                    if ('NOT NULL' === $t['Null']) {
                        $null = '2';
                    } elseif ('NULL' === $t['Null']) {
                        $null = '3';
                    }
                    $fieldsObj->setVar('field_null', $null);
                    $fieldsObj->setVar('field_default', $t['Default']);

                    $key  = 1;
                    if (isset($t['Key'])) {
                        $keys = [
                            2 => 'PRI',
                            3 => 'UNI',
                            4 => 'KEY',
                            5 => 'IND',
                            6 => 'FUL',
                        ];
                        $key  = array_search(mb_strtolower($t['Key']), array_map('strtolower', $keys), true);
                    }
                    $fieldsObj->setVar('field_key', $key);
                    $fieldsObj->setVar('field_element', $t['Field']);

                    //if ($currentFieldNumber < $countFields - 1) {
                    //}

                    if (0 == $currentFieldNumber) {
                        if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set'])) {
                            // XoopsFormTextArea
                            $fieldsObj->setVar('field_element', '3');
                        } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar'])) {
                            //XoopsFormText
                            $fieldsObj->setVar('field_element', '2');
                        } elseif ('datetime' === $t['Type']) {
                            //XoopsFormDateTime //XoopsFormDatePicker
                            $fieldsObj->setVar('field_element', '21');
                        } elseif ('date' === $t['Type']) {
                            //XoopsFormTextDateSelect
                            $fieldsObj->setVar('field_element', '15');
                        }
                    } elseif ($currentFieldNumber > 0) {
                        if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set'])) {
                                //XoopsFormTextArea
                                $fieldsObj->setVar('field_element', '3');
                        } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar'])) {
                                //XoopsFormText
                                $fieldsObj->setVar('field_element', '2');
                            } elseif ('datetime' === $t['Type']) {
                                //XoopsFormDateTime //XoopsFormDatePicker
                                $fieldsObj->setVar('field_element', '21');
                            } elseif ('date' === $t['Type']) {
                                //XoopsFormTextDateSelect
                                $fieldsObj->setVar('field_element', '15');
                            }
                    }

                    ++$currentFieldNumber;

                    $fieldsHandler->insert($fieldsObj);
                }

                $tables[] = \_AM_MODULEBUILDER_SUCCESS_IMPTABLES . $table;

                ++$currentTableNumber;
            }
        }

        return $tables;
    }

    /**
     * @param string $tableName
     *
     * @return array
     */
    public static function importFields($tableName)
    {
        $table  = $GLOBALS['xoopsDB']->prefix((string)$tableName);
        $sql    = 'SHOW COLUMNS FROM ' . $table;
        $result = $GLOBALS['xoopsDB']->query($sql);

        if (!$result instanceof \mysqli_result) {
            \trigger_error($GLOBALS['xoopsDB']->error());
        }

        $tFields = [];
        while (false !== ($data = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $t          = [];
            $t['Field'] = $data['Field'];
            $t['Type']  = $data['Type'];

            if ('YES' === $data['Null']) {
                $t['Null'] = 'NULL';
            } else {
                $t['Null'] = 'NOT NULL';
            }
            $t['Key']     = $data['Key'];
            $t['Default'] = $data['Default'];
            $t['Extra']   = $data['Extra'];

            $t['Label'] = $data['Label'] ?? '';

            $h = mb_strpos($data['Type'], '(');
            $i = mb_strpos($data['Type'], ')');
            if (false === $h) {
                $t['Len'] = 0;
            } else {
                $t['Type'] = mb_substr($data['Type'], 0, $h);
                if ('double' === $t['Type'] || 'float' === $t['Type'] || 'real' === $t['Type']) {
                    $t['Len'] = mb_substr($data['Type'], $h + 1, $i - 1 - $h);
                } else {
                    $t['Len'] = (int)mb_substr($data['Type'], $h + 1, $i - 1 - $h);
                }
                if (mb_strlen($data['Type']) > $i) {
                    $t['Signed'] = mb_substr($data['Type'], $i + 2);
                }
            }

            $tFields[$t['Field']] = $t;
        }

        return $tFields;
    }
}
