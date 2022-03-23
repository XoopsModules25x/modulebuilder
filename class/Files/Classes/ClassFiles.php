<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\Classes;

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
 * tc module.
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
 * Class ClassFiles.
 */
class ClassFiles extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $cxc = null;
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
    private $helper = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc     = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->cxc    = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $this->helper = Modulebuilder\Helper::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return ClassFiles
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
     * @param string $table
     * @param mixed  $tables
     * @param        $filename
     */
    public function write($module, $table, $tables, $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getInitVar
     *
     * @param string $fieldName
     * @param string $type
     *
     * @return string
     */
    private function getInitVar($fieldName, $type = 'INT')
    {
        return $this->cxc->getClassInitVar($fieldName, $type);
    }

    /**
     * @private function getInitVars
     *
     * @param array $fields
     *
     * @return string
     */
    private function getInitVars($fields)
    {
        $ret = '';
        // Creation of the initVar functions list
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            switch ($fieldType) {
                case 2:
                case 3:
                case 4:
                case 5:
                    $ret .= $this->getInitVar($fieldName);
                    break;
                case 6:
                    $ret .= $this->getInitVar($fieldName, 'FLOAT');
                    break;
                case 7:
                case 8:
                    $ret .= $this->getInitVar($fieldName, 'DECIMAL');
                    break;
                case 10:
                    $ret .= $this->getInitVar($fieldName, 'ENUM');
                    break;
                case 11:
                    $ret .= $this->getInitVar($fieldName, 'EMAIL');
                    break;
                case 12:
                    $ret .= $this->getInitVar($fieldName, 'URL');
                    break;
                case 13:
                case 14:
                    $ret .= $this->getInitVar($fieldName, 'TXTBOX');
                    break;
                case 15:
                case 16:
                case 17:
                case 18:
                    if ((int)$fields[$f]->getVar('field_element') == 4) {
                        $ret .= $this->getInitVar($fieldName, 'OTHER');
                    } else {
                        $ret .= $this->getInitVar($fieldName, 'TXTAREA');
                    }
                    break;
                case 19:
                case 20:
                case 21:
                case 22:
                case 23:
                    $ret .= $this->getInitVar($fieldName, 'LTIME');
                    break;
            }
        }

        return $ret;
    }

    /**
     * @private  function getClassObject
     * @param $module
     * @param $table
     * @param $fields
     * @return string
     */
    private function getClassObject($module, $table, $fields)
    {
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $ucfTableName     = \ucfirst($tableName);
        $ret              = $this->pc->getPhpCodeDefined();
        $ret              .= $this->pc->getPhpCodeCommentMultiLine(['Class Object' => $ucfTableName]);
        $cCl              = '';

        $fieldInForm      = [];
        $fieldElementId   = [];
        $optionsFieldName = [];
        $fieldUpload      = false;
        $fieldId          = null;
        foreach (\array_keys($fields) as $f) {
            $fieldName        = $fields[$f]->getVar('field_name');
            $fieldElement     = $fields[$f]->getVar('field_element');
            $fieldInForm[]    = $fields[$f]->getVar('field_inform');
            $fieldElements    = $this->helper->getHandler('Fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');
            if (13 == $fieldElements->getVar('fieldelement_id') || 14 == $fieldElements->getVar('fieldelement_id')) {
                //13: UploadImage, 14: UploadFile
                $fieldUpload = true;
            }
            $rpFieldName      = $this->getRightString($fieldName);
            if (\in_array(5, $fieldElementId)) {
                //if (\count($rpFieldName) % 5) {
                    //$optionsFieldName[] = "'" . $rpFieldName . "'";
                //} else {
                    $optionsFieldName[] = "'" . $rpFieldName . "'\n";
                //}
            }
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
        }
        if (\in_array(5, $fieldElementId) > 1) {
            $cCl             .= $this->pc->getPhpCodeCommentMultiLine(['Options' => '']);
            $options         = $this->pc->getPhpCodeArray('', $optionsFieldName, true);
            $cCl             .= $this->pc->getPhpCodeVariableClass('private', 'options', $options);
        }
        unset($optionsFieldName);
        $cCl              .= $this->pc->getPhpCodeCommentMultiLine(['@var' => 'int'], "\t");
        $cCl              .= $this->pc->getPhpCodeVariableClass('public', 'start', '0', "\t");
        $cCl              .= $this->pc->getPhpCodeCommentMultiLine(['@var' => 'int'], "\t");
        $cCl              .= $this->pc->getPhpCodeVariableClass('public', 'limit', '0', "\t");
        $cCl              .= $this->pc->getPhpCodeCommentMultiLine(['Constructor' => '', '' => '', '@param' => 'null'], "\t");
        $constr           = $this->getInitVars($fields);
        $cCl              .= $this->pc->getPhpCodeFunction('__construct', '', $constr, 'public ', false, "\t");
        $arrayGetInstance = ['@static function' => '&getInstance', '' => '', '@param' => 'null'];
        $cCl              .= $this->pc->getPhpCodeCommentMultiLine($arrayGetInstance, "\t");
        $getInstance      = $this->pc->getPhpCodeVariableClass('static', 'instance', 'false', "\t\t");
        $instance         = $this->xc->getXcEqualsOperator('$instance', 'new self()', null, "\t\t\t");
        $getInstance      .= $this->pc->getPhpCodeConditions('!$instance', '', '', $instance, false, "\t\t");
        $cCl              .= $this->pc->getPhpCodeFunction('getInstance', '', $getInstance, 'public static ', false, "\t");

        $cCl .= $this->getNewInsertId($table);
        $cCl .= $this->getFunctionForm($module, $table, $fieldId, $fieldInForm, $fieldUpload);
        $cCl .= $this->getValuesInObject($moduleDirname, $table, $fields);
        $cCl .= $this->getToArrayInObject($table);

        if (\in_array(5, $fieldElementId) > 1) {
            $cCl .= $this->getOptionsCheck($table);
        }
        unset($fieldElementId);

        $ret .= $this->pc->getPhpCodeClass($ucfTableName, $cCl, '\XoopsObject');

        return $ret;
    }

    /**
     * @private function getNewInsertId
     *
     * @param $table
     *
     * @return string
     */
    private function getNewInsertId($table)
    {
        $tableName     = $table->getVar('table_name');
        $ucfTableName  = \ucfirst($tableName);
        $ret           = $this->pc->getPhpCodeCommentMultiLine(['The new inserted' => '$Id', '@return' => 'inserted id'], "\t");
        $getInsertedId = $this->xc->getXcEqualsOperator('$newInsertedId', "\$GLOBALS['xoopsDB']->getInsertId()", null, "\t\t");
        $getInsertedId .= $this->getSimpleString('return $newInsertedId;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getNewInsertedId' . $ucfTableName, '', $getInsertedId, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @private function getFunctionForm
     *
     * @param string $module
     * @param string $table
     * @param        $fieldId
     * @param        $fieldInForm
     * @param        $fieldUpload
     * @return string
     */
    private function getFunctionForm($module, $table, $fieldId, $fieldInForm, $fieldUpload)
    {
        $fe               = ClassFormElements::getInstance();
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $ucfTableName     = \ucfirst($tableName);
        $stuTableSoleName = \mb_strtoupper($tableSoleName);
        $language         = $this->getLanguage($moduleDirname, 'AM');
        $fe->initForm($module, $table);
        $ret              = $this->pc->getPhpCodeCommentMultiLine(['@public function' => 'getForm', '@param bool' => '$action', '@return' => '\XoopsThemeForm'], "\t");
        $action           = $this->xc->getXcEqualsOperator('$action', "\$_SERVER['REQUEST_URI']", null, "\t\t\t");
        $ucfModuleDirname = \ucfirst($moduleDirname);
        $getForm          = $this->xc->getXcGetInstance('helper', "\XoopsModules\\{$ucfModuleDirname}\Helper", "\t\t");
        $getForm          .= $this->pc->getPhpCodeConditions('!', '', '$action', $action, false, "\t\t");
        $xUser            = $this->pc->getPhpCodeGlobals('xoopsUser');
        $xModule          = $this->pc->getPhpCodeGlobals('xoopsModule');
        $getForm          .= $this->pc->getPhpCodeTernaryOperator('isAdmin', '\is_object(' . $xUser . ')', $xUser . '->isAdmin(' . $xModule . '->mid())', 'false', "\t\t");
        $getForm          .= $this->xc->getXcEqualsOperator('$isAdmin', "\is_object(\$GLOBALS['xoopsUser']) && \$GLOBALS['xoopsUser']->isAdmin(\$GLOBALS['xoopsModule']->mid())", null, "\t\t");

        if ($fieldUpload) {
            $permString = 'upload_groups';
            $getForm          .= $this->pc->getPhpCodeCommentLine('Permissions for', 'uploader', "\t\t");
            $getForm          .= $this->xc->getXcXoopsHandler('groupperm', "\t\t");
            $getForm          .= $this->pc->getPhpCodeTernaryOperator('groups', '\is_object(' . $xUser . ')', $xUser . '->getGroups()', '\XOOPS_GROUP_ANONYMOUS', "\t\t");
            $checkRight       = $this->xc->getXcCheckRight('$grouppermHandler', $permString, 32, '$groups', $xModule . '->getVar(\'mid\')', true);
            $getForm  .= $this->pc->getPhpCodeTernaryOperator('permissionUpload', $checkRight, 'true', 'false', "\t\t");
        }
        $getForm .= $this->pc->getPhpCodeCommentLine('Title', '', "\t\t");
        $getForm .= $this->pc->getPhpCodeTernaryOperator('title', '$this->isNew()', "{$language}{$stuTableSoleName}_ADD", "{$language}{$stuTableSoleName}_EDIT", "\t\t");
        $getForm .= $this->pc->getPhpCodeCommentLine('Get Theme', 'Form', "\t\t");
        $getForm .= $this->xc->getXcXoopsLoad('XoopsFormLoader', "\t\t");
        $getForm .= $this->cxc->getClassXoopsThemeForm('form', 'title', 'form', 'action', 'post');
        $getForm .= $this->cxc->getClassSetExtra('form', "'enctype=\"multipart/form-data\"'");
        $getForm .= $fe->renderElements();

        if (\in_array(1, $fieldInForm)) {
            if (1 == $table->getVar('table_permissions')) {
                $getForm .= $this->getPermissionsInForm($moduleDirname, $fieldId, $tableName);
            }
        }
        $getForm .= $this->pc->getPhpCodeCommentLine('To Save', '', "\t\t");
        $getForm .= $this->cxc->getClassAddElement('form', "new \XoopsFormHidden('op', 'save')");
        $getForm .= $this->cxc->getClassAddElement('form', "new \XoopsFormHidden('start', \$this->start)");
        $getForm .= $this->cxc->getClassAddElement('form', "new \XoopsFormHidden('limit', \$this->limit)");
        $getForm .= $this->cxc->getClassAddElement('form', "new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false)");
        $getForm .= $this->getSimpleString('return $form;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getForm' . $ucfTableName, '$action = false', $getForm, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @private function getPermissionsInForm
     *
     * @param string $moduleDirname
     * @param string $fieldId
     *
     * @param $tableName
     * @return string
     */
    private function getPermissionsInForm($moduleDirname, $fieldId, $tableName)
    {
        $permissionApprove = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_APPROVE');
        $permissionSubmit  = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_SUBMIT');
        $permissionView    = $this->getLanguage($moduleDirname, 'AM', 'PERMISSIONS_VIEW');
        $ret               = $this->pc->getPhpCodeCommentLine('Permissions', '', "\t\t");
        $ret               .= $this->xc->getXcXoopsHandler('member', "\t\t");
        $ret               .= $this->xc->getXcEqualsOperator('$groupList', '$memberHandler->getGroupList()', null, "\t\t");
        $ret               .= $this->xc->getXcXoopsHandler('groupperm',  "\t\t");
        $ret               .= $this->pc->getPhpCodeArrayType('fullList', 'keys', 'groupList');
        $fId               = $this->xc->getXcGetVar('', 'this', $fieldId, true);
        $mId               = $this->xc->getXcGetVar('', "GLOBALS['xoopsModule']", 'mid', true);
        $contElse          = $this->xc->getXcGetGroupIds('groupsIdsApprove', 'grouppermHandler', "'{$moduleDirname}_approve_{$tableName}'", $fId, $mId, "\t\t\t");
        $contElse          .= $this->pc->getPhpCodeArrayType('groupsIdsApprove', 'values', 'groupsIdsApprove', null, false, "\t\t\t");
        $contElse          .= $this->cxc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', $permissionApprove, "groups_approve_{$tableName}[]", '$groupsIdsApprove', false, "\t\t\t");
        $contElse          .= $this->xc->getXcGetGroupIds('groupsIdsSubmit', 'grouppermHandler', "'{$moduleDirname}_submit_{$tableName}'", $fId, $mId, "\t\t\t");
        $contElse          .= $this->pc->getPhpCodeArrayType('groupsIdsSubmit', 'values', 'groupsIdsSubmit', null, false, "\t\t\t");
        $contElse          .= $this->cxc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', $permissionSubmit, "groups_submit_{$tableName}[]", '$groupsIdsSubmit', false, "\t\t\t");
        $contElse          .= $this->xc->getXcGetGroupIds('groupsIdsView', 'grouppermHandler', "'{$moduleDirname}_view_{$tableName}'", $fId, $mId, "\t\t\t");
        $contElse          .= $this->pc->getPhpCodeArrayType('groupsIdsView', 'values', 'groupsIdsView', null, false, "\t\t\t");
        $contElse          .= $this->cxc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', $permissionView, "groups_view_{$tableName}[]", '$groupsIdsView', false, "\t\t\t");

        $contIf = $this->cxc->getClassXoopsFormCheckBox('groupsCanApproveCheckbox', $permissionApprove, "groups_approve_{$tableName}[]", '$fullList', false, "\t\t\t");
        $contIf .= $this->cxc->getClassXoopsFormCheckBox('groupsCanSubmitCheckbox', $permissionSubmit, "groups_submit_{$tableName}[]", '$fullList', false, "\t\t\t");
        $contIf .= $this->cxc->getClassXoopsFormCheckBox('groupsCanViewCheckbox', $permissionView, "groups_view_{$tableName}[]", '$fullList', false, "\t\t\t");

        $ret .= $this->pc->getPhpCodeConditions('$this->isNew()', '', '', $contIf, $contElse, "\t\t");
        $ret .= $this->pc->getPhpCodeCommentLine('To Approve', '', "\t\t");
        $ret .= $this->cxc->getClassAddOptionArray('groupsCanApproveCheckbox', '$groupList');
        $ret .= $this->cxc->getClassAddElement('form', '$groupsCanApproveCheckbox');
        $ret .= $this->pc->getPhpCodeCommentLine('To Submit', '', "\t\t");
        $ret .= $this->cxc->getClassAddOptionArray('groupsCanSubmitCheckbox', '$groupList');
        $ret .= $this->cxc->getClassAddElement('form', '$groupsCanSubmitCheckbox');
        $ret .= $this->pc->getPhpCodeCommentLine('To View', '', "\t\t");
        $ret .= $this->cxc->getClassAddOptionArray('groupsCanViewCheckbox', '$groupList');
        $ret .= $this->cxc->getClassAddElement('form', '$groupsCanViewCheckbox');

        return $ret;
    }

    /**
     * @private  function getValuesInObject
     *
     * @param $moduleDirname
     * @param $table
     * @param $fields
     * @return string
     * @internal param $null
     */
    private function getValuesInObject($moduleDirname, $table, $fields)
    {
        $ucfTableName     = \ucfirst($table->getVar('table_name'));
        $ret              = $this->pc->getPhpCodeCommentMultiLine(['Get' => 'Values', '@param null $keys' => '', '@param null $format' => '', '@param null $maxDepth' => '', '@return' => 'array'], "\t");
        $ucfModuleDirname = \ucfirst($moduleDirname);
        $language         = $this->getLanguage($moduleDirname, 'AM');
        $getValues        = $this->xc->getXcEqualsOperator('$ret', '$this->getValues($keys, $format, $maxDepth)', null, "\t\t");
        $tablePermissions = $table->getVar('table_permissions');
        $tableBroken      = $table->getVar('table_broken');
        $fieldMainTopic   = null;
        $helper           = 0;
        $utility          = 0;
        $header           = '';
        $configMaxchar    = 0;
        $lenMaxName       = 0;
        foreach (\array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $rpFieldName  = $this->getRightString($fieldName);
            $len         = \mb_strlen($rpFieldName);
            if (3 == $fields[$f]->getVar('field_element') || 4 == $fields[$f]->getVar('field_element')) {
                $len = $len + \mb_strlen('_short');
            }
            $lenMaxName = max($len, $lenMaxName);
        }
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->getRightString($fieldName);
            $spacer       = str_repeat(' ', $lenMaxName - \mb_strlen($rpFieldName));
            switch ($fieldElement) {
                case 3:
                    $getValues .= $this->pc->getPhpCodeStripTags("ret['{$rpFieldName}']{$spacer}", "\$this->getVar('{$fieldName}', 'e')", false, "\t\t");
                    if ($configMaxchar == 0) {
                        $getValues .= $this->xc->getXcEqualsOperator('$editorMaxchar', $this->xc->getXcGetConfig('editor_maxchar'), false, "\t\t");
                        $configMaxchar = 1;
                    }
                    $truncate  =  "\$utility::truncateHtml(\$ret['{$rpFieldName}'], \$editorMaxchar)";
                    $spacer    = str_repeat(' ', $lenMaxName - \mb_strlen($rpFieldName) - \mb_strlen('_short'));
                    $getValues .= $this->xc->getXcEqualsOperator("\$ret['{$rpFieldName}_short']{$spacer}", $truncate, false, "\t\t");
                    $helper = 1;
                    $utility = 1;
                    break;
                case 4:
                    $getValues .= $this->xc->getXcGetVar("ret['{$rpFieldName}']{$spacer}", 'this', $fieldName, false, "\t\t", ", 'e'");
                    if ($configMaxchar == 0) {
                        $getValues .= $this->xc->getXcEqualsOperator('$editorMaxchar', $this->xc->getXcGetConfig('editor_maxchar'), false, "\t\t");
                        $configMaxchar = 1;
                    }
                    $truncate  =  "\$utility::truncateHtml(\$ret['{$rpFieldName}'], \$editorMaxchar)";
                    $spacer    = str_repeat(' ', $lenMaxName - \mb_strlen($rpFieldName) - \mb_strlen('_short'));
                    $getValues .= $this->xc->getXcEqualsOperator("\$ret['{$rpFieldName}_short']{$spacer}", $truncate, false, "\t\t");
                    $helper = 1;
                    $utility = 1;
                    break;
                case 6:
                    $getValues .= $this->xc->getXcEqualsOperator("\$ret['{$rpFieldName}']{$spacer}", "(int)\$this->getVar('{$fieldName}') > 0 ? _YES : _NO", false, "\t\t");
                    break;
                case 8:
                    $getValues .= $this->xc->getXcXoopsUserUnameFromId("ret['{$rpFieldName}']{$spacer}", "\$this->getVar('{$fieldName}')", "\t\t");
                    break;
                case 15:
                    $getValues .= $this->xc->getXcFormatTimeStamp("ret['{$rpFieldName}']{$spacer}", "\$this->getVar('{$fieldName}')", 's', "\t\t");
                    break;
                case 16:
                    $spacer                                                  = str_repeat(' ', $lenMaxName - \mb_strlen('status') + 7);
                    $getValues .= $this->xc->getXcGetVar("status{$spacer}", 'this', $fieldName, false, "\t\t");
                    $spacer                                                  = str_repeat(' ', $lenMaxName - \mb_strlen('status'));
                    $getValues .= $this->xc->getXcEqualsOperator("\$ret['status']{$spacer}", '$status', false, "\t\t");
                    $contCase1  = $this->xc->getXcEqualsOperator('$status_text', $language . 'STATUS_NONE', false, "\t\t\t\t");
                    $cases[$this->xc->getXcGetConstants('STATUS_NONE')] = [$contCase1];
                    $contCase2  = $this->xc->getXcEqualsOperator('$status_text', $language . 'STATUS_OFFLINE', false, "\t\t\t\t");
                    $cases[$this->xc->getXcGetConstants('STATUS_OFFLINE')] = [$contCase2];
                    $contCase3  = $this->xc->getXcEqualsOperator('$status_text', $language . 'STATUS_SUBMITTED', false, "\t\t\t\t");
                    $cases[$this->xc->getXcGetConstants('STATUS_SUBMITTED')] = [$contCase3];
                    if (1 == $tablePermissions) {
                        $contCase4 = $this->xc->getXcEqualsOperator('$status_text', $language . 'STATUS_APPROVED', false, "\t\t\t\t");
                        $cases[$this->xc->getXcGetConstants('STATUS_APPROVED')] = [$contCase4];
                    }
                    if (1 == $tableBroken) {
                        $contCase5 = $this->xc->getXcEqualsOperator('$status_text', $language . 'STATUS_BROKEN', false, "\t\t\t\t");
                        $cases[$this->xc->getXcGetConstants('STATUS_BROKEN')] = [$contCase5];
                    }
                    $contentSwitch = $this->pc->getPhpCodeCaseSwitch($cases, true, false, "\t\t\t", true);
                    $getValues     .= $this->pc->getPhpCodeSwitch('status', $contentSwitch, "\t\t");
                    $len           = $lenMaxName - \mb_strlen('status_text');
                    $spacer        = $len > 0 ? str_repeat(' ', $len) : '';
                    $getValues     .= $this->xc->getXcEqualsOperator("\$ret['status_text']{$spacer}", '$status_text',  false, "\t\t");
                    break;
                case 21:
                    $getValues .= $this->xc->getXcFormatTimeStamp("ret['{$rpFieldName}']{$spacer}", "\$this->getVar('{$fieldName}')", 'm', "\t\t");
                    break;
                default:
                    $fieldElements    = $this->helper->getHandler('Fieldelements')->get($fieldElement);
                    $fieldElementTid  = $fieldElements->getVar('fieldelement_tid');
                    if ((int)$fieldElementTid > 0 ) {
                        $fieldElementMid = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementName = (string)$fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc = mb_substr($fieldElementName, \mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                        $topicTableName = \str_replace(': ', '', \mb_strtolower($fieldNameDesc));
                        $fieldsTopics = $this->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (\array_keys($fieldsTopics) as $g) {
                            $fieldNameTopic = $fieldsTopics[$g]->getVar('field_name');
                            if (1 == $fieldsTopics[$g]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $getValues .= $this->xc->getXcHandlerLine($topicTableName, "\t\t");
                        $getTopicTable = "\${$topicTableName}Handler->get(\$this->getVar('{$fieldName}'))";
                        $getValues .= $this->xc->getXcEqualsOperator("\${$topicTableName}Obj", $getTopicTable, null, "\t\t");
                        $fMainTopic = "\${$topicTableName}Obj->getVar('{$fieldMainTopic}')";
                        $getValues .= $this->xc->getXcEqualsOperator("\$ret['{$rpFieldName}']{$spacer}", $fMainTopic, null, "\t\t");
                        $helper = 1;
                    } else {
                        $getValues .= $this->xc->getXcGetVar("ret['{$rpFieldName}']{$spacer}", 'this', $fieldName, false, "\t\t");
                    }
                    break;
            }
        }
        if ($helper > 0) {
            $header .= $this->xc->getXcGetInstance('helper ', "\XoopsModules\\{$ucfModuleDirname}\Helper", "\t\t");
        }
        if ($utility > 0) {
            $header .= $this->xc->getXcEqualsOperator('$utility', "new \XoopsModules\\{$ucfModuleDirname}\Utility()", '',"\t\t");
        }
        $getValues .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getValues' . $ucfTableName, '$keys = null, $format = null, $maxDepth = null', $header . $getValues, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @private function getToArrayInObject
     *
     * @param $table
     *
     * @return string
     */
    private function getToArrayInObject($table)
    {
        $tableName    = $table->getVar('table_name');
        $ucfTableName = \ucfirst($tableName);
        $multiLineCom = ['Returns an array representation' => 'of the object', '' => '', '@return' => 'array'];
        $ret          = $this->pc->getPhpCodeCommentMultiLine($multiLineCom, "\t");

        $getToArray = $this->pc->getPhpCodeArray('ret', []);
        $getToArray .= $this->xc->getXcEqualsOperator('$vars', '$this->getVars()', null, "\t\t");
        $foreach    = $this->xc->getXcEqualsOperator('$ret[$var]', '$this->getVar($var)', null, "\t\t\t");
        $getToArray .= $this->pc->getPhpCodeForeach('vars', true, false, 'var', $foreach, "\t\t");
        $getToArray .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('toArray' . $ucfTableName, '', $getToArray, 'public ', false, "\t");

        return $ret;
    }

    /**
     * @private function getOptionsCheck
     *
     * @param $table
     *
     * @return string
     */
    private function getOptionsCheck($table)
    {
        $tableName    = $table->getVar('table_name');
        $ucfTableName = \ucfirst($tableName);
        $ret          = $this->pc->getPhpCodeCommentMultiLine(['Get' => 'Options'], "\t");
        $getOptions   = $this->pc->getPhpCodeArray('ret', [], false, "\t");

        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');

            $fieldElements  = $this->helper->getHandler('Fieldelements')->get($fieldElement);
            $fieldElementId = $fieldElements->getVar('fieldelement_id');
            $rpFieldName    = $this->getRightString($fieldName);
            if (5 == $fieldElementId) {
                $arrayPush  = $this->pc->getPhpCodeArrayType('ret', 'push', "'{$rpFieldName}'", null, false, "\t\t\t");
                $getOptions .= $this->pc->getPhpCodeConditions("\$this->getVar('{$fieldName}')", ' == ', '1', $arrayPush, false, "\t\t");
            }
        }

        $getOptions .= $this->getSimpleString('return $ret;', "\t\t");

        $ret .= $this->pc->getPhpCodeFunction('getOptions' . $ucfTableName, '', $getOptions, 'public ', false, "\t");

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
        $module         = $this->getModule();
        $table          = $this->getTable();
        $filename       = $this->getFileName();
        $moduleDirname  = $module->getVar('mod_dirname');
        $fields         = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));

        $namespace = $this->pc->getPhpCodeNamespace(['XoopsModules', $moduleDirname]);
        $content   = $this->getHeaderFilesComments($module, null, $namespace);
        $content   .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname]);
        $content   .= $this->getClassObject($module, $table, $fields);

        $this->create($moduleDirname, 'class', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
