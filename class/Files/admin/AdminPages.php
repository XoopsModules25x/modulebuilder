<?php

namespace XoopsModules\Tdmcreate\Files\Admin;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
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
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $pc->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret       .= $xc->getXcXoopsRequest('op', 'op', 'list');
        $ret       .= $pc->getPhpCodeCommentLine("Request {$fieldId}");
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');

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
        $pc            = Tdmcreate\Files\CreatePhpCode::getInstance();
        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $pc->getPhpCodeSwitch('op', $contentSwitch);
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
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $axc = Tdmcreate\Files\Admin\AdminXoopsCode::getInstance();

        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $stuTableName     = mb_strtoupper($tableName);
        $stuTableSoleName = mb_strtoupper($tableSoleName);

        $ret        = $pc->getPhpCodeCommentLine('Define Stylesheet', '', $t);
        $ret        .= $xc->getXcXoThemeAddStylesheet('style', $t);
        $ret        .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, $t);
        $adminpager = $xc->getXcGetConfig('adminpager');
        $ret        .= $xc->getXcXoopsRequest('limit', 'limit', $adminpager, 'Int', false, $t);
        $ret        .= $axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $axc->getAdminDisplayNavigation($tableName);
        $ret        .= $xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }

        $ret .= $xc->getXcHandlerCountObj($tableName, $t);
        $ret .= $xc->getXcHandlerAllObj($tableName, '', '$start', '$limit', $t);
        $ret .= $xc->getXcXoopsTplAssign("{$tableName}_count", "\${$tableName}Count", true, $t);
        $ret .= $xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL", true, $t);
        $ret .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL", true, $t);

        $ret            .= $pc->getPhpCodeCommentLine('Table view', $tableName, $t);
        $contentForeach = $xc->getXcGetValues($tableName, $tableSoleName, 'i', false, $t . "\t\t");
        $contentForeach .= $xc->getXcXoopsTplAppend("{$tableName}_list", "\${$tableSoleName}", $t . "\t\t");
        $contentForeach .= $pc->getPhpCodeUnset($tableSoleName, $t . "\t\t");
        $condIf         = $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contentForeach, $t . "\t");
        $condIf         .= $xc->getXcPageNav($tableName, $t . "\t");
        $condElse       = $xc->getXcXoopsTplAssign('error', "{$language}THEREARENT_{$stuTableName}", true, $t . "\t");
        $ret            .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf, $condElse, $t);

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
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $axc = Tdmcreate\Files\Admin\AdminXoopsCode::getInstance();

        $stuTableName = mb_strtoupper($tableName);
        $ret          = $axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation   = $axc->getAdminDisplayNavigation($tableName);
        $ret          .= $xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $xc->getXcCommonPagesNew($tableName, $t);

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
        $pc = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc = Tdmcreate\Files\CreateXoopsCode::getInstance();

        $ret     = $pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret     .= $xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', '$permId', false, "\t\t\t");
        $content = $xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', '$mid', false, "\t\t\t\t\t");
        $foreach = $pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

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
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $axc = Tdmcreate\Files\Admin\AdminXoopsCode::getInstance();

        $ccFieldId          = $this->getCamelCase($fieldId, false, true);
        $ret                = $pc->getPhpCodeCommentLine('Security Check','',  $t);
        $xoopsSecurityCheck = $xc->getXcXoopsSecurityCheck('!');
        $securityError      = $xc->getXcXoopsSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $isset       = $pc->getPhpCodeIsset($ccFieldId);
        $contentIf   = $xc->getXcHandlerGet($tableName, $ccFieldId, 'Obj', $tableName . 'Handler', false, $t . "\t");
        $contentElse = $xc->getXcHandlerCreateObj($tableName, "\t\t\t");
        $ret         .= $pc->getPhpCodeConditions($isset, '', '', $contentIf, $contentElse, $t);
        $ret         .= $pc->getPhpCodeCommentLine('Set Vars', null, "\t\t");
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
                        $ret .= $xc->getXcSetVarCheckBoxOrRadioYN($tableName, $fieldName, $t);
                        break;
                    case 10:
                        $ret .= $axc->getAxcSetVarImageList($tableName, $fieldName, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 11:
                        $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 12:
                        $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, true, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 13:
                        $ret .= $axc->getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, $t, $countUploader);
                        $countUploader++;
                        break;
                    case 14:
                        $ret .= $axc->getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, false, $t, $countUploader, $fieldMain);
                        $countUploader++;
                        break;
                    case 15:
                        $ret .= $xc->getXcSetVarTextDateSelect($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    case 17:
                        $ret .= $axc->getAxcSetVarPassword($tableName, $fieldName, $t);
                        break;
                    case 21:
                        $ret .= $xc->getXcSetVarDateTime($tableName, $tableSoleName, $fieldName, $t);
                        break;
                    default:
                        $ret .= $axc->getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, $t);
                        break;
                }
            }
        }
        $ret           .= $pc->getPhpCodeCommentLine('Insert Data', null, "\t\t");
        $insert        = $xc->getXcHandlerInsert($tableName, $tableName, 'Obj');
        $contentInsert = '';
        //if (1 == $tableCategory) {
        if (1 == $tablePerms) {
            $ucfTableName  = ucfirst($tableName);
            $ucfFieldId    = $this->getCamelCase($fieldId, true);
            $contentInsert = $xc->getXcEqualsOperator("\$new{$ucfFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", null, $t . "\t");
            $contentInsert .= $pc->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t . "\t");
            $contentInsert .= $xc->getXcXoopsHandler('groupperm', $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'approve_' . $tableName);
        }
        if ($countUploader > 0) {
            $errIf         = $xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \${$ccFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse       = $xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $contentInsert .= $pc->getPhpCodeConditions("''", ' !== ', '$uploaderErrors', $errIf, $errElse, $t . "\t");
        } else {
            $contentInsert .= $xc->getXcRedirectHeader($tableName . '', '?op=list', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $ret .= $pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);
        $ret .= $pc->getPhpCodeCommentLine('Get Form', null, "\t\t");
        $ret .= $xc->getXcXoopsTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcXoopsTplAssign('form', '$form->render()', true, $t);

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
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $axc = Tdmcreate\Files\Admin\AdminXoopsCode::getInstance();

        $tableName         = $table->getVar('table_name');
        $tableSoleName     = $table->getVar('table_solename');
        $stuTableName      = mb_strtoupper($tableName);
        $stuTableSoleName  = mb_strtoupper($tableSoleName);
        $ccFieldId         = $this->getCamelCase($fieldId, false, true);

        $ret        = $axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $axc->getAdminDisplayNavigation($tableName);
        $ret        .= $xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        if (in_array(1, $fieldInForm)) {
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableSoleName, '?op=new', 'add', $t);
            $ret .= $axc->getAdminItemButton($language, $tableName, $stuTableName, '', 'list', $t);
            $ret .= $xc->getXcXoopsTplAssign('buttons', '$adminObject->displayButton(\'left\')', true, $t);
        }
        $ret .= $xc->getXcCommonPagesEdit($tableName, $ccFieldId, $t);

        return $ret;
    }

    /**
     * @private function getAdminPagesDelete
     * @param        $tableName
     * @param        $language
     * @param        $fieldId
     * @param        $fieldMain
     * @param string $t
     * @return string
     */
    private function getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, $t = '')
    {
        $xc = Tdmcreate\Files\CreateXoopsCode::getInstance();

        return $xc->getXcCommonPagesDelete($language, $tableName, $fieldId, $fieldMain, $t);
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $tf  = Tdmcreate\Files\CreateFile::getInstance();
        $new = $save = $edit = '';

        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tablePerms    = $table->getVar('table_permissions');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $fields        = $tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldInForm   = null;
        $fieldId       = null;
        $fieldMain     = null;
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
        $delete = $this->getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain, "\t\t");

        $cases   = [
            'list'   => [$list],
            'new'    => [$new],
            'save'   => [$save],
            'edit'   => [$edit],
            'delete' => [$delete],
        ];
        $content .= $this->getAdminPagesSwitch($cases);
        $content .= $this->getInclude('footer');

        $tf->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $tf->renderFile();
    }
}
