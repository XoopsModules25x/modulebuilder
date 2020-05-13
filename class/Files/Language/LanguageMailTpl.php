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
 * Class LanguageMailTpl.
 */
class LanguageMailTpl extends Files\CreateFile
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
     * @param null
     * @return LanguageMailTpl
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
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getTemplateDummy
     * @param $tableSoleName
     * @param $stuTableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateDummy($tableSoleName, $stuTableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Content Dummy');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('A new ' . $tableSoleName . ' "{' . $stuFieldMain . '}" has been added at {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You can view this ' . $tableSoleName . ' here:');
        $ret .= $this->getSimpleString('{' . $stuTableSoleName . '_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a new ' . $tableSoleName . ' is added to our site.');
        $ret .= $this->getSimpleString('');

        return $ret;
    }

    /**
     * @public function getTemplateTableNew
     * @param $tableSoleName
     * @param $stuTableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateTableNew($tableSoleName, $stuTableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Notification New');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('A new ' . $tableSoleName . ' "{' . $stuFieldMain . '}" has been added at {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You can view this ' . $tableSoleName . ' here:');
        $ret .= $this->getSimpleString('{' . $stuTableSoleName . '_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a new ' . $tableSoleName . ' is added to our site.');

        return $ret;
    }

    /**
     * @public function getTemplateTableModify
     * @param $tableSoleName
     * @param $stuTableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateTableModify($tableSoleName, $stuTableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Notification Modify');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('The ' . $tableSoleName . ' "{' . $stuFieldMain . '}" has been modified at {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You can view this ' . $tableSoleName . ' here:');
        $ret .= $this->getSimpleString('{' . $stuTableSoleName . '_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a ' . $tableSoleName . ' is modified on our site.');

        return $ret;
    }

    /**
     * @public function getTemplateTableDelete
     * @param $tableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateTableDelete($tableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Notification Delete');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('The ' . $tableSoleName . ' "{' . $stuFieldMain . '}" has been deleted from {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a ' . $tableSoleName . ' is deleted from our site.');

        return $ret;
    }

    /**
     * @public function getTemplateTableApprove
     * @param $tableSoleName
     * @param $stuTableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateTableApprove($tableSoleName, $stuTableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Notification Approve');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('A new ' . $tableSoleName . ' "{' . $stuFieldMain . '}" is waiting for approval at {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You can view this ' . $tableSoleName . ' here:');
        $ret .= $this->getSimpleString('{' . $stuTableSoleName . '_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a ' . $tableSoleName . ' is waitung for approval on our site.');

        return $ret;
    }

    /**
     * @public function getTemplateTableApprove
     * @param $tableSoleName
     * @param $stuTableSoleName
     * @param $stuFieldMain
     * @param $line
     * @return string
     */
    public function getTemplateTableBroken($tableSoleName, $stuTableSoleName, $stuFieldMain, $line)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret = $pc->getPhpCodeCommentLine('Templates Mail Notification Broken');
        $ret .= $this->getSimpleString('Hello {X_UNAME},');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('The ' . $tableSoleName . ' "{' . $stuFieldMain . '}" has been notified as broken at {X_SITENAME}.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You can view this ' . $tableSoleName . ' here:');
        $ret .= $this->getSimpleString('{' . $stuTableSoleName . '_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('You are receiving this message because you selected to be notified when a ' . $tableSoleName . ' is notified as broken on our site.');

        return $ret;
    }

    /**
     * @public function getTemplateTableApprove
     * @param $line
     * @return string
     */
    public function getTemplateTableFooter($line)
    {

        $ret = $this->getSimpleString('');
        $ret .= $this->getSimpleString('If this is an error or you wish not to receive further such notifications, please update your subscriptions by visiting the link below:');
        $ret .= $this->getSimpleString('{X_UNSUBSCRIBE_URL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('Please do not reply to this message.');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString('{X_SITENAME} ({X_SITEURL})');
        $ret .= $this->getSimpleString('webmaster');
        $ret .= $this->getSimpleString('{X_ADMINMAIL}');
        $ret .= $this->getSimpleString('');
        $ret .= $this->getSimpleString($line);

        return $ret;
    }


    /**
     * @public   function renderFile
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $table         = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $stuTableSoleName = mb_strtoupper($tableSoleName);
        $fieldMain  = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $stuFieldMain = mb_strtoupper($fieldMain);

        $line = '------------------------------------------------------------------';
        $content = '';
        switch ($filename) {
            case 'category_new_notify.tpl':
                //$content .= $this->getTemplateCategoryNew('categories', 'CATEGORY', 'CATEGORY', $line);
                //break;
            case 'default':
            default:
                $content .= $this->getTemplateDummy('story', 'STORY', 'STORY_NAME', $line);
                break;
            case $tableName . '_new_notify.tpl':
                $content .= $this->getTemplateTableNew($tableSoleName, $stuTableSoleName, $stuFieldMain, $line);
                break;
            case $tableName . '_modify_notify.tpl':
                $content .= $this->getTemplateTableModify($tableSoleName, $stuTableSoleName, $stuFieldMain, $line);
                break;
            case $tableName . '_delete_notify.tpl':
                $content .= $this->getTemplateTableDelete($tableSoleName, $stuFieldMain, $line);
                break;
            case $tableName . '_approve_notify.tpl':
                $content .= $this->getTemplateTableApprove($tableSoleName, $stuTableSoleName, $stuFieldMain, $line);
                break;
            case $tableName . '_broken_notify.tpl':
                $content .= $this->getTemplateTableBroken($tableSoleName, $stuTableSoleName, $stuFieldMain, $line);
                break;
        }
        $content .= $this->getTemplateTableFooter($line);

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
