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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class AdminPages.
 */
class AdminPages extends Files\CreateFile
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
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->axc = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     *
     * @return AdminPages
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
     * @private function getAdminPagesHeader
     * @param $moduleDirname
     * @param $fieldId
     * @return string
     */
    private function getAdminPagesHeader($moduleDirname, $fieldId)
    {
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants'], '', '');
        $ret       .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Common']);
        $ret       .= $this->getInclude();
        $ret       .= $this->pc->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret       .= $this->xc->getXcXoopsRequest('op', 'op', 'list', 'Cmd');
        $ret       .= $this->pc->getPhpCodeCommentLine("Request {$fieldId}");
        $ret       .= $this->xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');

        return $ret;
    }

    /**
     * @private function getAdminPagesSwitch
     * @param $cases
     *
     * @return string
     */
    private function getAdminPagesSwitch($cases = [])
    {
        $contentSwitch = $this->pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $this->pc->getPhpCodeSwitch('op', $contentSwitch);
    }

    /**
     * @private  function getAdminPagesList
     * @param        $moduleDirname
     * @param        $table
     * @param        $language
     * @param        $fieldInForm
     * @param string $t
     * @return string
     */
    private function getAdminPagesList($moduleDirname, $table, $language, $fieldInForm, $t = '')
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $stuTableName     = mb_strtoupper($tableName);
        $stuTableSoleName = mb_strtoupper($tableSoleName);

        $ret        = $this->pc->getPhpCodeCommentLine('Define Stylesheet', '', $t);
        $ret        .= $this->xc->getXcXoThemeAddStylesheet('style', $t);
        $ret        .= $this->xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, $t);
        $adminpager = $this->xc->getXcGetConfig('adminpager');
        $ret        .= $this->xc->getXcXoopsRequest('limit', 'limit', $adminpager, 'Int', false, $t);
        $ret        .= $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $this->axc->getAdminDisplayNavigation($tableName);
        $ret        .= $this->xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $this->xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }

        $ret .= $this->xc->getXcHandlerCountObj($tableName, $t);
        $ret .= $this->xc->getXcHandlerAllObj($tableName, '', '$start', '$limit', $t);
        $ret .= $this->xc->getXcXoopsTplAssign("{$tableName}_count", "\${$tableName}Count", true, $t);
        $ret .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL", true, $t);
        $ret .= $this->xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL", true, $t);

        $ret            .= $this->pc->getPhpCodeCommentLine('Table view', $tableName, $t);
        $contentForeach = $this->xc->getXcGetValues($tableName, $tableSoleName, 'i', false, $t . "\t\t");
        $contentForeach .= $this->xc->getXcXoopsTplAppend("{$tableName}_list", "\${$tableSoleName}", $t . "\t\t");
        $contentForeach .= $this->pc->getPhpCodeUnset($tableSoleName, $t . "\t\t");
        $condIf         = $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, $t . "\t");
        $condIf         .= $this->xc->getXcPageNav($tableName, $t . "\t");
        $condElse       = $this->xc->getXcXoopsTplAssign('error', "{$language}THEREARENT_{$stuTableName}", true, $t . "\t");
        $ret            .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, $condElse, $t);

        return $ret;
    }

    /**
     * @private function getAdminPagesNew
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldInForm
     * @param        $language
     * @param string $t
     * @return string
     */
    private function getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, $t = '')
    {
        $stuTableName = mb_strtoupper($tableName);
        $ret          = $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation   = $this->axc->getAdminDisplayNavigation($tableName);
        $ret          .= $this->xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $this->xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $this->xc->getXcCommonPagesNew($tableName, $t);

        return $ret;
    }

    /**
     * @private function getPermissionsSave
     * @param $moduleDirname
     * @param $perm
     *
     * @return string
     */
    private function getPermissionsSave($moduleDirname, $perm = 'view')
    {
        $ret     = $this->pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret     .= $this->xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', '$permId', false, "\t\t\t");
        $content = $this->xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', '$mid', false, "\t\t\t\t\t");
        $foreach = $this->pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $this->pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @private function getAdminPagesSave
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $language
     * @param        $fields
     * @param        $fieldId
     * @param        $fieldMain
     * @param $tablePerms
     * @param string $t
     * @return string
     */
    private function getAdminPagesSave($moduleDirname, $tableName, $tableSoleName, $language, $fields, $fieldId, $fieldMain, $tablePerms, $t = '')
    {
        $ccFieldId          = $this->getCamelCase($fieldId, false, true);
        $ret                = $this->pc->getPhpCodeCommentLine('Security Check','',  $t);
        $xoopsSecurityCheck = $this->xc->getXcXoopsSecurityCheck('!');
        $securityError      = $this->xc->getXcXoopsSecurityErrors();
        $implode            = $this->pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $contentIf   = $this->xc->getXcHandlerGetObj($tableName, $ccFieldId,  $t . "\t");
        $contentElse = $this->xc->getXcHandlerCreateObj($tableName, $t . "\t");
        $ret         .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $contentIf, $contentElse, $t);
        $ret         .= $this->pc->getPhpCodeCommentLine('Set Vars', null, $t);
        $countUploader = 0;
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldType    = $fields[$f]->getVar('field_type');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->xc->getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName, $t);
                        break;
                    case 10:
                        $ret .= $this->axc->getAxcSetVarImageList($tableName, $fieldName, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 11:
                        $ret .= $this->axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 12:
                        $ret .= $this->axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, true, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 13:
                        $ret .= $this->axc->getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 14:
                        $ret .= $this->axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 15:
                        $ret .= $this->xc->getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    case 17:
                        $ret .= $this->axc->getAxcSetVarPassword($tableName, $fieldName, $t);
                        break;
                    case 21:
                        $ret .= $this->xc->getXcSetVarDateTime($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    default:
                        $ret .= $this->axc->getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, $t);
                        break;
                }
            }
        }
        $ret           .= $this->pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert        = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $contentInsert = '';
        if (1 == $tablePerms) {
            $ucfTableName  = ucfirst($tableName);
            $ucfFieldId    = $this->getCamelCase($fieldId, true);
            $contentInsert = $this->xc->getXcEqualsOperator("\$new{$ucfFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", null, $t . "\t");
            $contentInsert .= $this->pc->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t . "\t");
            $contentInsert .= $this->xc->getXcXoopsHandler('groupperm', $t . "\t");
            $contentInsert .= $this->xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'approve_' . $tableName);
        }
        if ($countUploader > 0) {
            $errIf         = $this->xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \${$ccFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse       = $this->xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $contentInsert .= $this->pc->getPhpCodeConditions('$uploaderErrors', ' !== ',"''" , $errIf, $errElse, $t . "\t");
        } else {
            $contentInsert .= $this->xc->getXcRedirectHeader($tableName . '', '?op=list', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $ret .= $this->pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);
        $ret .= $this->pc->getPhpCodeCommentLine('Get Form', null, $t);
        $ret .= $this->xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $this->xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

        return $ret;
    }

    /**
     * @private  function getAdminPagesEdit
     * @param        $moduleDirname
     * @param        $table
     * @param        $language
     * @param        $fieldId
     * @param        $fieldInForm
     * @param string $t
     * @return string
     */
    private function getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, $t = '')
    {
        $tableName         = $table->getVar('table_name');
        $tableSoleName     = $table->getVar('table_solename');
        $stuTableName      = mb_strtoupper($tableName);
        $stuTableSoleName  = mb_strtoupper($tableSoleName);
        $ccFieldId         = $this->getCamelCase($fieldId, false, true);

        $ret        = $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $this->axc->getAdminDisplayNavigation($tableName);
        $ret        .= $this->xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $this->axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $this->xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $this->xc->getXcCommonPagesEdit($tableName, $ccFieldId, $t);

        return $ret;
    }

    /**
     * @private function getAdminPagesDelete
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param $tableNotifications
     * @param string $t
     * @return string
     */
    private function getAdminPagesDelete($moduleDirname, $tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications, $t = '')
    {
        $ret        = $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $this->axc->getAdminDisplayNavigation($tableName);
        $ret        .= $this->xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);
        $ret        .= $this->xc->getXcCommonPagesDelete($language, $tableName, $tableSoleName, $fieldId, $fieldMain, $tableNotifications, $t, true);
        return $ret;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $tf  = Modulebuilder\Files\CreateFile::getInstance();
        $new = $save = $edit = '';

        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $tablePerms         = $table->getVar('table_permissions');
        $tableNotifications = $table->getVar('table_notifications');
        $language           = $this->getLanguage($moduleDirname, 'AM');
        $fields             = $tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm        = null;
        $fieldId            = null;
        $fieldMain          = null;
        foreach (array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
            $fieldInForm[] = $fields[$f]->getVar('field_inform');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $content = $this->getHeaderFilesComments($module);
        $content .= $this->getAdminPagesHeader($moduleDirname, $fieldId);
        $list    = $this->getAdminPagesList($moduleDirname, $table, $language, $fieldInForm, "\t\t");
        if (in_array(1, $fieldInForm)) {
            $new  = $this->getAdminPagesNew($moduleDirname, $tableName, $fieldInForm, $language, "\t\t");
            $save = $this->getAdminPagesSave($moduleDirname, $tableName, $tableSoleName, $language, $fields, $fieldId, $fieldMain, $tablePerms, "\t\t");
            $edit = $this->getAdminPagesEdit($moduleDirname, $table, $language, $fieldId, $fieldInForm, "\t\t");
        }
        $delete = $this->getAdminPagesDelete($moduleDirname, $tableName, $tableSoleName, $language, $fieldId, $fieldMain, $tableNotifications, "\t\t");

        $cases   = [
            'list'   => [$list],
            'new'    => [$new],
            'save'   => [$save],
            'edit'   => [$edit],
            'delete' => [$delete],
        ];
        $content .= $this->getAdminPagesSwitch($cases);
        $content .= $this->getInclude('footer');

        $tf->create($moduleDirname, 'admin', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $tf->renderFile();
    }
}
