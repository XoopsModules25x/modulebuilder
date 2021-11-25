<?php

namespace XoopsModules\Modulebuilder\Files\Admin;

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */

/**
 * Class AdminPermissions.
 */
class AdminPermissions extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $axc = null;

    /**
     * @var mixed
     */
    private $xc = null;

    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @var mixed
     */
    private $cxc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->axc = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
        $this->cxc = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return AdminPermissions
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
     * @param string $module
     * @param mixed  $tables
     * @param string $filename
     *
     * @return null
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
        return null;
    }

    /**
     * @private function getPermissionsHeader
     *
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsHeader($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tables        = $this->getTableTables($module->getVar('mod_id'));
        $tableNames    = [];
        foreach (\array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableNames[] = $tables[$t]->getVar('table_name');
            }
        }
        $ret           = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret           .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret           .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret           .= $this->getRequire();
        $ret           .= $this->pc->getPhpCodeBlankLine();
        $ret           .= $this->pc->getPhpCodeCommentLine('Template Index');
        $ret           .= $this->axc->getAdminTemplateMain($moduleDirname, 'permissions');
        $ret           .= $this->xc->getXcXoopsTplAssign('navigation', "\$adminObject->displayNavigation('permissions.php')");
        $ret           .= $this->pc->getPhpCodeBlankLine();
        $ret           .= $this->xc->getXcXoopsRequest('op', 'op', 'global', 'Cmd');
        $ret           .= $this->pc->getPhpCodeBlankLine();
        $ret           .= $this->pc->getPhpCodeCommentLine('Get Form');
        $ret           .= $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret           .= $this->xc->getXcXoopsLoad('XoopsFormLoader');
        $optionsSelect['global'] = "{$language}PERMISSIONS_GLOBAL";
        foreach ($tableNames as $tableName) {
            $ucfTablename = \ucfirst($tableName);
            $optionsSelect["approve_{$tableName}"] = "{$language}PERMISSIONS_APPROVE . ' {$ucfTablename}'";
            $optionsSelect["submit_{$tableName}"] = "{$language}PERMISSIONS_SUBMIT . ' {$ucfTablename}'";
            $optionsSelect["view_{$tableName}"] = "{$language}PERMISSIONS_VIEW . ' {$ucfTablename}'";
        }
        $formSelect    = $this->xc->getXoopsFormSelectExtraOptions('formSelect', '\'\'', 'op', $optionsSelect, 'onchange="document.fselperm.submit()"');
        $ret           .= $this->cxc->getXoopsSimpleForm('permTableForm', 'formSelect', $formSelect, '\'\'', 'fselperm', 'permissions');

        return $ret;
    }

    /**
     * @private function getPermissionsSwitch
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsSwitch($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tables        = $this->getTableTables($module->getVar('mod_id'));
        $t = "\t\t";
        $n = "\n";
        $cases['global']= [
                "{$t}\$formTitle = {$language}PERMISSIONS_GLOBAL;{$n}",
                "{$t}\$permName = '{$moduleDirname}_ac';{$n}",
                "{$t}\$permDesc = {$language}PERMISSIONS_GLOBAL_DESC;{$n}",
                "{$t}\$globalPerms = ['4' => {$language}PERMISSIONS_GLOBAL_4, '8' => {$language}PERMISSIONS_GLOBAL_8, '16' => {$language}PERMISSIONS_GLOBAL_16 ];{$n}",
                ];
        foreach (\array_keys($tables) as $i) {
            if (1 == $tables[$i]->getVar('table_permissions')) {
                $tableName = $tables[$i]->getVar('table_name');
                $ucfTablename = \ucfirst($tableName);
                $cases["approve_{$tableName}"] = [
                    "{$t}\$formTitle = {$language}PERMISSIONS_APPROVE;{$n}",
                    "{$t}\$permName = '{$moduleDirname}_approve_{$tableName}';{$n}",
                    "{$t}\$permDesc = {$language}PERMISSIONS_APPROVE_DESC . ' {$ucfTablename}';{$n}",
                    "{$t}\$handler = \$helper->getHandler('{$tableName}');{$n}",
                ];
                $cases["submit_{$tableName}"] = [
                    "{$t}\$formTitle = {$language}PERMISSIONS_SUBMIT;{$n}",
                    "{$t}\$permName = '{$moduleDirname}_submit_{$tableName}';{$n}",
                    "{$t}\$permDesc = {$language}PERMISSIONS_SUBMIT_DESC . ' {$ucfTablename}';{$n}",
                    "{$t}\$handler = \$helper->getHandler('{$tableName}');{$n}",
                ];
                $cases["view_{$tableName}"] = [
                    "{$t}\$formTitle = {$language}PERMISSIONS_VIEW;{$n}",
                    "{$t}\$permName = '{$moduleDirname}_view_{$tableName}';{$n}",
                    "{$t}\$permDesc = {$language}PERMISSIONS_VIEW_DESC . ' {$ucfTablename}';{$n}",
                    "{$t}\$handler = \$helper->getHandler('{$tableName}');{$n}",
                ];
            }
        }
        $contentSwitch = $this->pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $this->pc->getPhpCodeSwitch('op', $contentSwitch);
    }

    /**
     * @private function getPermissionsBody
     *
     * @param string $module
     * @param string $language
     *
     * @return string
     */
    private function getPermissionsBody($module, $language)
    {
        $tables   = $this->getTableTables($module->getVar('mod_id'));

        $ret      = $this->xc->getXcGetVar('moduleId', 'xoopsModule', 'mid');
        $ret      .= $this->xc->getXcXoopsFormGroupPerm('permform', '$formTitle', '$moduleId', '$permName', '$permDesc', "'admin/permissions.php'");
        $ret      .= $this->xc->getXcEqualsOperator('$permFound', 'false');
        $foreach1 = $this->xc->getXcAddItem('permform', '$gPermId', '$gPermName', "\t\t");
        $if1      = $this->pc->getPhpCodeForeach('globalPerms', false, 'gPermId', 'gPermName', $foreach1, "\t");
        $if1      .= $this->xc->getXcXoopsTplAssign('form', '$permform->render()', true, "\t");
        $if1      .= $this->xc->getXcEqualsOperator('$permFound', 'true', null, "\t");
        $ret      .= $this->pc->getPhpCodeConditions("'global'", ' === ', '$op', $if1, false);

        foreach (\array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableId   = $tables[$t]->getVar('table_id');
                $tableMid  = $tables[$t]->getVar('table_mid');
                $tableName = $tables[$t]->getVar('table_name');
                $fields    = $this->getTableFields($tableMid, $tableId);
                $fieldId   = 'id';
                $fieldMain = 'title';
                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (0 == $f) {
                        $fieldId = $fieldName;
                    }
                    if (1 == $fields[$f]->getVar('field_main')) {
                        $fieldMain = $fieldName;
                    }
                }
                $if_count   = $this->xc->getXcHandlerAllObj($tableName, $fieldMain, 0, 0, "\t\t");
                $getVar1    = $this->xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldId, true);
                $getVar2    = $this->xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldMain, true);
                $fe_content = $this->xc->getXcAddItem('permform', $getVar1, $getVar2, "\t\t\t");
                $if_table   = $this->xc->getXcHandlerCountObj($tableName, "\t");
                $if_count   .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $fe_content, "\t\t");
                $if_count   .= $this->xc->getXcXoopsTplAssign('form', '$permform->render()', true, "\t\t");
                $if_table   .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $if_count, false, "\t");
                $if_table   .= $this->xc->getXcEqualsOperator('$permFound', 'true', null, "\t");
                $cond       = "'approve_{$tableName}' === \$op || 'submit_{$tableName}' === \$op || 'view_{$tableName}' === \$op";
                $ret        .= $this->pc->getPhpCodeConditions($cond, '', '', $if_table, false);
            }
        }

        $ret       .= $this->pc->getPhpCodeUnset('permform');
        $elseInter = $this->xc->getXcRedirectHeader("'permissions.php'", '', '3', "{$language}NO_PERMISSIONS_SET", false, "\t");
        $elseInter .= $this->getSimpleString("exit();", "\t");
        $ret       .= $this->pc->getPhpCodeConditions('$permFound', ' !== ', 'true', $elseInter, false);

        return $ret;
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
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getPermissionsHeader($module, $language);
        $content       .= $this->getPermissionsSwitch($module, $language);
        $content       .= $this->getPermissionsBody($module, $language);
        $content       .= $this->getRequire('footer');

        $this->create($moduleDirname, 'admin', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
