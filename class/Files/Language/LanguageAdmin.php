<?php

namespace XoopsModules\Modulebuilder\Files\Language;

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
 * Class LanguageAdmin.
 */
class LanguageAdmin extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $defines = null;


    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->defines = LanguageDefines::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageAdmin
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
     * @param        $table
     * @param string $tables
     * @param string $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @public function getLanguageAdminIndex
     * @param string $language
     * @param array $tables
     * @return string
     */
    public function getLanguageAdminIndex($language, $tables)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret = $this->defines->getBlankLine();
        $ret .= $pc->getPhpCodeIncludeDir("'common.php'",'', true, true, 'include');
        $ret .= $this->defines->getBlankLine();
        $ret .= $this->defines->getAboveHeadDefines('Admin Index');
        $ret .= $this->defines->getDefine($language, 'STATISTICS', 'Statistics');
        $ret .= $this->defines->getAboveDefines('There are');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $stlTableName = mb_strtolower($tableName);
            $ret          .= $this->defines->getDefine($language, "THEREARE_{$stuTableName}", "There are <span class='bold'>%s</span> {$stlTableName} in the database", true);
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminPages
     * @param string $language
     * @param array $tables
     * @return string
     */
    public function getLanguageAdminPages($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Files');
        $ret .= $this->defines->getAboveDefines('There aren\'t');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $stlTableName = mb_strtolower($tableName);
            $ret          .= $this->defines->getDefine($language, "THEREARENT_{$stuTableName}", "There aren't {$stlTableName}", true);
        }
        $ret .= $this->defines->getAboveDefines('Save/Delete');
        $ret .= $this->defines->getDefine($language, 'FORM_OK', 'Successfully saved');
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE_OK', 'Successfully deleted');
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->defines->getAboveDefines('Buttons');

        foreach (array_keys($tables) as $t) {
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $stuTableSoleName = mb_strtoupper($tableSoleName);
            $ucfTableSoleName = ucfirst($tableSoleName);
            $ret              .= $this->defines->getDefine($language, "ADD_{$stuTableSoleName}", "Add New {$ucfTableSoleName}");
        }
        $ret .= $this->defines->getAboveDefines('Lists');

        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $ucfTableName = ucfirst($tableName);
            $ret          .= $this->defines->getDefine($language, "{$stuTableName}_LIST", "List of {$ucfTableName}");
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminClass
     * @param string $language
     * @param array $tables
     * @return string
     */
    public function getLanguageAdminClass($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Classes');
        $fieldStatus          = 0;
        $fieldSampleListValue = 0;
        $tableBroken          = 0;
        foreach (array_keys($tables) as $t) {
            $tableId          = $tables[$t]->getVar('table_id');
            $tableMid         = $tables[$t]->getVar('table_mid');
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $tableBroken      = $tables[$t]->getVar('table_broken');
            $ucfTableSoleName = ucfirst($tableSoleName);

            $fields      = $this->getTableFields($tableMid, $tableId);
            $fieldInForm = 0;
            foreach (array_keys($fields) as $f) {
                if ($fieldInForm < $fields[$f]->getVar('field_inform')) {
                    $fieldInForm = $fields[$f]->getVar('field_inform');
                }
            }
            if (1 == $fieldInForm) {
                $ret .= $this->defines->getAboveDefines("{$ucfTableSoleName} add/edit");
                $ret .= $this->defines->getDefine($language, "{$tableSoleName}_ADD", "Add {$ucfTableSoleName}");
                $ret .= $this->defines->getDefine($language, "{$tableSoleName}_EDIT", "Edit {$ucfTableSoleName}");
            }
            $ret .= $this->defines->getAboveDefines("Elements of {$ucfTableSoleName}");

            foreach (array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $rpFieldName = $this->getRightString($fieldName);
                if ($fieldElement > 16) {
                    $fieldElements    = Modulebuilder\Helper::getInstance()->getHandler('Fieldelements')->get($fieldElement);
                    $fieldElementName = $fieldElements->getVar('fieldelement_name');
                    $fieldNameDesc    = mb_substr($fieldElementName, mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                    $fieldNameDesc    = str_replace(': ', '', $fieldNameDesc);
                } else {
                    $fieldNameDesc = false !== mb_strpos($rpFieldName, '_') ? str_replace('_', ' ', ucfirst($rpFieldName)) : ucfirst($rpFieldName);
                }
                $ret          .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName, $fieldNameDesc);

                switch ($fieldElement) {
                    case 10:
                        $ret .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in frameworks images: %s");
                        break;
                    case 12:
                        $ret .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in uploads");
                        break;
                    case 11:
                    case 13:
                    case 14:
                        $ret .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in %s :");
                        break;
                    case 16:
                        $fieldStatus++;
                        break;
                    case 20:
                    case 22:
                        $fieldSampleListValue++;
                        break;
                }
                if (16 === (int)$fieldElement) {
                    $fieldStatus++;
                }
                if (20 === (int)$fieldElement || 20 === (int)$fieldElement) {
                    $fieldSampleListValue++;
                }
            }
        }
        $ret .= $this->defines->getAboveDefines('General');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD', 'Upload file');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_NEW', 'Upload new file: ');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_SIZE', 'Max file size: ');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_SIZE_MB', 'MB');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_IMG_WIDTH', 'Max image width: ');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_IMG_HEIGHT', 'Max image height: ');
        $ret .= $this->defines->getDefine($language, 'FORM_IMAGE_PATH', 'Files in %s :');
        $ret .= $this->defines->getDefine($language, 'FORM_ACTION', 'Action');
        $ret .= $this->defines->getDefine($language, 'FORM_EDIT', 'Modification');
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE', 'Clear');
        if ($fieldStatus > 0) {
            $ret .= $this->defines->getAboveDefines('Status');
            $ret .= $this->defines->getDefine($language, 'STATUS_NONE', 'No status');
            $ret .= $this->defines->getDefine($language, 'STATUS_OFFLINE', 'Offline');
            $ret .= $this->defines->getDefine($language, 'STATUS_SUBMITTED', 'Submitted');
            $ret .= $this->defines->getDefine($language, 'STATUS_APPROVED', 'Approved');
            $ret .= $this->defines->getDefine($language, 'STATUS_BROKEN', 'Broken');
        }
        if ($tableBroken > 0) {
            $ret .= $this->defines->getAboveDefines('Broken');
            $ret .= $this->defines->getDefine($language, 'BROKEN_RESULT', 'Broken items in table %s');
            $ret .= $this->defines->getDefine($language, 'BROKEN_NODATA', 'No broken items in table %s');
            $ret .= $this->defines->getDefine($language, 'BROKEN_TABLE', 'Table');
            $ret .= $this->defines->getDefine($language, 'BROKEN_KEY', 'Key field');
            $ret .= $this->defines->getDefine($language, 'BROKEN_KEYVAL', 'Key value');
            $ret .= $this->defines->getDefine($language, 'BROKEN_MAIN', 'Info main');
        }
        if ($fieldSampleListValue > 0) {
            $ret .= $this->defines->getAboveDefines('Sample List Values');
            $ret .= $this->defines->getDefine($language, 'LIST_1', 'Sample List Value 1');
            $ret .= $this->defines->getDefine($language, 'LIST_2', 'Sample List Value 2');
            $ret .= $this->defines->getDefine($language, 'LIST_3', 'Sample List Value 3');
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminPermissions
     * @param string $language
     * @return string
     */
    public function getLanguageAdminPermissions($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Permissions');
        $ret .= $this->defines->getAboveDefines('Permissions');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL', 'Permissions global');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_DESC', 'Permissions global to check type of.');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_4', 'Permissions global to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_8', 'Permissions global to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_16', 'Permissions global to view');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_APPROVE', 'Permissions to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_APPROVE_DESC', 'Permissions to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_SUBMIT', 'Permissions to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_SUBMIT_DESC', 'Permissions to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_VIEW', 'Permissions to view');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_VIEW_DESC', 'Permissions to view');
        $ret .= $this->defines->getDefine($language, 'NO_PERMISSIONS_SET', 'No permission set');

        return $ret;
    }

    /**
     * @public function getLanguageAdminFoot
     * @param string $language
     * @return string
     */
    public function getLanguageAdminFoot($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Others');
        $ret .= $this->defines->getDefine($language, 'MAINTAINEDBY', ' is maintained by ');
        $ret .= $this->defines->getBelowDefines('End');
        $ret .= $this->defines->getBlankLine();

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $tablePermissions = [];
        foreach (array_keys($tables) as $t) {
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
        }
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module);
        if (is_array($tables)) {
            $content .= $this->getLanguageAdminIndex($language, $tables);
            $content .= $this->getLanguageAdminPages($language, $tables);
            $content .= $this->getLanguageAdminClass($language, $tables);
        }
        if (in_array(1, $tablePermissions)) {
            $content .= $this->getLanguageAdminPermissions($language);
        }
        $content .= $this->getLanguageAdminFoot($language);

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
