<?php

namespace XoopsModules\Modulebuilder\Files\Classes;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Constants;

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
 *
 */

/**
 * Class ClassFormElements.
 */
class ClassFormElements extends Modulebuilder\Files\CreateAbstractClass
{
    /**
     * @var mixed
     */
    private $cf = null;

    /**
     * @var mixed
     */
    private $tf = null;

    /**
     * @var mixed
     */
    private $uxc = null;

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
        $this->helper = Modulebuilder\Helper::getInstance();
        $this->cf     = Modulebuilder\Files\CreateFile::getInstance();
        $this->xc     = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->tf     = Modulebuilder\Files\CreateTableFields::getInstance();
        $this->uxc    = Modulebuilder\Files\User\UserXoopsCode::getInstance();
        $this->cxc    = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     *
     * @return ClassFormElements
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
     * @public function initForm
     *
     * @param $module
     * @param $table
     */
    public function initForm($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /**
     * @private function getXoopsFormText
     *
     * @param        $language
     * @param        $fieldName
     * @param        $fieldDefault
     * @param string $required
     * @return string
     */
    private function getXoopsFormText($language, $fieldName, $fieldDefault, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        if ('' != $fieldDefault) {
            $ret      = $this->pc->getPhpCodeCommentLine('Form Text', $ccFieldName, "\t\t");
            $ret      .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", "\t\t");
            $formText = $this->cxc->getClassXoopsFormText('', $language, $fieldName, 20, 150, $ccFieldName, true);
            $ret      .= $this->cxc->getClassAddElement('form', $formText . $required);
        } else {
            $ret      = $this->pc->getPhpCodeCommentLine('Form Text', $ccFieldName, "\t\t");
            $formText = $this->cxc->getClassXoopsFormText('', $language, $fieldName, 50, 255, "this->getVar('{$fieldName}')", true);
            $ret      .= $this->cxc->getClassAddElement('form', $formText . $required);
        }

        return $ret;
    }

    /**
     * @private function getXoopsFormText
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextArea($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $ret          = $this->pc->getPhpCodeCommentLine('Form Editor', 'TextArea ' . $ccFieldName, "\t\t");
        $formTextArea = $this->cxc->getClassXoopsFormTextArea('', $language, $fieldName, 4, 47, true);
        $ret          .= $this->cxc->getClassAddElement('form', $formTextArea . $required);

        return $ret;
    }

    /**
     * @private function getXoopsFormDhtmlTextArea
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormDhtmlTextArea($language, $fieldName, $required = 'false')
    {
        $ccFieldName = $this->cf->getCamelCase($fieldName, false, true);
        $ret         = $this->pc->getPhpCodeCommentLine('Form Editor', 'DhtmlTextArea ' . $ccFieldName, "\t\t");
        $ret         .= $this->pc->getPhpCodeArray('editorConfigs', null, false, "\t\t");
        $getConfig    = $this->xc->getXcGetConfig('editor_admin');
        $contIf       = $this->xc->getXcEqualsOperator("\$editor", $getConfig, null, "\t\t\t");
        $getConfig    = $this->xc->getXcGetConfig('editor_user');
        $contElse     = $this->xc->getXcEqualsOperator("\$editor", $getConfig, null, "\t\t\t");
        $ret      .= $this->pc->getPhpCodeConditions('$isAdmin','','', $contIf,  $contElse, "\t\t");


        $configs     = [
            'name'   => "'{$fieldName}'",
            'value'  => "\$this->getVar('{$fieldName}', 'e')",
            'rows'   => 5,
            'cols'   => 40,
            'width'  => "'100%'",
            'height' => "'400px'",
            'editor' => '$editor',
        ];
        foreach ($configs as $c => $d) {
            $ret .= $this->xc->getXcEqualsOperator("\$editorConfigs['{$c}']", $d, null, "\t\t");
        }
        $formEditor = $this->cxc->getClassXoopsFormEditor('', $language, $fieldName, 'editorConfigs', true);
        $ret        .= $this->cxc->getClassAddElement('form', $formEditor . $required);

        return $ret;
    }

    /**
     * @private function getXoopsFormCheckBox
     *
     * @param        $language
     * @param        $tableSoleName
     * @param        $fieldName
     * @param        $fieldElementId
     * @param string $required
     * @return string
     */
    private function getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required = 'false')
    {
        $stuTableSoleName = \mb_strtoupper($tableSoleName);
        $ucfFieldName     = $this->cf->getCamelCase($fieldName, true);
        $ccFieldName      = $this->cf->getCamelCase($fieldName, false, true);
        $t                = "\t\t";
        if (\in_array(5, $fieldElementId) > 1) {
            $ret     = $this->pc->getPhpCodeCommentLine('Form Check Box', 'List Options ' . $ccFieldName, $t);
            $ret     .= $this->xc->getXcEqualsOperator('$checkOption', '$this->getOptions()');
            $foreach = $this->cxc->getClassXoopsFormCheckBox('check' . $ucfFieldName, '<hr />', $tableSoleName . '_option', '$checkOption', false, $t . "\t");
            $foreach .= $this->cxc->getClassSetDescription('check' . $ucfFieldName, "{$language}{$stuTableSoleName}_OPTIONS_DESC", $t . "\t");
            $foreach .= $this->cxc->getClassAddOption('check' . $ucfFieldName, "\$option, {$language}{$stuTableSoleName}_ . strtoupper(\$option)", $t . "\t");
            $ret     .= $this->pc->getPhpCodeForeach("{$tableSoleName}All", false, false, 'option', $foreach, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $this->cxc->getClassAddElement('form', $intElem, $t);
        } else {
            $ret     = $this->pc->getPhpCodeCommentLine('Form Check Box', $ccFieldName, $t);
            $ret     .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
            $ret     .= $this->cxc->getClassXoopsFormCheckBox('check' . $ucfFieldName, $language, $fieldName, "\${$ccFieldName}", false, $t);
            $option  = "1, {$language}";
            $ret     .= $this->cxc->getClassAddOption('check' . $ucfFieldName, $option, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $this->cxc->getClassAddElement('form', $intElem, $t);
        }

        return $ret;
    }

    /**
     * @private function getXoopsFormHidden
     *
     * @param $fieldName
     *
     * @return string
     */
    private function getXoopsFormHidden($fieldName)
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $ret          = $this->pc->getPhpCodeCommentLine('Form Hidden', $ccFieldName, "\t\t");
        $formHidden   = $this->cxc->getClassXoopsFormHidden('', $fieldName, $fieldName, true, true);
        $ret          .= $this->cxc->getClassAddElement('form', $formHidden);

        return $ret;
    }

    /**
     * @private function getXoopsFormImageList
     *          provides listbox for select image, a preview of image and an upload field
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormImageList($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $ucfFieldName    = $this->cf->getCamelCase($fieldName, true);
        $ccFieldName     = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort   = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $this->pc->getPhpCodeCommentLine('Form Frameworks Images', 'Files ' . $ccFieldName, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine('Form Frameworks Images', $ccFieldName .': Select Uploaded Image', $t);
        $ret             .= $this->xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '', "'blank.gif'", $t);
        $ret             .= $this->xc->getXcEqualsOperator('$imageDirectory', "'/Frameworks/moduleclasses/icons/32'", null, $t);
        $ret             .= $this->cxc->getClassXoopsFormElementTray('imageTray', $language, '<br>', $t);
        $sprintf         = $this->pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$imageDirectory}/"');
        $ret             .= $this->cxc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $this->xc->getXcXoopsListImgListArray('imageArray', '\XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $this->cxc->getClassAddOption('imageSelect', '($image1), $image1', $t . "\t");
        $ret             .= $this->pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"imglabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\" . \$imageDirectory . '\", \"\", \"' . \XOOPS_URL . \"\\\")'\"";
        $ret             .= $this->cxc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $this->cxc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel      = "\"<br><img src='\" . \XOOPS_URL . '/' . \$imageDirectory . '/' . \${$ccFieldName} . \"' id='imglabel_{$fieldName}' alt='' style='max-width:100px' >\"";
        $xoopsFormLabel  = $this->cxc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $ret             .= $this->cxc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine('Form Frameworks Images', $ccFieldName .': Upload new image', $t);
        $ret             .= $this->cxc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br>', $t);
        $getConfig       = $this->xc->getXcGetConfig('maxsize_image');
        $xoopsFormFile   = $this->cxc->getClassXoopsFormFile('', $languageShort . 'FORM_UPLOAD_NEW', $fieldName, $getConfig, true, '');
        $ret             .= $this->cxc->getClassAddElement('fileSelectTray', $xoopsFormFile, $t);
        $xoopsFormLabel1 = $this->cxc->getClassXoopsFormLabel('', "''", null, true, $t);
        $ret             .= $this->cxc->getClassAddElement('fileSelectTray', $xoopsFormLabel1, $t);
        $ret             .= $this->cxc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $ret             .= $this->cxc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectFile
     *          provides listbox for select file and an upload field
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormSelectFile($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $ucfFieldName    = $this->cf->getCamelCase($fieldName, true);
        $ccFieldName     = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort   = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $this->pc->getPhpCodeCommentLine('Form File', $ccFieldName, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine("Form File {$ccFieldName}:", 'Select Uploaded File ', $t);
        $ret             .= $this->xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '', "'blank.gif'", $t);
        $ret             .= $this->xc->getXcEqualsOperator('$fileDirectory', "'/uploads/{$moduleDirname}/files/{$tableName}'", null, $t);
        $ret             .= $this->cxc->getClassXoopsFormElementTray('fileTray', $language, '<br>', $t);
        $sprintf         = $this->pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$fileDirectory}/"');
        $ret             .= $this->cxc->getClassXoopsFormSelect('fileSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $this->xc->getXcXoopsListImgListArray('fileArray', '\XOOPS_ROOT_PATH . $fileDirectory', $t);
        $contForeach     = $this->cxc->getClassAddOption('fileSelect', '($file1), $file1', $t . "\t");
        $ret             .= $this->pc->getPhpCodeForeach('fileArray', false, false, 'file1', $contForeach, $t);
        //TODO: make preview for images or show "no preview possible"
        //$setExtraParam   = "\"onchange='showImgSelected(\\\"imglabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\" . \$imageDirectory . '\", \"\", \"' . \XOOPS_URL . \"\\\")'\"";
        //$ret             .= $cc->getClassSetExtra('fileSelect', $setExtraParam, $t);
        $ret             .= $this->cxc->getClassAddElement('fileTray', '$fileSelect, false', $t);
        //$paramLabel      = "\"<br><img src='\" . \XOOPS_URL . '/' . \$imageDirectory . '/' . \${$ccFieldName} . \"' id='imglabel_{$fieldName}' alt='' style='max-width:100px' />\"";
        //$xoopsFormLabel  = $cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        //$ret             .= $cc->getClassAddElement('fileTray', $xoopsFormLabel, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine("Form File {$ccFieldName}:", 'Upload new file', $t);
        $getConfigSize   = $this->xc->getXcGetConfig('maxsize_file');
        $contIf          = $this->xc->getXcEqualsOperator('$maxsize', $getConfigSize,'', "\t\t\t");
        $xoopsFormFile   = $this->cxc->getClassXoopsFormFile('fileTray', "'<br>' . " . $languageShort . 'FORM_UPLOAD_NEW', $fieldName, '$maxsize', true, '');
        $contIf          .= $this->cxc->getClassAddElement('fileTray', $xoopsFormFile, $t . "\t");
        $configText      = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $this->cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $contIf          .= $this->cxc->getClassAddElement('fileTray', $labelInfo1, $t . "\t");
        $formHidden      = $this->cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, $t, true);
        $contElse        = $this->cxc->getClassAddElement('fileTray', $formHidden, $t . "\t");
        $ret             .= $this->pc->getPhpCodeConditions('$permissionUpload', null, null, $contIf, $contElse, "\t\t");
        $ret             .= $this->cxc->getClassAddElement('form', "\$fileTray{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormUrlFile
     *          provides textbox with last uploaded url and an upload field
     *
     * @param   $language
     * @param   $moduleDirname
     * @param   $fieldName
     * @param   $fieldDefault
     * @param   $required
     *
     * @return string
     */
    private function getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required = 'false')
    {
        $ccFieldName    = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $this->pc->getPhpCodeCommentLine('Form Url', 'Text File ' . $ccFieldName, $t);
        $ret           .= $this->cxc->getClassXoopsFormElementTray('formUrlFile', $language, '<br><br>', $t);
        $ret           .= $this->pc->getPhpCodeTernaryOperator('formUrl', '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", $t);
        $ret           .= $this->cxc->getClassXoopsFormText('formText', $language . '_UPLOADS', $fieldName, 75, 255, 'formUrl', false, $t);
        $ret           .= $this->cxc->getClassAddElement('formUrlFile', '$formText' . $required, $t);
        $getConfig     = $this->xc->getXcGetConfig('maxsize_file');
        $xoopsFormFile = $this->cxc->getClassXoopsFormFile('', $languageShort . 'FORM_UPLOAD', $fieldName, $getConfig, true, '');
        $ret           .= $this->cxc->getClassAddElement('formUrlFile', $xoopsFormFile . $required, $t);
        $ret           .= $this->cxc->getClassAddElement('form', '$formUrlFile', $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormUploadImage
     *          provides listbox for select image, a preview of image and an upload field
     *
     * @param        $language
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormUploadImage($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $ucfFieldName    = $this->cf->getCamelCase($fieldName, true);
        $ccFieldName     = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort   = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $this->pc->getPhpCodeCommentLine('Form Image', $ccFieldName, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine("Form Image {$ccFieldName}:", 'Select Uploaded Image ', $t);
        $ret             .= $this->xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '', "'blank.gif'", $t);
        $ret             .= $this->xc->getXcEqualsOperator('$imageDirectory', "'/uploads/{$moduleDirname}/images/{$tableName}'", null, $t);
        $ret             .= $this->cxc->getClassXoopsFormElementTray('imageTray', $language, '<br>', $t);
        $sprintf         = $this->pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$imageDirectory}/"');
        $ret             .= $this->cxc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $this->xc->getXcXoopsListImgListArray('imageArray', '\XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $this->cxc->getClassAddOption('imageSelect', '($image1), $image1', $t . "\t");
        $ret             .= $this->pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"imglabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\" . \$imageDirectory . '\", \"\", \"' . \XOOPS_URL . \"\\\")'\"";
        $ret             .= $this->cxc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $this->cxc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel      = "\"<br><img src='\" . \XOOPS_URL . '/' . \$imageDirectory . '/' . \${$ccFieldName} . \"' id='imglabel_{$fieldName}' alt='' style='max-width:100px' >\"";
        $xoopsFormLabel  = $this->cxc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $ret             .= $this->cxc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $this->pc->getPhpCodeCommentLine("Form Image {$ccFieldName}:", 'Upload new image', $t);
        $getConfigSize   = $this->xc->getXcGetConfig('maxsize_image');
        $contIf          = $this->xc->getXcEqualsOperator('$maxsize', $getConfigSize,'', "\t\t\t");
        $xoopsFormFile   = $this->cxc->getClassXoopsFormFile('imageTray', "'<br>' . " . $languageShort . 'FORM_UPLOAD_NEW', $fieldName, '$maxsize', true, '');
        $contIf          .= $this->cxc->getClassAddElement('imageTray', $xoopsFormFile, $t . "\t");
        $configText      = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $this->cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $contIf          .= $this->cxc->getClassAddElement('imageTray', $labelInfo1, $t . "\t");
        $getConfig       = $this->xc->getXcGetConfig('maxwidth_image');
        $labelInfo2      = $this->cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_IMG_WIDTH', $getConfig . " . ' px'", true, '');
        $contIf          .= $this->cxc->getClassAddElement('imageTray', $labelInfo2, $t . "\t");
        $getConfig       = $this->xc->getXcGetConfig('maxheight_image');
        $labelInfo3      = $this->cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_IMG_HEIGHT', $getConfig . " . ' px'", true, '');
        $contIf          .= $this->cxc->getClassAddElement('imageTray', $labelInfo3, $t . "\t");
        $formHidden      = $this->cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, $t, true);
        $contElse        = $this->cxc->getClassAddElement('imageTray', $formHidden, $t . "\t");
        $ret             .= $this->pc->getPhpCodeConditions('$permissionUpload', null, null, $contIf, $contElse, "\t\t");
        $ret             .= $this->cxc->getClassAddElement('form', "\$imageTray{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormUploadFile
     *          provides label with last uploaded file and an upload field
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     *
     * @param string $required
     * @return string
     */
    private function getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $ccFieldName    = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort  = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t              = "\t\t\t";
        $ret            = $this->pc->getPhpCodeCommentLine('Form File:', 'Upload ' . $ccFieldName, "\t\t");
        $ret            .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "''", "\$this->getVar('{$fieldName}')", "\t\t");
        $uForm          = $this->cxc->getClassXoopsFormElementTray('fileUploadTray', $language, '<br>', $t);
        $uForm          .= $this->xc->getXcEqualsOperator('$fileDirectory', "'/uploads/{$moduleDirname}/files/{$tableName}'", null, $t);
        $sprintf        = $this->pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$fileDirectory}/"');
        $xoopsFormLabel = $this->cxc->getClassXoopsFormLabel('', $sprintf, $ccFieldName, true, "\t\t", true);
        $condIf         = $this->cxc->getClassAddElement('fileUploadTray', $xoopsFormLabel, $t . "\t");
        $uForm          .= $this->pc->getPhpCodeConditions('!$this->isNew()', null, null, $condIf, false, "\t\t\t");
        $getConfig      = $this->xc->getXcGetConfig('maxsize_file');
        $uForm          .= $this->xc->getXcEqualsOperator('$maxsize', $getConfig,'', "\t\t\t");
        $xoopsFormFile  = $this->cxc->getClassXoopsFormFile('', "''", $fieldName, '$maxsize', true, '');
        $uForm          .= $this->cxc->getClassAddElement('fileUploadTray', $xoopsFormFile, $t);
        $configText     = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $this->cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $uForm          .= $this->cxc->getClassAddElement('fileUploadTray', $labelInfo1, $t );
        $uForm          .= $this->cxc->getClassAddElement('form', "\$fileUploadTray{$required}", $t);
        $formHidden     = $this->cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, "\t\t", true);
        $contElse       = $this->cxc->getClassAddElement('form', $formHidden, $t);

        $ret           .= $this->pc->getPhpCodeConditions('$permissionUpload', null, null, $uForm, $contElse, "\t\t");

        return $ret;
    }

