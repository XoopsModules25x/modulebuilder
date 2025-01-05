<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Language;

use XoopsModules\Modulebuilder\{
    Files,
    Constants,
    Helper
};

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
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * Class LanguageAdmin.
 */
class LanguageAdmin extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $ld = null;
    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->ld = LanguageDefines::getInstance();
        $this->pc = Files\CreatePhpCode::getInstance();
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
    public function write($module, $table, $tables, $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @public function getLanguageAdminIndex
     * @param string $language
     * @param array  $tables
     * @return string
     */
    public function getLanguageAdminIndex($language, $tables)
    {
        $thereare  = '';
        $tableUser = 0;
        foreach (\array_keys($tables) as $t) {
            if (1 == (int)$tables[$t]->getVar('table_user')) {
                $tableUser = 1;
            }
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = \mb_strtoupper($tableName);
            $stlTableName = \mb_strtolower($tableName);
            $thereare     .= $this->ld->getDefine($language, "THEREARE_{$stuTableName}", "There are <span class='bold'>%s</span> {$stlTableName} in the database", true);
        }
        $ret = $this->ld->getBlankLine();
        $ret .= $this->pc->getPhpCodeIncludeDir('__DIR__', 'common', true);
        if ($tableUser) {
            $ret .= $this->pc->getPhpCodeIncludeDir('__DIR__', 'main', true);
        }
        $ret .= $this->ld->getBlankLine();
        $ret .= $this->ld->getAboveHeadDefines('Admin Index');
        $ret .= $this->ld->getDefine($language, 'STATISTICS', 'Statistics');
        $ret .= $this->ld->getAboveDefines('There are');
        $ret .= $thereare;

        return $ret;
    }

    /**
     * @public function getLanguageAdminPages
     * @param string $language
     * @param array  $tables
     * @return string
     */
    public function getLanguageAdminPages($language, $tables)
    {
        $ret = $this->ld->getAboveHeadDefines('Admin Files');
        $ret .= $this->ld->getAboveDefines('There aren\'t');
        foreach (\array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = \mb_strtoupper($tableName);
            $stlTableName = \mb_strtolower($tableName);
            $ret          .= $this->ld->getDefine($language, "THEREARENT_{$stuTableName}", "There aren't {$stlTableName}", true);
        }
        $ret .= $this->ld->getAboveDefines('Save/Delete');
        $ret .= $this->ld->getDefine($language, 'FORM_OK', 'Successfully saved');
        $ret .= $this->ld->getDefine($language, 'FORM_DELETE_OK', 'Successfully deleted');
        $ret .= $this->ld->getDefine($language, 'FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->ld->getDefine($language, 'FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->ld->getAboveDefines('Buttons');

        foreach (\array_keys($tables) as $t) {
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $stuTableSoleName = \mb_strtoupper($tableSoleName);
            $ucfTableSoleName = \ucfirst($tableSoleName);
            $ret              .= $this->ld->getDefine($language, "ADD_{$stuTableSoleName}", "Add New {$ucfTableSoleName}");
        }
        $ret .= $this->ld->getAboveDefines('Lists');

        foreach (\array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = \mb_strtoupper($tableName);
            $ucfTableName = \ucfirst($tableName);
            $ret          .= $this->ld->getDefine($language, "LIST_{$stuTableName}", "List of {$ucfTableName}");
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminClass
     * @param string $language
     * @param array  $tables
     * @return string
     */
    public function getLanguageAdminClass($language, $tables)
    {
        $ret                  = $this->ld->getAboveHeadDefines('Admin Classes');
        $fieldStatus          = 0;
        $fieldSampleListValue = 0;
        $tableBroken          = 0;
        foreach (\array_keys($tables) as $t) {
            $tableId          = $tables[$t]->getVar('table_id');
            $tableMid         = $tables[$t]->getVar('table_mid');
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $tableBroken      = $tables[$t]->getVar('table_broken');
            $ucfTableSoleName = \ucfirst($tableSoleName);

            $fields      = $this->getTableFields($tableMid, $tableId);
            $fieldInForm = 0;
            foreach (\array_keys($fields) as $f) {
                if ($fieldInForm < $fields[$f]->getVar('field_inform')) {
                    $fieldInForm = $fields[$f]->getVar('field_inform');
                }
            }
            if (1 == $fieldInForm) {
                $ret .= $this->ld->getAboveDefines("{$ucfTableSoleName} add/edit");
                $ret .= $this->ld->getDefine($language, "{$tableSoleName}_ADD", "Add {$ucfTableSoleName}");
                $ret .= $this->ld->getDefine($language, "{$tableSoleName}_EDIT", "Edit {$ucfTableSoleName}");
            }
            $ret .= $this->ld->getAboveDefines("Elements of {$ucfTableSoleName}");

            foreach (\array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $rpFieldName  = $this->getRightString($fieldName);
                if ($fieldElement >= Constants::FIRST_FIELDELEMENT_TABLE) {
                    $fieldElements    = Helper::getInstance()->getHandler('Fieldelements')->get($fieldElement);
                    $fieldElementName = $fieldElements->getVar('fieldelement_name');
                    $fieldNameDesc    = mb_substr($fieldElementName, \mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                    $fieldNameDesc    = \str_replace(': ', '', $fieldNameDesc);
                } else {
                    $fieldNameDesc = false !== mb_strpos($rpFieldName, '_') ? \str_replace('_', ' ', \ucfirst($rpFieldName)) : \ucfirst($rpFieldName);
                }
                $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName, $fieldNameDesc);

                switch ($fieldElement) {
                    case Constants::FIELD_ELE_IMAGELIST:
                        $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in frameworks images: %s");
                        break;
                    case Constants::FIELD_ELE_URLFILE:
                        $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in uploads");
                        break;
                    case Constants::FIELD_ELE_SELECTFILE:
                    case Constants::FIELD_ELE_UPLOADIMAGE:
                    case Constants::FIELD_ELE_UPLOADFILE:
                        $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_UPLOADS', "{$fieldNameDesc} in %s :");
                        break;
                    case Constants::FIELD_ELE_SELECTSTATUS:
                        $fieldStatus++;
                        break;
                    case Constants::FIELD_ELE_RADIO:
                    case Constants::FIELD_ELE_SELECTCOMBO:
                        $fieldSampleListValue++;
                        break;
                    case Constants::FIELD_ELE_RADIO_ONOFFLINE:
                        $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_OFFLINE', 'Offline');
                        $ret .= $this->ld->getDefine($language, $tableSoleName . '_' . $rpFieldName . '_ONLINE', 'Online');
                        break;
                }
                if (16 === (int)$fieldElement) {
                    $fieldStatus++;
                }
                if (20 === (int)$fieldElement || 22 === (int)$fieldElement) {
                    $fieldSampleListValue++;
                }
            }
        }
        $ret .= $this->ld->getAboveDefines('General');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD', 'Upload file');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD_NEW', 'Upload new file: ');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD_SIZE', 'Max file size: ');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD_SIZE_MB', 'MB');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD_IMG_WIDTH', 'Max image width: ');
        $ret .= $this->ld->getDefine($language, 'FORM_UPLOAD_IMG_HEIGHT', 'Max image height: ');
        $ret .= $this->ld->getDefine($language, 'FORM_IMAGE_PATH', 'Files in %s :');
        $ret .= $this->ld->getDefine($language, 'FORM_ACTION', 'Action');
        $ret .= $this->ld->getDefine($language, 'FORM_EDIT', 'Modification');
        $ret .= $this->ld->getDefine($language, 'FORM_DELETE', 'Clear');
        if ($fieldStatus > 0) {
            $ret .= $this->ld->getAboveDefines('Status');
            $ret .= $this->ld->getDefine($language, 'STATUS_NONE', 'No status');
            $ret .= $this->ld->getDefine($language, 'STATUS_OFFLINE', 'Offline');
            $ret .= $this->ld->getDefine($language, 'STATUS_SUBMITTED', 'Submitted');
            $ret .= $this->ld->getDefine($language, 'STATUS_APPROVED', 'Approved');
            $ret .= $this->ld->getDefine($language, 'STATUS_BROKEN', 'Broken');
        }
        if ($tableBroken > 0) {
            $ret .= $this->ld->getAboveDefines('Broken');
            $ret .= $this->ld->getDefine($language, 'BROKEN_RESULT', 'Broken items in table %s');
            $ret .= $this->ld->getDefine($language, 'BROKEN_NODATA', 'No broken items in table %s');
            $ret .= $this->ld->getDefine($language, 'BROKEN_TABLE', 'Table');
            $ret .= $this->ld->getDefine($language, 'BROKEN_KEY', 'Key field');
            $ret .= $this->ld->getDefine($language, 'BROKEN_KEYVAL', 'Key value');
            $ret .= $this->ld->getDefine($language, 'BROKEN_MAIN', 'Info main');
        }
        if ($fieldSampleListValue > 0) {
            $ret .= $this->ld->getAboveDefines('Sample List Values');
            $ret .= $this->ld->getDefine($language, 'LIST_1', 'Sample List Value 1');
            $ret .= $this->ld->getDefine($language, 'LIST_2', 'Sample List Value 2');
            $ret .= $this->ld->getDefine($language, 'LIST_3', 'Sample List Value 3');
        }
        $ret .= $this->ld->getAboveDefines('Clone feature');
        $ret .= $this->ld->getDefine($language, 'CLONE', 'Clone');
        $ret .= $this->ld->getDefine($language, 'CLONE_DSC', 'Cloning a module has never been this easy! Just type in the name you want for it and hit submit button!');
        $ret .= $this->ld->getDefine($language, 'CLONE_TITLE', 'Clone %s');
        $ret .= $this->ld->getDefine($language, 'CLONE_NAME', 'Choose a name for the new module');
        $ret .= $this->ld->getDefine($language, 'CLONE_NAME_DSC', 'Do not use special characters! <br>Do not choose an existing module dirname or database table name!');
        $ret .= $this->ld->getDefine($language, 'CLONE_INVALIDNAME', 'ERROR: Invalid module name, please try another one!');
        $ret .= $this->ld->getDefine($language, 'CLONE_EXISTS', 'ERROR: Module name already taken, please try another one!');
        $ret .= $this->ld->getDefine($language, 'CLONE_CONGRAT', 'Congratulations! %s was sucessfully created!<br>You may want to make changes in language files.');
        $ret .= $this->ld->getDefine($language, 'CLONE_IMAGEFAIL', 'Attention, we failed creating the new module logo. Please consider modifying assets/images/logo_module.png manually!');
        $ret .= $this->ld->getDefine($language, 'CLONE_FAIL', 'Sorry, we failed in creating the new clone. Maybe you need to temporally set write permissions (CHMOD 777) to modules folder and try again.');

        return $ret;
    }

    /**
     * @public function getLanguageAdminPermissions
     * @param string $language
     * @return string
     */
    public function getLanguageAdminPermissions($language)
    {
        $ret = $this->ld->getAboveHeadDefines('Admin Permissions');
        $ret .= $this->ld->getAboveDefines('Permissions');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_GLOBAL', 'Permissions global');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_GLOBAL_DESC', 'Permissions global to check type of.');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_GLOBAL_4', 'Permissions global to approve');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_GLOBAL_8', 'Permissions global to submit');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_GLOBAL_16', 'Permissions global to view');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_APPROVE', 'Permissions to approve');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_APPROVE_DESC', 'Permissions to approve');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_SUBMIT', 'Permissions to submit');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_SUBMIT_DESC', 'Permissions to submit');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_VIEW', 'Permissions to view');
        $ret .= $this->ld->getDefine($language, 'PERMISSIONS_VIEW_DESC', 'Permissions to view');
        $ret .= $this->ld->getDefine($language, 'NO_PERMISSIONS_SET', 'No permission set');

        return $ret;
    }

    /**
     * @public function getLanguageAdminFoot
     * @param string $language
     * @return string
     */
    public function getLanguageAdminFoot($language)
    {
        $ret = $this->ld->getAboveHeadDefines('Admin Others');
        $ret .= $this->ld->getDefine($language, 'ABOUT_MAKE_DONATION', 'Submit');
        $ret .= $this->ld->getDefine($language, 'SUPPORT_FORUM', 'Support Forum');
        $ret .= $this->ld->getDefine($language, 'DONATION_AMOUNT', 'Donation Amount');
        $ret .= $this->ld->getDefine($language, 'MAINTAINEDBY', ' is maintained by ');
        $ret .= $this->ld->getBelowDefines('End');
        $ret .= $this->ld->getBlankLine();

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module           = $this->getModule();
        $tables           = $this->getTableTables($module->getVar('mod_id'));
        $tablePermissions = [];
        foreach (\array_keys($tables) as $t) {
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
        }
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM', '', false);
        $content       = $this->getHeaderFilesComments($module);
        if (\is_array($tables)) {
            $content .= $this->getLanguageAdminIndex($language, $tables);
            $content .= $this->getLanguageAdminPages($language, $tables);
            $content .= $this->getLanguageAdminClass($language, $tables);
        }
        if (\in_array(1, $tablePermissions)) {
            $content .= $this->getLanguageAdminPermissions($language);
        }
        $content .= $this->getLanguageAdminFoot($language);

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
