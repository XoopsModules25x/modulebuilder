<?php

namespace XoopsModules\Modulebuilder;

use Xmf\Request;

/**
 * class Import
 */
class Import
{
    public static function importModule()
    {
        $moduleName    = Request::getString('moduleName',  'newModule', 'POST');
        $moduleNewName = Request::getString('moduleNewName',  $moduleName, 'POST');
        $moduleDirname = \preg_replace('/[^a-zA-Z0-9]\s+/', '', \mb_strtolower($moduleNewName));

        $helper         = Helper::getInstance();
        $modulesHandler = $helper->getHandler('Modules');
        $newModule      = $modulesHandler->create();

        $newModule->setVar('mod_name', $moduleNewName);
        $newModule->setVar('mod_release', date('Y-m-d'));
        $newModule->setVar('mod_dirname', $moduleDirname);

        $newModule->setVar('mod_version', $helper->getConfig('version')); //$GLOBALS['xoopsModuleConfig']['version']);

        $newModule->setVar('mod_since', $helper->getConfig('since'));
        $newModule->setVar('mod_min_php', $helper->getConfig('min_php'));
        $newModule->setVar('mod_min_xoops', $helper->getConfig('min_xoops'));
        $newModule->setVar('mod_min_admin', $helper->getConfig('min_admin'));
        $newModule->setVar('mod_min_mysql', $helper->getConfig('min_mysql'));

        $newModule->setVar('mod_description', $helper->getConfig('description'));
        $newModule->setVar('mod_author', $helper->getConfig('author'));
        $newModule->setVar('mod_author_mail', $helper->getConfig('author_email'));
        $newModule->setVar('mod_author_website_url', $helper->getConfig('author_website_url'));
        $newModule->setVar('mod_author_website_name', $helper->getConfig('author_website_name'));
        $newModule->setVar('mod_credits', $helper->getConfig('credits'));
        $newModule->setVar('mod_license', $helper->getConfig('license'));
        $newModule->setVar('mod_display_admin', $helper->getConfig('display_admin'));
        $newModule->setVar('mod_display_user', $helper->getConfig('display_user'));
        $newModule->setVar('mod_active_search', $helper->getConfig('active_search'));
        $newModule->setVar('mod_active_comments', $helper->getConfig('active_comments'));
        $newModule->setVar('mod_release_info', $helper->getConfig('release_info'));
        $newModule->setVar('mod_release_file', $helper->getConfig('release_file'));
        $newModule->setVar('mod_manual', $helper->getConfig('manual'));
        $newModule->setVar('mod_manual_file', $helper->getConfig('manual_file'));
        $newModule->setVar('mod_image', 'empty.png');
        $newModule->setVar('mod_demo_site_url', $helper->getConfig('demo_site_url'));
        $newModule->setVar('mod_demo_site_name', $helper->getConfig('demo_site_name'));
        $newModule->setVar('mod_support_url', $helper->getConfig('support_url'));
        $newModule->setVar('mod_support_name', $helper->getConfig('support_name'));
        $newModule->setVar('mod_website_url', $helper->getConfig('website_url'));
        $newModule->setVar('mod_website_name', $helper->getConfig('website_name'));
        $newModule->setVar('mod_status', $helper->getConfig('status'));

        $newModule->setVar('mod_admin', $helper->getConfig('display_admin'));
        $newModule->setVar('mod_user', $helper->getConfig('display_user'));
        $newModule->setVar('mod_search', $helper->getConfig('active_search'));
        $newModule->setVar('mod_comments', $helper->getConfig('active_comments'));
        $newModule->setVar('mod_notifications', $helper->getConfig('active_notifications'));
        $newModule->setVar('mod_permissions', $helper->getConfig('active_permissions'));
        $newModule->setVar('mod_donations', $helper->getConfig('donations'));
        $newModule->setVar('mod_subversion', $helper->getConfig('subversion'));

        if ($modulesHandler->insert($newModule)) {
            // get the ID of the new module
            $criteria     = new \Criteria('mod_name', $moduleNewName);
            $moduleObject = $modulesHandler->getObjects($criteria, false, true);
            $moduleId     = $moduleObject[0]->getVar('mod_id');
            self::importTables($moduleId, $moduleName);
        }
    }

    /**
     * @param $moduleId
     * @param $moduleName
     */
    public static function importTables($moduleId, $moduleName)
    {
        $helper        = Helper::getInstance();
        $tablesHandler = $helper->getHandler('Tables');
        $fieldsHandler = $helper->getHandler('Fields');

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($moduleName);
        $moduleTables  = $module->getInfo('tables');

        if (false !== $moduleTables && is_array($moduleTables)) {
            $currentTableNumber = 0;
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
                        $type = array_search(strtolower($t['Type']), array_map('strtolower', $types));
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
                        $attr    = array_search(strtolower($t['Signed']), array_map('strtolower', $attribs));
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
                        $key = array_search(strtolower($t['Key']), array_map('strtolower', $keys));
                    }
                    $fieldsObj->setVar('field_key', $key);
                    $fieldsObj->setVar('field_element', $t['Field']);

                    if ($currentFieldNumber < $countFields - 1) {
                        //
                    }

                    if (0 == $currentFieldNumber) {
                        if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set',])) {
                            // XoopsFormTextArea
                            $fieldsObj->setVar('field_element', '3');
                        } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar',])) {
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
                            if (in_array($t['Type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext', 'enum', 'set',])) {
                                //XoopsFormTextArea
                                $fieldsObj->setVar('field_element', '3');
                            } elseif (in_array($t['Type'], ['int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'real', 'char', 'varchar',])) {
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

                echo $table . ' Table has been imported <br>';

                ++$currentTableNumber;
            }
        }
    }

    /**
     * @param $tableName
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
        while ($data = $GLOBALS['xoopsDB']->fetchBoth($result)) {
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

            $h = strpos($data['Type'], '(');
            $i = strpos($data['Type'], ')');
            if (false === $h) {
                $t['Len'] = 0;
            } else {
                $t['Type'] = substr($data['Type'], 0, $h);
                if ('double' === $t['Type'] || 'float' === $t['Type'] || 'real' === $t['Type']) {
                    $t['Len'] = substr($data['Type'], $h + 1, $i - 1 - $h);
                } else {
                    $t['Len'] = (int)substr($data['Type'], $h + 1, $i - 1 - $h);
                }
                if (strlen($data['Type']) > $i) {
                    $t['Signed'] = substr($data['Type'], $i + 2);
                }
            }

            $tFields[$t['Field']] = $t;
        }
        return $tFields;
    }
}
