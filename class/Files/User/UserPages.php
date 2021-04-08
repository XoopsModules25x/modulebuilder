<?php

namespace XoopsModules\Modulebuilder\Files\User;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\{
    Files,
    Constants
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
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class UserPages.
 */
class UserPages extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $uxc = null;

    /**
     * @var mixed
     */
    private $xc = null;

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
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = Modulebuilder\Files\User\UserXoopsCode::getInstance();
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
     * @param $tablePermissions
     * @param $language
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName, $fieldId, $tablePermissions, $language)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);

        $ret       = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants'], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Common']);
        $ret       .= $this->getInclude();
        $ret       .= $this->uxc->getUserTplMain($moduleDirname, $tableName);
        $ret       .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $this->pc->getPhpCodeBlankLine();
        $ret       .= $this->xc->getXcXoopsRequest('op   ', 'op', 'list', 'Cmd');
        $ret       .= $this->xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $this->xc->getXcGetConfig('userpager');
        $ret       .= $this->xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret       .= $this->xc->getXcXoopsRequest($ccFieldId, $fieldId, '0', 'Int');
        $ret       .= $this->pc->getPhpCodeBlankLine();
        $ret       .= $this->pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $this->xc->getXcXoThemeAddStylesheet();
        $ret       .= $this->pc->getPhpCodeCommentLine('Paths');
        $ret       .= $this->xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret       .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret       .= $this->pc->getPhpCodeCommentLine('Keywords');
        $ret       .= $this->pc->getPhpCodeArray('keywords', null, false, '');
        $ret       .= $this->uxc->getUserBreadcrumbs($language, 'index', '', 'index.php');
        $ret       .= $this->pc->getPhpCodeCommentLine('Permissions');
        if (1 == $tablePermissions) {
            $ret .= $this->xc->getXcEqualsOperator('$permEdit', '$permissionsHandler->getPermGlobalSubmit()');
            $ret .= $this->xc->getXcXoopsTplAssign("permEdit", '$permEdit');
        }
        $ret       .= $this->xc->getXcXoopsTplAssign("showItem", "\${$ccFieldId} > 0");
        $ret       .= $this->pc->getPhpCodeBlankLine();

        return $ret;
    }

    /**
     * @private function getUserPagesList
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     * @param $tableRate
     * @param $fieldReads
     * @param $language
     * @param string $t
     * @return string
     */
    private function getUserPagesList($moduleDirname, $tableName, $fieldId, $fieldMain, $tableRate, $fieldReads, $language, $t = '')
    {
        $ucfTableName     = \ucfirst($tableName);
        $stuTableName     = \mb_strtoupper($tableName);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);
        $ccFieldMain      = $this->getCamelCase($fieldMain, false, true);
        $ccFieldReads     = $this->getCamelCase($fieldReads, false, true);
        $stuModuleDirname = \mb_strtoupper($moduleDirname);

        $ret = '';
        $ret .= $this->uxc->getUserBreadcrumbs($language, $tableName, 'list', '', "\t\t");
        if ($tableRate) {
            $varRate = '$ratingbars';
            $ret .= $this->xc->getXcEqualsOperator($varRate, '(int)' . $this->xc->getXcGetConfig('ratingbars'),'', $t);
            $contIf = $this->xc->getXcXoThemeAddStylesheet("{$stuModuleDirname}_URL . '/assets/css/rating.css'", $t . "\t", false);
            $contIf .= $this->xc->getXcXoopsTplAssign('rating', $varRate, true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign('rating_5stars', "(Constants::RATING_5STARS === {$varRate})", true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign('rating_10stars', "(Constants::RATING_10STARS === {$varRate})", true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign('rating_10num', "(Constants::RATING_10NUM === {$varRate})", true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign('rating_likes', "(Constants::RATING_LIKES === {$varRate})", true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign('itemid', "'{$fieldId}'", true, $t . "\t");
            $contIf .= $this->xc->getXcXoopsTplAssign($moduleDirname . '_icon_url_16', "{$stuModuleDirname}_URL . '/' . \$modPathIcon16", true, $t . "\t");
            $ret .= $this->pc->getPhpCodeConditions($varRate, ' > ', '0', $contIf, false, $t);
        }
        $critName  = 'cr' . $ucfTableName;
        $ret       .= $this->xc->getXcCriteriaCompo($critName, $t);
        $crit      = $this->xc->getXcCriteria('', "'{$fieldId}'", "\${$ccFieldId}",'',true);
        $contIf    = $this->xc->getXcCriteriaAdd($critName, $crit, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $contIf, false, $t);
        $ret       .= $this->xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName, $t);
        $ret       .= $this->xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count", '', $t);
        $ret       .= $this->xc->getXcCriteriaSetStart($critName, '$start', $t);
        $ret       .= $this->xc->getXcCriteriaSetLimit($critName, '$limit', $t);
        $ret       .= $this->xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$' . $critName, $t);
        $condIf    = $this->pc->getPhpCodeArray($tableName, null, false, $t . "\t");
        $condIf    .= $this->xc->getXcEqualsOperator("\${$ccFieldMain}", "''",'', $t . "\t");
        $condIf    .= $this->pc->getPhpCodeCommentLine('Get All', $ucfTableName, $t . "\t");
        $foreach   = $this->xc->getXcGetValues($tableName, $tableName . '[$i]', 'i', false, $t . "\t\t");
        $foreach   .= $this->xc->getXcGetVar($ccFieldMain, "{$tableName}All[\$i]", $fieldMain, false, $t . "\t\t");
        $foreach   .= $this->xc->getXcEqualsOperator('$keywords[$i]', "\${$ccFieldMain}",'', $t . "\t\t");
        if ($tableRate) {
            $itemId   = $this->xc->getXcGetVar($ccFieldId, "{$tableName}All[\$i]", $fieldId, true);
            $const  = $this->xc->getXcGetConstants('TABLE_' . $stuTableName);
            $foreach .= $this->xc->getXcEqualsOperator("\${$tableName}[\$i]['rating']", "\$ratingsHandler->getItemRating({$itemId}, {$const})",'', $t . "\t\t");
        }
        $condIf    .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t . "\t");
        $condIf    .= $this->xc->getXcXoopsTplAssign($tableName, "\${$tableName}", true, $t . "\t");
        $condIf    .= $this->pc->getPhpCodeUnset($tableName, $t . "\t");
        $condIf    .= $this->xc->getXcPageNav($tableName, $t . "\t");
        $config    = $this->xc->getXcGetConfig('table_type');
        $condIf    .= $this->xc->getXcXoopsTplAssign('table_type', $config, true, $t . "\t");
        $config    = $this->xc->getXcGetConfig('panel_type');
        $condIf    .= $this->xc->getXcXoopsTplAssign('panel_type', $config, true, $t . "\t");
        $divideby  = $this->xc->getXcGetConfig('divideby');
        $condIf    .= $this->xc->getXcXoopsTplAssign('divideby', $divideby, true, $t . "\t");
        $numbCol   = $this->xc->getXcGetConfig('numb_col');
        $condIf    .= $this->xc->getXcXoopsTplAssign('numb_col', $numbCol, true, $t . "\t");
        $stripTags      = $this->pc->getPhpCodeStripTags('', "\${$ccFieldMain} . ' - ' . " . "\$GLOBALS['xoopsModule']->getVar('name')", true);
        $condIf2         = $this->xc->getXcXoopsTplAssign('xoops_pagetitle', $stripTags, true, $t . "\t\t");
        $condIf       .= $this->pc->getPhpCodeConditions("'show' == \$op && '' != \${$ccFieldMain}", '', "", $condIf2, false, $t . "\t");

        if ('' !== $fieldReads) {
            $condIf3 = $this->xc->getXcHandlerGetObj($tableName, $ccFieldId, $t . "\t\t");


            $getVar = $this->xc->getXcGetVar('', "{$tableName}Obj", $fieldReads, true);
            $condIf3 .= $this->xc->getXcEqualsOperator("\${$ccFieldReads}", "(int)" . $getVar . ' + 1', false, $t . "\t\t");
            $condIf3 .= $this->xc->getXcSetVarObj($tableName, $fieldReads, "\${$ccFieldReads}", $t . "\t\t");
            $condIf3 .= $this->pc->getPhpCodeCommentLine('Insert Data', null, $t . "\t\t");
            $insert = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj', 'Handler');
            $condIf3 .= $this->getSimpleString($insert .';',$t . "\t\t");
            //$contentInsert = $this->xc->getXcRedirectHeader("'{$tableName}.php?op=list&{$fieldId}=' . \${$ccFieldId}", '', '5', "\${$tableName}Obj->getHtmlErrors()", false, $t . "\t\t\t");
            //$condIf3 .= $this->pc->getPhpCodeConditions('!' . $insert, '', '', $contentInsert, false, $t . "\t\t");
            $condIf .= $this->pc->getPhpCodeConditions("'show' == \$op", '', "", $condIf3, false, $t . "\t");

        }


        $ret       .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, false, $t);

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
        $ucfTableName  = \ucfirst($tableName);
        $countUploader = 0;
        $fieldId       = '';
        $ccFieldId     = '';
        $fieldMain     = '';
        $fieldStatus   = '';
        $ucfFieldId    = '';
        $ccFieldMain   = '';
        $ccFieldStatus = '';
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId   = $fieldName;
                $ccFieldId = $this->getCamelCase($fieldId, false, true);
                $ucfFieldId = \ucfirst($ccFieldId);
            }
            if ($fields[$f]->getVar('field_element') >= Constants::FIELD_ELE_IMAGELIST && $fields[$f]->getVar('field_element') <= Constants::FIELD_ELE_UPLOADFILE) {
                $countUploader++;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain    = $fieldName; // fieldMain = fields parameters main field
                $ccFieldMain  = $this->getCamelCase($fieldMain, false, true);
            }
            if ($fields[$f]->getVar('field_element') == Constants::FIELD_ELE_SELECTSTATUS) {
                $fieldStatus   = $fieldName;
                $ccFieldStatus = $this->getCamelCase($fieldStatus, false, true);
            }
        }

        $ret                = $this->pc->getPhpCodeCommentLine('Security Check', '', $t);
        $xoopsSecurityCheck = $this->xc->getXcXoopsSecurityCheck();
        $securityError      = $this->xc->getXcXoopsSecurityErrors();
        $implode            = $this->pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret                .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf             = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $getObj             = $this->xc->getXcHandlerGetObj($tableName, $ccFieldId,  $t . "\t");
        $createObj          = $this->xc->getXcHandlerCreateObj($tableName, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $getObj, $createObj, $t);
        $ret                .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t);
        $ret                .= $this->pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert             = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj', 'Handler');

        $contentInsert = '';
        if (1 == $tableNotifications || $countUploader > 0) {
            $contentInsert .= $this->pc->getPhpCodeTernaryOperator("new{$ucfFieldId}", "\${$ccFieldId} > 0", "\${$ccFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", $t . "\t");
        }

        if (1 == $tablePermissions) {
            $contentInsert .= $this->xc->getXcXoopsHandler('groupperm', $t . "\t");
            $contentInsert .= $this->xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId,'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, $ucfFieldId, 'approve_' . $tableName);
        }

        if (1 == $tableNotifications) {
            $contentInsert .= $this->pc->getPhpCodeCommentLine('Handle notification', null, $t . "\t");
            $contentInsert .= $this->xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t. "\t");
            if ('' !== $fieldStatus) {
                $contentInsert .= $this->xc->getXcGetVar($ccFieldStatus, "{$tableName}Obj", $fieldStatus, false, $t . "\t");
            }
            $contentInsert .= $this->pc->getPhpCodeArray('tags', [], false, $t . "\t");
            $contentInsert .= $this->xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t . "\t");
            $url = "XOOPS_URL . '/modules/{$moduleDirname}/{$tableName}.php?op=show&{$fieldId}=' . \${$ccFieldId}";
            $contentInsert .= $this->xc->getXcEqualsOperator("\$tags['ITEM_URL'] ", $url, '', $t . "\t");
            $contentInsert .= $this->xc->getXcXoopsHandler('notification', $t . "\t");
            if ('' === $fieldStatus) {
                $not2If        = $this->pc->getPhpCodeCommentLine('Event modify notification', null, $t . "\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_modify', \$tags);", $t . "\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_modify', \$tags);", $t . "\t\t");
                $not2Else      = $this->pc->getPhpCodeCommentLine('Event new notification', null, $t . "\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_new', \$tags);", $t . "\t\t");
                //$not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_new', \$tags);", $t . "\t\t");
                $not1Else      = $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $not2If, $not2Else, $t . "\t");
                $contentInsert .= $not1Else;
            } else {
                $not1If        = $this->pc->getPhpCodeCommentLine('Event approve notification', null, $t . "\t\t");
                $not1If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_approve', \$tags);", $t . "\t\t");
                $not1If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_approve', \$tags);", $t . "\t\t");
                $not2If        = $this->pc->getPhpCodeCommentLine('Event modify notification', null, $t . "\t\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_modify', \$tags);", $t . "\t\t\t");
                $not2If        .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \$new{$ucfFieldId}, '{$tableSoleName}_modify', \$tags);", $t . "\t\t\t");
                $not2Else      = $this->pc->getPhpCodeCommentLine('Event new notification', null, $t . "\t\t\t");
                $not2Else      .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_new', \$tags);", $t . "\t\t\t");
                $not1Else      = $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $not2If, $not2Else, $t . "\t\t");
                $contentInsert .= $this->pc->getPhpCodeConditions("\${$ccFieldStatus}", ' == ', $this->xc->getXcGetConstants('STATUS_SUBMITTED'), $not1If, $not1Else, $t . "\t");
            }
        }

        $contentInsert .= $this->pc->getPhpCodeCommentLine('redirect after insert', null, $t . "\t");
        if ($countUploader > 0) {
            $errIf     = $this->xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \$new{$ucfFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse   = $this->xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $confirmOk = $this->pc->getPhpCodeConditions('$uploaderErrors', ' !== ', "''", $errIf, $errElse, $t . "\t");
        } else {
            $confirmOk = $this->xc->getXcRedirectHeader($tableName, '', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $contentInsert .= $confirmOk;
        $ret           .= $this->pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);

        $ret .= $this->pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $this->xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $this->xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

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
        $ret     = $this->pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret     .= $this->xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', "\$new{$ucfFieldId}", false, "\t\t\t");
        $content = $this->xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", "\$new{$ucfFieldId}", '$onegroupId', '$mid', false, "\t\t\t\t\t");
        $foreach = $this->pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $this->pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @public function getUserPagesNew
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesNew($tableName, $tableSoleName, $language, $t = '')
    {
        $ret    = $this->uxc->getUserBreadcrumbs($language, $tableSoleName, 'add', '', "\t\t");
        $ret    .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret    .= $this->pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret    .= $this->xc->getXcCommonPagesNew($tableName, $t);

        return $ret;
    }

    /**
     * @public function getUserPagesEdit
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldId
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserPagesEdit($tableName, $tableSoleName, $fieldId, $language, $t = '')
    {
        $ret       = $this->uxc->getUserBreadcrumbs($language, $tableSoleName, 'edit', '', "\t\t");
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $this->pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $this->xc->getXcCommonPagesEdit($tableName, $ccFieldId, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesDelete
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param        $tableNotifications
     * @param string $t
     * @return string
     */
    private function getUserPagesDelete($tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications, $t = '')
    {
        $ret       = $this->uxc->getUserBreadcrumbs($language, $tableSoleName, 'delete', '', "\t\t");
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $contIf    = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, '_NOPERM', true, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions('!$permissionsHandler->getPermGlobalSubmit()', '', '', $contIf, false, $t);
        $ret       .= $this->pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf    = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);
        $ret       .= $this->xc->getXcCommonPagesDelete($language, $tableName, $tableSoleName, $fieldId, $fieldMain, $tableNotifications, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesBroken
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $fieldId
     * @param $fieldStatus
     * @param        $fieldMain
     * @param        $tableNotifications
     * @param string $t
     * @return string
     */
    private function getUserPagesBroken($language, $moduleDirname, $tableName, $tableSoleName, $fieldId, $fieldStatus, $fieldMain, $tableNotifications, $t = '')
    {
        $ccFieldId   = $this->getCamelCase($fieldId, false, true);
        $ccFieldMain = $this->getCamelCase($fieldMain, false, true);
        $ret    = $this->uxc->getUserBreadcrumbs($language, '', 'broken', '', "\t\t");
        $ret    .= $this->pc->getPhpCodeCommentLine('Check params', '', $t);
        $contIf = $this->xc->getXcRedirectHeader($tableName, '?op=list', 3, "{$language}INVALID_PARAM", true, $t . "\t");
        $ret    .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' == ', '0', $contIf, false, $t);

        $ret                  .= $this->xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t);
        $ret                  .= $this->xc->getXcGetVar($ccFieldMain, "{$tableName}Obj", $fieldMain, false, $t);
        $reqOk                = "_REQUEST['ok']";
        $isset                = $this->pc->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck   = $this->xc->getXcXoopsSecurityCheck();
        $xoopsSecurityErrors  = $this->xc->getXcXoopsSecurityErrors();
        $implode              = $this->pc->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t\t");
        $insert               = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj', 'Handler');
        $condition            = $this->pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectHeaderErrors, false, $t . "\t");
        $constant             = $this->xc->getXcGetConstants('STATUS_BROKEN');
        $condition            .= $this->xc->getXcSetVarObj($tableName, $fieldStatus, $constant, $t . "\t");

        $contInsert = '';
        if (1 == $tableNotifications) {
            $contInsert .= $this->pc->getPhpCodeCommentLine('Event broken notification', null, $t . "\t\t");
            $contInsert .= $this->pc->getPhpCodeArray('tags', [], false, $t . "\t\t");
            $contInsert .= $this->xc->getXcEqualsOperator("\$tags['ITEM_NAME']", "\${$ccFieldMain}", '', $t . "\t\t");
            $url = "XOOPS_URL . '/modules/{$moduleDirname}/{$tableName}.php?op=show&{$fieldId}=' . \${$ccFieldId}";
            $contInsert .= $this->xc->getXcEqualsOperator("\$tags['ITEM_URL'] ", $url, '', $t . "\t\t");
            $contInsert .= $this->xc->getXcXoopsHandler('notification', $t . "\t\t");
            $contInsert .= $this->getSimpleString("\$notificationHandler->triggerEvent('global', 0, 'global_broken', \$tags);", $t . "\t\t");
            $contInsert .= $this->getSimpleString("\$notificationHandler->triggerEvent('{$tableName}', \${$ccFieldId}, '{$tableSoleName}_broken', \$tags);", $t . "\t\t");
        }
        $contInsert   .= $this->xc->getXcRedirectHeader($tableName, '', '3', "{$language}FORM_OK", true, $t . "\t\t");
        $htmlErrors   = $this->xc->getXcHtmlErrors($tableName, true);
        $internalElse = $this->xc->getXcXoopsTplAssign('error', $htmlErrors, true, $t . "\t\t");
        $condition    .= $this->pc->getPhpCodeConditions($insert, '', '', $contInsert, $internalElse, $t . "\t");
        $mainElse     = $this->xc->getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, 'broken', $t . "\t");
        $ret          .= $this->pc->getPhpCodeConditions($isset, ' && ', "1 == \${$reqOk}", $condition, $mainElse, $t);

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $tableComments
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $tableComments, $language)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $stuTableName     = \mb_strtoupper($tableName);
        $ret = $this->pc->getPhpCodeBlankLine();
        $ret .= $this->pc->getPhpCodeCommentLine('Keywords');
        $ret .= $this->uxc->getUserMetaKeywords($moduleDirname);
        $ret .= $this->pc->getPhpCodeUnset('keywords');
        $ret .= $this->pc->getPhpCodeBlankLine();
        $ret .= $this->pc->getPhpCodeCommentLine('Description');
        $ret .= $this->uxc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret .= $this->xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        if (1 == $tableComments) {
            $ret .= $this->pc->getPhpCodeBlankLine();
            $ret .= $this->pc->getPhpCodeCommentLine('View comments');
            $ret .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'include/comment_view', true, false, 'require');
        }
        $ret .= $this->pc->getPhpCodeBlankLine();
        $ret .= $this->getInclude('footer');

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
     * @param $fieldStatus
     * @param $tableNotifications
     * @param $tableRate
     * @param $fieldReads
     * @param $language
     * @param $t
     * @return string
     */
    private function getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldStatus, $tableNotifications, $tableRate, $fieldReads, $language, $t)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases['show'] = [];
        $cases['list'] = [$this->getUserPagesList($moduleDirname, $tableName, $fieldId, $fieldMain, $tableRate, $fieldReads, $language,$t . "\t")];
        if (1 == $tableSubmit) {
            $cases['save']   = [$this->getUserPagesSave($moduleDirname, $fields, $tableName, $tableSoleName, $tablePermissions, $tableNotifications, $language, $t . "\t")];
            $cases['new']    = [$this->getUserPagesNew($tableName, $tableSoleName, $language, $t . "\t")];
            $cases['edit']   = [$this->getUserPagesEdit($tableName, $tableSoleName, $fieldId, $language, $t . "\t")];
            $cases['delete'] = [$this->getUserPagesDelete($tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications,$t . "\t")];
        }
        if (1 == $tableBroken) {
            $cases['broken']  = [$this->getUserPagesBroken($language, $moduleDirname, $tableName, $tableSoleName, $fieldId, $fieldStatus, $fieldMain, $tableNotifications, $t . "\t")];
        }

        return $this->xc->getXcSwitch('op', $cases, true, false);
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
        $tableComments      = $table->getVar('table_comments');
        $tableRate          = $table->getVar('table_rate');
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $language           = $this->getLanguage($moduleDirname, 'MA');

        // Fields
        $fieldId    = '';
        $fieldMain  = '';
        $fieldStatus = '';
        $fieldReads = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fields parameters main field
            }
            if (Constants::FIELD_ELE_SELECTSTATUS == $fields[$f]->getVar('field_element')) {
                $fieldStatus = $fieldName; // fields for status
            }
            if (Constants::FIELD_ELE_TEXTREADS == $fields[$f]->getVar('field_element')) {
                $fieldReads = $fieldName; // fields for count reads
            }
        }
        $content = $this->getHeaderFilesComments($module);
        $content .= $this->getUserPagesHeader($moduleDirname, $tableName, $fieldId, $tablePermissions, $language);
        $content .= $this->getUserPagesSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $tableBroken, $fieldId, $fieldMain, $fieldStatus, $tableNotifications, $tableRate, $fieldReads, $language, "\t");
        $content .= $this->getUserPagesFooter($moduleDirname, $tableName, $tableComments, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