    /**
     * @private function getXoopsFormColorPicker
     *
     * @param $language
     * @param $fieldName
     *
     * @param string $required
     * @return string
     */
    private function getXoopsFormColorPicker($language, $fieldName, $required = 'false')
    {
        $ccFieldName   = $this->cf->getCamelCase($fieldName, false, true);
        $t             = "\t\t";
        $ret           = $this->pc->getPhpCodeCommentLine('Form Color', 'Picker ' . $ccFieldName, $t);
        $getVar        = $this->xc->getXcGetVar('', 'this', $fieldName, true);
        $xoopsFormFile = $this->cxc->getClassXoopsFormColorPicker('', $language, $fieldName, $getVar, true, '');
        $ret           .= $this->cxc->getClassAddElement('form', $xoopsFormFile. $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectBox
     *
     * @param $language
     * @param $tableName
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormSelectBox($language, $tableName, $fieldName, $required = 'false')
    {
        $ucfTableName = \ucfirst($tableName);
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $this->pc->getPhpCodeCommentLine($ucfTableName, 'Handler', $t);
        $ret          .= $this->xc->getXcHandlerLine($tableName, $t);
        $ret          .= $this->pc->getPhpCodeCommentLine('Form', 'Select ' . $ccFieldName, $t);
        $ret          .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'Empty'", $t);
        $ret          .= $this->cxc->getClassAddOptionArray($ccFieldName . 'Select', "\${$tableName}Handler->getList()", $t);
        $ret          .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectUser
     *
     * @param        $language
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormSelectUser($language, $fieldName, $required = 'false')
    {
        $ccFieldName     = $this->cf->getCamelCase($fieldName, false, true);
        $t               = "\t\t";
        $ret             = $this->pc->getPhpCodeCommentLine('Form Select', 'User ' . $ccFieldName, $t);
        $ret             .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '$GLOBALS[\'xoopsUser\']->uid()', "\$this->getVar('{$fieldName}')", $t);
        $xoopsSelectUser = $this->cxc->getClassXoopsFormSelectUser('', $language, $fieldName, 'false', '$' . $ccFieldName, true, $t);
        $ret             .= $this->cxc->getClassAddElement('form', $xoopsSelectUser . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormRadioYN
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormRadioYN($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $this->pc->getPhpCodeCommentLine('Form Radio', 'Yes/No ' . $ccFieldName, $t);
        $ret          .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsRadioYN = $this->cxc->getClassXoopsFormRadioYN('', $language, $fieldName, $ccFieldName, true, $t);
        $ret          .= $this->cxc->getClassAddElement('form', $xoopsRadioYN . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTextDateSelect
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTextDateSelect($language, $fieldName, $required = 'false')
    {
        $t                   = "\t\t";
        $ccFieldName         = $this->cf->getCamelCase($fieldName, false, true);
        $ret                 = $this->pc->getPhpCodeCommentLine('Form Text', 'Date Select ' . $ccFieldName, $t);
        $ret                 .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '\time()', "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $this->cxc->getClassXoopsFormTextDateSelect('', $language, $fieldName, $fieldName, $ccFieldName, true, $t);
        $ret                 .= $this->cxc->getClassAddElement('form', $xoopsTextDateSelect . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormDateTime
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormDateTime($language, $fieldName, $required = 'false')
    {
        $t                   = "\t\t";
        $ccFieldName         = $this->cf->getCamelCase($fieldName, false, true);
        $ret                 = $this->pc->getPhpCodeCommentLine('Form Text', 'Date Select ' . $ccFieldName, $t);
        $ret                 .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '\time()', "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $this->cxc->getClassXoopsFormDateTime('', $language, $fieldName, $fieldName, $ccFieldName, true, $t);
        $ret                 .= $this->cxc->getClassAddElement('form', $xoopsTextDateSelect . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectStatus
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param $tablePermissions
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectStatus($language, $moduleDirname, $fieldName, $tablePermissions, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t            = "\t\t";
        $ret          = $this->pc->getPhpCodeCommentLine('Form Select', 'Status ' . $ccFieldName, $t);
        if (1 == $tablePermissions) {
            $ret .= $this->xc->getXcHandlerLine('permissions', $t);
        }
        $ret          .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', $this->xc->getXcGetConstants('STATUS_NONE') . ", {$languageShort}STATUS_NONE", $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', $this->xc->getXcGetConstants('STATUS_OFFLINE') . ", {$languageShort}STATUS_OFFLINE", $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', $this->xc->getXcGetConstants('STATUS_SUBMITTED') . ", {$languageShort}STATUS_SUBMITTED", $t);
        if (1 == $tablePermissions) {
            $contIf = $this->cxc->getClassAddOption($ccFieldName . 'Select', $this->xc->getXcGetConstants('STATUS_APPROVED') . ", {$languageShort}STATUS_APPROVED", $t . "\t");
            $ret .= $this->pc->getPhpCodeConditions('$permissionsHandler->getPermGlobalApprove()', '', '', $contIf, false, $t);
        }
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', $this->xc->getXcGetConstants('STATUS_BROKEN') . ", {$languageShort}STATUS_BROKEN", $t);
        $ret          .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormPassword
     *
     * @param $language
     * @param $fieldName
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormPassword($language, $fieldName, $required = 'false')
    {
        $t             = "\t\t";
        $ccFieldName   = $this->cf->getCamelCase($fieldName, false, true);
        $ret           = $this->pc->getPhpCodeCommentLine('Form Text', 'Enter Password ' . $ccFieldName, $t);
        $xoopsPassword = $this->cxc->getClassXoopsFormPassword('', $language, $fieldName, 10, 32, true, $t);
        $ret           .= $this->cxc->getClassAddElement('form', $xoopsPassword . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectCountry
     *
     * @param $language
     * @param $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectCountry($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $this->pc->getPhpCodeCommentLine('Form Select', 'Country ' . $ccFieldName, $t);
        $ret          .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'', \_NONE", $t);
        $ret          .= $this->xc->getXcXoopsListCountryList('countryArray', $t);
        $ret          .= $this->cxc->getClassAddOptionArray($ccFieldName . 'Select', '$countryArray', $t);
        $ret          .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectLang
     *
     * @param $language
     * @param $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectLang($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $this->pc->getPhpCodeCommentLine('Form Select', 'Lang ' . $ccFieldName, $t);
        $ret          .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'', \_NONE", $t);
        $ret          .= $this->xc->getXcXoopsListLangList('langArray', $t);
        $ret          .= $this->cxc->getClassAddOptionArray($ccFieldName . 'Select', '$langArray', $t);
        $ret          .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormRadio
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormRadio($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $ccFieldName   = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $this->pc->getPhpCodeCommentLine('Form Radio', $ccFieldName, $t);
        $ret           .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '0', "\$this->getVar('{$fieldName}')", $t);
        $ret           .= $this->cxc->getClassXoopsFormRadio($ccFieldName . 'Select', $language, $fieldName, "{$ccFieldName}", false, $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'0', \_NONE", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'1', {$languageShort}LIST_1", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'2', {$languageShort}LIST_2", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'3', {$languageShort}LIST_3", $t);
        $ret           .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectCombo
     *
     * @param $language
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectCombo($language, $moduleDirname, $tableName, $fieldName, $required = 'false')
    {
        $ucfTableName  = \ucfirst($tableName);
        $ccFieldName   = $this->cf->getCamelCase($fieldName, false, true);
        $languageShort = \substr($language, 0, 5) . \mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $this->pc->getPhpCodeCommentLine($ucfTableName, 'Handler', $t);
        $ret           .= $this->xc->getXcHandlerLine($tableName, $t);
        $ret           .= $this->pc->getPhpCodeCommentLine('Form', 'Select ' . $ccFieldName, $t);
        $ret           .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", '5', '', false, $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'0', \_NONE", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'1', {$languageShort}LIST_1", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'2', {$languageShort}LIST_2", $t);
        $ret           .= $this->cxc->getClassAddOption($ccFieldName . 'Select', "'3', {$languageShort}LIST_3", $t);
        $ret           .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTable
     *
     * @param $language
     * @param $fieldName
     * @param $fieldElement
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTable($language,$fieldName, $fieldElement, $required = 'false')
    {
        $t   = "\t\t";
        $ret = '';
        $fElement           = $this->helper->getHandler('Fieldelements')->get($fieldElement);
        $rpFieldelementName = \mb_strtolower(\str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
        $ret                .= $this->pc->getPhpCodeCommentLine('Form Table', $rpFieldelementName, $t);
        $ccFieldName        = $this->cf->getCamelCase($fieldName, false, true);
        $ret                .= $this->xc->getXcHandlerLine($rpFieldelementName, $t);
        $ret                .= $this->cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret                .= $this->cxc->getClassAddOptionArray($ccFieldName . 'Select', "\${$rpFieldelementName}Handler->getList()", $t);
        $ret                .= $this->cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

        return $ret;
    }

    /**
     * @private  function getXoopsFormTopic
     *
     * @param        $language
     * @param        $topicTableName
     * @param        $fieldId
     * @param        $fieldPid
     * @param        $fieldMain
     * @return string
     */
    private function getXoopsFormTopic($language, $topicTableName, $fieldId, $fieldPid, $fieldMain)
    {
        $ucfTopicTableName = \ucfirst($topicTableName);
        $stlTopicTableName = \mb_strtolower($topicTableName);
        $ccFieldPid        = $this->cf->getCamelCase($fieldPid, false, true);
        $t                 = "\t\t";
        $ret               = $this->pc->getPhpCodeCommentLine('Form Table', $ucfTopicTableName, $t);
        $ret               .= $this->xc->getXcHandlerLine($stlTopicTableName, $t);
        $ret               .= $this->xc->getXcCriteriaCompo('cr' . $ucfTopicTableName, $t);
        $ret               .= $this->xc->getXcHandlerCountClear($stlTopicTableName . 'Count', $stlTopicTableName, '$cr' . $ucfTopicTableName, $t);
        $contIf            = $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/tree', true, false, 'require', $t . "\t");
        $contIf            .= $this->xc->getXcHandlerAllClear($stlTopicTableName . 'All', $stlTopicTableName, '$cr' . $ucfTopicTableName, $t . "\t");
        $contIf            .= $this->cxc->getClassXoopsObjectTree($stlTopicTableName . 'Tree', $stlTopicTableName . 'All', $fieldId, $fieldPid, $t . "\t");
        $contIf            .= $this->cxc->getClassXoopsMakeSelBox($ccFieldPid, $stlTopicTableName . 'Tree', $fieldPid, $fieldMain, '--', $fieldPid, $t . "\t");
        $formLabel         = $this->cxc->getClassXoopsFormLabel('', $language, "\${$ccFieldPid}", true, '');
        $contIf            .= $this->cxc->getClassAddElement('form', $formLabel, $t . "\t");
        $ret               .= $this->pc->getPhpCodeConditions("\${$stlTopicTableName}Count", null, null, $contIf, false, $t);
        $ret               .= $this->pc->getPhpCodeUnset('cr' . $ucfTopicTableName, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTag
     *
     * @param $fieldId
     * @param $required
     *
     * @return string
     */
    private function getXoopsFormTag($fieldId, $required = 'false')
    {
        $t         = "\t\t";
        $ret       = $this->pc->getPhpCodeCommentLine('Use tag', 'module', $t);
        $isDir     = $this->pc->getPhpCodeIsDir("\XOOPS_ROOT_PATH . '/modules/tag'");
        $ret       .= $this->pc->getPhpCodeTernaryOperator('dirTag', $isDir, 'true', 'false', $t);
        $paramIf   = '(' . $this->xc->getXcGetConfig('usetag') . ' == 1)';
        $condIf    = $this->pc->getPhpCodeTernaryOperator('tagId', '$this->isNew()', '0', "\$this->getVar('{$fieldId}')", $t . "\t");
        $condIf    .= $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'modules/tag/include/formtag', true, false, $type = 'require', $t . "\t");
        $paramElem = $this->cxc->getClassXoopsFormTag('', 'tag', 60, 255, 'tagId', 0, true, '');
        $condIf    .= $this->cxc->getClassAddElement('form', $paramElem . $required, $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions($paramIf, ' && ', '$dirTag', $condIf, false, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormTextUuid
     *
     * @param        $language
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormTextUuid($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);
        $ret      = $this->pc->getPhpCodeCommentLine('Form Text Uuid', $ccFieldName, "\t\t");
        $ret      .= $this->pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '\Xmf\Uuid::generate()', "\$this->getVar('{$fieldName}')", "\t\t");
        $formText = $this->cxc->getClassXoopsFormText('', $language, $fieldName, 50, 150, $ccFieldName, true);
        $ret      .= $this->cxc->getClassAddElement('form', $formText . $required);


        return $ret;
    }
    
    /**
     * @private function getXoopsFormTextIp
     *
     * @param        $language
     * @param        $fieldName
     * @param string $required
     * @return string
     */
    private function getXoopsFormTextIp($language, $fieldName, $required = 'false')
    {
        $ccFieldName  = $this->cf->getCamelCase($fieldName, false, true);

        $ret      = $this->pc->getPhpCodeCommentLine('Form Text IP', $ccFieldName, "\t\t");
        $ret      .= $this->xc->getXcEqualsOperator('$' . $ccFieldName, "\$_SERVER['REMOTE_ADDR']", null, "\t\t");
        $formText = $this->cxc->getClassXoopsFormText('', $language, $fieldName, 20, 150, $ccFieldName, true);
        $ret      .= $this->cxc->getClassAddElement('form', $formText . $required);

        return $ret;
    }

    /**
     * @public function renderElements
     * @param null
     * @return string
     */
    public function renderElements()
    {
        $module           = $this->getModule();
        $table            = $this->getTable();
        $moduleDirname    = $module->getVar('mod_dirname');
        $tableName        = $table->getVar('table_name');
        $tableSoleName    = $table->getVar('table_solename');
        $tablePermissions = $table->getVar('table_permissions');
        $languageFunct    = $this->cf->getLanguage($moduleDirname, 'AM');
        $ret               = '';
        $fields            = $this->tf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order ASC, field_id');
        $fieldId           = '';
        $fieldIdTopic      = '';
        $fieldPidTopic     = '';
        $fieldMainTopic    = '';
        $fieldElementId    = [];
        $counter           = 0;
        $tagDone           = false;
        foreach (\array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldParent  = $fields[$f]->getVar('field_parent');
            $fieldInForm  = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            $rpFieldName = $this->cf->getRightString($fieldName);
            $language    = $languageFunct . \mb_strtoupper($tableSoleName) . '_' . \mb_strtoupper($rpFieldName);
            $required    = (1 == $fields[$f]->getVar('field_required')) ? ', true' : '';

            $fieldElements    = $this->helper->getHandler('Fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');

            if (1 == $fieldInForm) {
                $counter++;
                // Switch elements
                switch ($fieldElement) {
                    case 1:
                        break;
                    case Constants::FIELD_ELE_TEXT:  // textbox
                    case Constants::FIELD_ELE_TEXTCOMMENTS: // textbox comments
                    case Constants::FIELD_ELE_TEXTRATINGS: // textbox ratings
                    case Constants::FIELD_ELE_TEXTVOTES: // textbox votes
                    case Constants::FIELD_ELE_TEXTREADS: // textbox reads
                        $ret .= $this->getXoopsFormText($language, $fieldName, $fieldDefault, $required);
                        break;
                    case Constants::FIELD_ELE_TEXTAREA:
                        $ret .= $this->getXoopsFormTextArea($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_DHTMLTEXTAREA:
                        $ret .= $this->getXoopsFormDhtmlTextArea($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_CHECKBOX:
                        $ret .= $this->getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required);
                        break;
                    case Constants::FIELD_ELE_RADIOYN:
                        $ret .= $this->getXoopsFormRadioYN($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTBOX:
                        $ret .= $this->getXoopsFormSelectBox($language, $tableName, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTUSER:
                        $ret .= $this->getXoopsFormSelectUser($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_COLORPICKER:
                        $ret .= $this->getXoopsFormColorPicker($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_IMAGELIST:
                        $ret .= $this->getXoopsFormImageList($language, $moduleDirname, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTFILE:
                        $ret .= $this->getXoopsFormSelectFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_URLFILE:
                        $ret .= $this->getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required);
                        break;
                    case Constants::FIELD_ELE_UPLOADIMAGE:
                        $ret .= $this->getXoopsFormUploadImage($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_UPLOADFILE:
                        $ret .= $this->getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_TEXTDATESELECT:
                        $ret .= $this->getXoopsFormTextDateSelect($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTSTATUS:
                        $ret .= $this->getXoopsFormSelectStatus($language, $moduleDirname, $fieldName, $tablePermissions, $required);
                        break;
                    case Constants::FIELD_ELE_PASSWORD:
                        $ret .= $this->getXoopsFormPassword($language,  $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTCOUNTRY:
                        $ret .= $this->getXoopsFormSelectCountry($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTLANG:
                        $ret .= $this->getXoopsFormSelectLang($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_RADIO:
                        $ret .= $this->getXoopsFormRadio($language, $moduleDirname, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_DATETIME:
                        $ret .= $this->getXoopsFormDateTime($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_SELECTCOMBO:
                        $ret .= $this->getXoopsFormSelectCombo($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_TEXTUUID:
                        $ret .= $this->getXoopsFormTextUuid($language, $fieldName, $required);
                        break;
                    case Constants::FIELD_ELE_TEXTIP:
                        $ret .= $this->getXoopsFormTextIp($language, $fieldName, $required);
                        break;
                    default:
                        // If we use tag module
                        if (!$tagDone && 1 == $table->getVar('table_tag')) {
                            $ret .= $this->getXoopsFormTag($fieldId, $required);
                            $tagDone = true;
                        }
                        // If we want to hide XoopsFormHidden() or field id
                        if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                            $ret .= $this->getXoopsFormHidden($fieldName);
                        }
                        break;
                }

                $fieldElements    = $this->helper->getHandler('Fieldelements')->get($fieldElement);
                $fieldElementTid  = $fieldElements->getVar('fieldelement_tid');
                if ((int)$fieldElementTid > 0 ) {
                    if ((1 == $fieldParent) || 1 == $table->getVar('table_category')) {
                        $fieldElementMid  = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc    = mb_substr($fieldElementName, \mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                        $topicTableName   = \str_replace(': ', '', $fieldNameDesc);
                        $fieldsTopics     = $this->tf->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (\array_keys($fieldsTopics) as $g) {
                            $fieldNameTopic = $fieldsTopics[$g]->getVar('field_name');
                            if ((0 == $g) && (1 == $table->getVar('table_autoincrement'))) {
                                $fieldIdTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$g]->getVar('field_parent')) {
                                $fieldPidTopic = $fieldNameTopic;
                            }
                            if (1 == $fieldsTopics[$g]->getVar('field_main')) {
                                $fieldMainTopic = $fieldNameTopic;
                            }
                        }
                        $ret .= $this->getXoopsFormTopic($language, $topicTableName, $fieldIdTopic, $fieldPidTopic, $fieldMainTopic);
                    } else {
                        $ret .= $this->getXoopsFormTable($language, $fieldName, $fieldElement, $required);
                    }
                }
            }
        }
        unset($fieldElementId);

        return $ret;
    }
}
