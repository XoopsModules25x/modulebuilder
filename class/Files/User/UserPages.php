<?php

namespace XoopsModules\Modulebuilder\Files\User;

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
 * Class UserPages.
 */
class UserPages extends Files\CreateFile
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
     * @return UserPages
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
     * @private function getUserPagesHeader
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $uxc->getUserTplMain($moduleDirname, $tableName);
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsRequest('op   ', 'op', 'list', 'Cmd');
        $ret       .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $xc->getXcGetConfig('userpager');
        $ret       .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '0', 'Int');
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $xc->getXcXoThemeAddStylesheet();
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret       .= $xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $pc->getPhpCodeArray('keywords', null, false, '');
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcEqualsOperator('$permEdit', '$permissionsHandler->getPermGlobalSubmit()', );
        $ret       .= $xc->getXcXoopsTplAssign("permEdit", '$permEdit');
        $ret       .= $xc->getXcXoopsTplAssign("showItem", "\${$ccFieldId} > 0");
        $ret       .= $pc->getPhpCodeBlankLine();

        return $ret;
    }

    /**
     * @private function getUserPagesList
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     * @param string $t
     * @return string
     */
    private function getUserPagesList($tableName, $fieldId, $fieldMain, $t = '')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ucfTableName     = ucfirst($tableName);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);

        $critName  = 'cr' . $ucfTableName;
        $ret       = $xc->getXcCriteriaCompo($critName, $t);
        $crit      = $xc->getXcCriteria('', "'{$fieldId}'", "\${$ccFieldId}",'',true);
        $contIf    = $xc->getXcCriteriaAdd($critName, $crit, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName, $t);
        $ret       .= $xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count", '', $t);
        $ret       .= $xc->getXcCriteriaSetStart($critName, '$start', $t);
        $ret       .= $xc->getXcCriteriaSetLimit($critName, '$limit', $t);
        $ret       .= $xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$' . $critName, $t);
        $condIf    = $pc->getPhpCodeArray($tableName, null, false, $t . "\t");
        $condIf    .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, $t . "\t");
        $foreach   = $xc->getXcGetValues($tableName, $tableName . '[]', 'i', false, $t . "\t\t");
        $foreach   .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, $t . "\t\t");
        $condIf    .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t . "\t");
        $condIf    .= $xc->getXcXoopsTplAssign($tableName, "\${$tableName}", true, $t . "\t");
        $condIf    .= $pc->getPhpCodeUnset($tableName, $t . "\t");
        $condIf    .= $xc->getXcPageNav($tableName, $t . "\t");
        $tableType = $xc->getXcGetConfig('table_type');
        $condIf    .= $xc->getXcXoopsTplAssign('type', $tableType, true, $t . "\t");
        $divideby  = $xc->getXcGetConfig('divideby');
        $condIf    .= $xc->getXcXoopsTplAssign('divideby', $divideby, true, $t . "\t");
        $numbCol   = $xc->getXcGetConfig('numb_col');
        $condIf    .= $xc->getXcXoopsTplAssign('numb_col', $numbCol, true, $t . "\t");

        $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, false, $t);

        return $ret;
    }

    /**
     * @public function getUserSubmitSave
     * @param string $moduleDirname
     * @param        $fields
     * @param string $tableName
     * @param        $tableSoleName
     * @param $tablePermissions
     * @param $tableNotifications
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesSave($moduleDirname, $fields, $tableName, $tableSoleName, $tablePermissions, $tableNotifications, $language, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ucfTableName  = ucfirst($tableName);
        $countUploader = 0;
        $fieldId       = '';
        $ccFieldId     = '';
        $fieldMain     = '';
        $fieldStatus   = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId   = $fieldName;
                $ccFieldId = $this->getCamelCase($fieldId, false, true);
                $ucfFieldId = ucfirst($ccFieldId);
            }
            if ($fields[$f]->getVar('field_type') >= 10 && $fields[$f]->getVar('field_type') <= 14) {
                $countUploader++;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain    = $fieldName; // fieldMain = fields parameters main field
                $stuFieldMain = mb_strtoupper($fieldMain);
                $ccFieldMain  = $this->getCamelCase($fieldMain, false, true);
            }
            if ($fields[$f]->getVar('field_element') == 16) {
                $fieldStatus   = $fieldName;
                $ccFieldStatus = $this->getCamelCase($fieldStatus, false, true);

            }
        }

        $ret                = $pc->getPhpCodeCommentLine('Security Check', '', $t);
        $xoopsSecurityCheck = $xc->getXcXoopsSecurityCheck();
        $securityError      = $xc->getXcXoopsSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret                .= $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf             = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $getObj             = $xc->getXcHandlerGetObj($tableName, $ccFieldId,  $t . "\t");
        $createObj          = $xc->getXcHandlerCreateObj($tableName, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $getObj, $createObj, $t);
        $ret                .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t);
        $ret                .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert             = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj', 'Handler');

        $contentInsert = '';
        $contentInsert .= $pc->getPhpCodeTernaryOperator("new{$ucfFieldId}", "\${$ccFieldId} > 0", "\${$ccFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", $t . "\t");

        if (1 == $tablePermissions) {
            $contentInsert .= $xc->getXcXoopsHandler('groupperm', $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId,'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId, 'approve_' . $tableName);
        }

        if (1 == $tableNotifications) {
            $contentInsert .= $pc->getPhpCodeCommentLine('Handle notification', null, $t . "\t");
            $contentInsert .= $xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t. "\t");
            if ('' !== $fieldStatus) {
                $contentInsert .= $xc->getXcGetVar($ccFieldStatus, "{$tableName}Obj", $fieldStatus, false, $t . "\t");
            }
            $contentInsert .= $pc->getPhpCodeArray('tags', [], false, $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t . "\t");
            $url = "XOOPS_URL . '/modules/{$moduleDirname}/{$tableName}.php?op=show&{$fieldId}=' . \${$ccFieldId}";
            $contentInsert .= $xc->getXcEqualsOperator("\$tags['ITEM_URL'] ", $url, '', $t . "\t");
            $contentInsert .= $xc->getXcXoopsHandler('notification', $t . "\t");
            if ('' === $fieldStatus) {
                $not2If        = $pc->getPhpCodeCommentLine('Event modify notification', null, $t . "\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_modify', \$tags);", $t . "\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_modify', \$tags);", $t . "\t\t");
                $not2Else      = $pc->getPhpCodeCommentLine('Event new notification', null, $t . "\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_new', \$tags);", $t . "\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_new', \$tags);", $t . "\t\t");
                $not1Else      = $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $not2If, $not2Else, $t . "\t");
                $contentInsert .= $not1Else;
            } else {
                $not1If        = $pc->getPhpCodeCommentLine('Event approve notification', null, $t . "\t\t");
                $not1If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_approve', \$tags);", $t . "\t\t");
                $not1If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_approve', \$tags);", $t . "\t\t");
                $not2If        = $pc->getPhpCodeCommentLine('Event modify notification', null, $t . "\t\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_modify', \$tags);", $t . "\t\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_modify', \$tags);", $t . "\t\t\t");
                $not2Else      = $pc->getPhpCodeCommentLine('Event new notification', null, $t . "\t\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_new', \$tags);", $t . "\t\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_new', \$tags);", $t . "\t\t\t");
                $not1Else      = $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $not2If, $not2Else, $t . "\t\t");
                $contentInsert .= $pc->getPhpCodeConditions("\${$ccFieldStatus}", ' == ', $xc->getXcGetConstants('STATUS_APPROVED'), $not1If, $not1Else, $t . "\t");
            }
        }

        $contentInsert .= $pc->getPhpCodeCommentLine('redirect after insert', null, $t . "\t");
        if ($countUploader > 0) {
            $errIf     = $xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \$new{$ucfFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse   = $xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $confirmOk = $pc->getPhpCodeConditions('$uploaderErrors', ' !== ', "''", $errIf, $errElse, $t . "\t");
        } else {
            $confirmOk = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $contentInsert .= $confirmOk;
        $ret           .= $pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @private function getPermissionsSave
     * @param $moduleDirname
     * @param $ucfFieldId
     * @param string $perm
     *
     * @return string
     */
    private function getPermissionsSave($moduleDirname, $ucfFieldId, $perm = 'view')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ret     = $pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret     .= $xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', "\$new{$ucfFieldId}", false, "\t\t\t");
        $content = $xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", "\$new{$ucfFieldId}", '$onegroupId', '$mid', false, "\t\t\t\t\t");
        $foreach = $pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @public function getUserPagesNew
     * @param        $tableName
     * @param string $t
     * @return string
     */
    public function getUserPagesNew($tableName, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ret    = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret    .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret    .= $xc->getXcCommonPagesNew($tableName, $t);

        return $ret;
    }

    /**
     * @public function getUserPagesEdit
     * @param        $tableName
     * @param $fieldId
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesEdit($tableName, $fieldId, $language, $t = '')
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();

        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcCommonPagesEdit($tableName, $ccFieldId, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesDelete
     * @param        $tableName
     * @param $tableSoleName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param $tableNotifications
     * @param string $t
     * @return string
     */
    private function getUserPagesDelete($tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications, $t = '')
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $xc->getXcCommonPagesDelete($language, $tableName, $tableSoleName, $fieldId, $fieldMain, $tableNotifications, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesBroken
     * @param        $language
     * @param $moduleDirname
     * @param        $tableName
     * @param $tableSoleName
     * @param        $fieldId
     * @param $fieldSatus
     * @param        $fieldMain
     * @param $tableNotifications
     * @param string $t
     * @return string
     */
    private function getUserPagesBroken($language, $moduleDirname, $tableName, $tableSoleName, $fieldId, $fieldSatus, $fieldMain, $tableNotifications, $t = '')
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();

        //$stuFieldMain     = mb_strtoupper($fieldMain);
        //$stuTableSoleName = mb_strtoupper($tableSoleName);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);
        $ccFieldMain      = $this->getCamelCase($fieldMain, false, true);

        $ret        = $pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf     = $xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret        .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret        .= $xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
		$constant   = $xc->getXcGetConstants('STATUS_BROKEN');
        $ret        .= $xc->getXcSetVarObj($tableName, $fieldSatus, $constant, $t);
        $ret        .= $xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t);
        $ret        .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert     = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $contInsert = '';
        if (1 == $tableNotifications) {
            $contInsert .= $pc->getPhpCodeCommentLine('Event broken notification', null, $t . "\t");
            $contInsert .= $pc->getPhpCodeArray('tags', [], false, $t . "\t");
            $contInsert .= $xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t . "\t");
            $url = "XOOPS_URL . '/modules/{$moduleDirname}/{$tableName}.php?op=show&{$fieldId}=' . \${$ccFieldId}";
            $contInsert .= $xc->getXcEqualsOperator("\$tags['ITEM_URL'] ", $url, '', $t . "\t");
            $contInsert .= $xc->getXcXoopsHandler('notification', $t . "\t");
            $contInsert .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_broken', \$tags);", $t . "\t");
            $contInsert .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \${$ccFieldId}, '{$tableSoleName}_broken', \$tags);", $t . "\t");
        }
        $contInsert .= $pc->getPhpCodeCommentLine('redirect after success', null, $t . "\t");
        $contInsert .= $xc->getXcRedirectHeader($tableName, '', '2', "{$language}FORM_OK", true, $t . "\t");
        $ret        .= $pc->getPhpCodeConditions($insert, '', '', $contInsert, false, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $language)
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ret              = $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uxc->getUserBreadcrumbs($language, $stuTableName);
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uxc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uxc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret              .= $xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret              .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret              .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @private function getUserPagesSwitch
     * @param $moduleDirname
     * @param $tableId
     * @param $tableMid
     * @param $tableName
     * @param $tableSoleName
     * @param $tableSubmit
     * @param $tablePermissions
     * @param $tableBroken
     * @param $fieldId
     * @param $fieldMain
     * @param $fieldSatus
     * @param $tableNotifications
     * @param $language
     * @param $t
     * @return string
     */
    private function getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldSatus, $tableNotifications, $language, $t)
    {
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();

        $fields = $this->getTableFields($tableMid, $tableId);
        $cases['show'] = [];
        $cases['list'] = [$this->getUserPagesList($tableName, $fieldId, $fieldMain, $t . "\t")];
        if (1 == $tableSubmit) {
            $cases['save']   = [$this->getUserPagesSave($moduleDirname, $fields, $tableName, $tableSoleName, $tablePermissions, $tableNotifications, $language, $t . "\t")];
            $cases['new']    = [$this->getUserPagesNew($tableName, $t . "\t")];
            $cases['edit']   = [$this->getUserPagesEdit($tableName, $fieldId, $language, $t . "\t")];
            $cases['delete'] = [$this->getUserPagesDelete($tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications,$t . "\t")];
        }
        if (1 == $tableBroken) {
            $cases['broken']  = [$this->getUserPagesBroken($language, $moduleDirname, $tableName, $tableSoleName, $fieldId, $fieldSatus, $fieldMain, $tableNotifications, $t . "\t")];
        }

        return $xc->getXcSwitch('op', $cases, true, false);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $table              = $this->getTable();
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSubmit        = $table->getVar('table_submit');
        $tablePermissions   = $table->getVar('table_permissions');
        $tableSoleName      = $table->getVar('table_solename');
        $tableBroken        = $table->getVar('table_broken');
        $tableNotifications = $table->getVar('table_notifications');
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $language           = $this->getLanguage($moduleDirname, 'MA');
        // Fields
        $fieldId    = '';
        $fieldMain  = '';
        $fieldSatus = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
            if (16 == $fields[$f]->getVar('field_element')) {
                $fieldSatus = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserPagesHeader($moduleDirname, $tableName, $fieldId);
        $content       .= $this->getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldSatus, $tableNotifications, $language, "\t");
        $content       .= $this->getUserPagesFooter($moduleDirname, $tableName, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
