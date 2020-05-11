<?php

namespace XoopsModules\Modulebuilder\Files\Classes;

use XoopsModules\Modulebuilder;

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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class ClassFormElements.
 */
class ClassFormElements extends Modulebuilder\Files\CreateAbstractClass
{
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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        if ('' != $fieldDefault) {
            $ret      = $pc->getPhpCodeCommentLine('Form Text', $ccFieldName, "\t\t");
            $ret      .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", "\t\t");
            $formText = $cxc->getClassXoopsFormText('', $language, $fieldName, 20, 150, $ccFieldName, true);
            $ret      .= $cxc->getClassAddElement('form', $formText . $required);
        } else {
            $ret      = $pc->getPhpCodeCommentLine('Form Text', $ccFieldName, "\t\t");
            $formText = $cxc->getClassXoopsFormText('', $language, $fieldName, 50, 255, "this->getVar('{$fieldName}')", true);
            $ret      .= $cxc->getClassAddElement('form', $formText . $required);
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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $ret          = $pc->getPhpCodeCommentLine('Form Editor', 'TextArea ' . $ccFieldName, "\t\t");
        $formTextArea = $cxc->getClassXoopsFormTextArea('', $language, $fieldName, 4, 47, true);
        $ret          .= $cxc->getClassAddElement('form', $formTextArea . $required);

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
        $tf          = Modulebuilder\Files\CreateFile::getInstance();
        $pc          = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc          = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc         = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $rpFieldName = $tf->getRightString($fieldName);
        $ccFieldName = $tf->getCamelCase($fieldName, false, true);
        $ret         = $pc->getPhpCodeCommentLine('Form Editor', 'DhtmlTextArea ' . $ccFieldName, "\t\t");
        $ret         .= $pc->getPhpCodeArray('editorConfigs', null, false, "\t\t");
        $getConfig   = $xc->getXcGetConfig('editor_' . $rpFieldName);
        $configs     = [
            'name'   => "'{$fieldName}'",
            'value'  => "\$this->getVar('{$fieldName}', 'e')",
            'rows'   => 5,
            'cols'   => 40,
            'width'  => "'100%'",
            'height' => "'400px'",
            'editor' => $getConfig,
        ];
        foreach ($configs as $c => $d) {
            $ret .= $xc->getXcEqualsOperator("\$editorConfigs['{$c}']", $d, null, "\t\t");
        }
        $formEditor = $cxc->getClassXoopsFormEditor('', $language, $fieldName, 'editorConfigs', true);
        $ret        .= $cxc->getClassAddElement('form', $formEditor . $required);

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
        $tf               = Modulebuilder\Files\CreateFile::getInstance();
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc              = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $stuTableSoleName = mb_strtoupper($tableSoleName);
        $ucfFieldName     = $tf->getCamelCase($fieldName, true);
        $ccFieldName      = $tf->getCamelCase($fieldName, false, true);
        $t                = "\t\t";
        if (in_array(5, $fieldElementId) > 1) {
            $ret     = $pc->getPhpCodeCommentLine('Form Check Box', 'List Options ' . $ccFieldName, $t);
            $ret     .= $xc->getXcEqualsOperator('$checkOption', '$this->getOptions()');
            $foreach = $cxc->getClassXoopsFormCheckBox('check' . $ucfFieldName, '<hr />', $tableSoleName . '_option', '$checkOption', false, $t . "\t");
            $foreach .= $cxc->getClassSetDescription('check' . $ucfFieldName, "{$language}{$stuTableSoleName}_OPTIONS_DESC", $t . "\t");
            $foreach .= $cxc->getClassAddOption('check' . $ucfFieldName, "\$option, {$language}{$stuTableSoleName}_ . strtoupper(\$option)", $t . "\t");
            $ret     .= $pc->getPhpCodeForeach("{$tableSoleName}All", false, false, 'option', $foreach, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $cxc->getClassAddElement('form', $intElem, $t);
        } else {
            $ret     = $pc->getPhpCodeCommentLine('Form Check Box', $ccFieldName, $t);
            $ret     .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
            $ret     .= $cxc->getClassXoopsFormCheckBox('check' . $ucfFieldName, (string)$language, $fieldName, "\${$ccFieldName}", false, $t);
            $option  = "1, {$language}";
            $ret     .= $cxc->getClassAddOption('check' . $ucfFieldName, $option, $t);
            $intElem = "\$check{$ucfFieldName}{$required}";
            $ret     .= $cxc->getClassAddElement('form', $intElem, $t);
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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $ret          = $pc->getPhpCodeCommentLine('Form Hidden', $ccFieldName, "\t\t");
        $formHidden   = $cxc->getClassXoopsFormHidden('', $fieldName, $fieldName, true, true);
        $ret          .= $cxc->getClassAddElement('form', $formHidden);

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
        $tf              = Modulebuilder\Files\CreateFile::getInstance();
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc              = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc             = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $languageShort   = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form Frameworks Images', 'Files ' . $ccFieldName, $t);
        $ret             .= $pc->getPhpCodeCommentLine('Form Frameworks Images', $ccFieldName .': Select Uploaded Image', $t);
        $ret             .= $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $ret             .= $xc->getXcEqualsOperator('$imageDirectory', "'/Frameworks/moduleclasses/icons/32'", null, $t);
        $ret             .= $cxc->getClassXoopsFormElementTray('imageTray', $language, '<br>', $t);
        $sprintf         = $pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$imageDirectory}/"');
        $ret             .= $cxc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $xc->getXcXoopsListImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $cxc->getClassAddOption('imageSelect', '"{$image1}", $image1', $t . "\t");
        $ret             .= $pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"imglabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\" . \$imageDirectory . \"\\\", \\\"\\\", \\\"\" . XOOPS_URL . \"\\\")'\"";
        $ret             .= $cxc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $cxc->getClassAddElement('imageTray', '$imageSelect, false', $t);

        $paramLabel      = "\"<br><img src='\" . XOOPS_URL . \"/\" . \$imageDirectory . \"/\" . \${$ccFieldName} . \"' id='imglabel_{$fieldName}' alt='' style='max-width:100px' />\"";
        $xoopsFormLabel  = $cxc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');

        $ret             .= $cxc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $pc->getPhpCodeCommentLine('Form Frameworks Images', $ccFieldName .': Upload new image', $t);
        $ret             .= $cxc->getClassXoopsFormElementTray('fileSelectTray', "''", '<br>', $t);
        $getConfig       = $xc->getXcGetConfig('maxsize_image');
        $xoopsFormFile   = $cxc->getClassXoopsFormFile('', $languageShort . 'FORM_UPLOAD_NEW', $fieldName, $getConfig, true, '');
        $ret             .= $cxc->getClassAddElement('fileSelectTray', $xoopsFormFile, $t);
        $xoopsFormLabel1 = $cxc->getClassXoopsFormLabel('', "''", null, true, $t);
        $ret             .= $cxc->getClassAddElement('fileSelectTray', $xoopsFormLabel1, $t);
        $ret             .= $cxc->getClassAddElement('imageTray', '$fileSelectTray', $t);
        $ret             .= $cxc->getClassAddElement('form', "\$imageTray{$required}", $t);

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
        $tf              = Modulebuilder\Files\CreateFile::getInstance();
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc              = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc             = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $languageShort   = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form File', $ccFieldName, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form File {$ccFieldName}:", 'Select Uploaded File ', $t);
        $ret             .= $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $ret             .= $xc->getXcEqualsOperator('$fileDirectory', "'/uploads/{$moduleDirname}/files/{$tableName}'", null, $t);
        $ret             .= $cxc->getClassXoopsFormElementTray('fileTray', $language, '<br>', $t);
        $sprintf         = $pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$fileDirectory}/"');
        $ret             .= $cxc->getClassXoopsFormSelect('fileSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $xc->getXcXoopsListImgListArray('fileArray', 'XOOPS_ROOT_PATH . $fileDirectory', $t);
        $contForeach     = $cxc->getClassAddOption('fileSelect', '"{$file1}", $file1', $t . "\t");
        $ret             .= $pc->getPhpCodeForeach('fileArray', false, false, 'file1', $contForeach, $t);
        //TODO: make preview for images or show "no preview possible"
        //$setExtraParam   = "\"onchange='showImgSelected(\\\"filelabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\".\$fileDirectory.\"\\\", \\\"\\\", \\\"\".XOOPS_URL.\"\\\")'\"";
        //$ret             .= $cc->getClassSetExtra('fileSelect', $setExtraParam, $t);
        $ret             .= $cxc->getClassAddElement('fileTray', '$fileSelect, false', $t);
        //$paramLabel      = "\"<br><img src='\".XOOPS_URL.\"/\".\$fileDirectory.\"/\".\${$ccFieldName}.\"' name='filelabel_{$fieldName}' id='filelabel_{$fieldName}' alt='' style='max-width:100px' />\"";
        //$xoopsFormLabel  = $cc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        //$ret             .= $cc->getClassAddElement('fileTray', $xoopsFormLabel, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form File {$ccFieldName}:", 'Upload new file', $t);
        $getConfigSize   = $xc->getXcGetConfig('maxsize_file');
        $contIf          = $xc->getXcEqualsOperator('$maxsize', $getConfigSize,'', "\t\t\t");
        $xoopsFormFile   = $cxc->getClassXoopsFormFile('fileTray', "'<br>' . " . $languageShort . 'FORM_UPLOAD_NEW', $fieldName, '$maxsize', true, '');
        $contIf          .= $cxc->getClassAddElement('fileTray', $xoopsFormFile, $t . "\t");
        $configText      = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $contIf          .= $cxc->getClassAddElement('fileTray', $labelInfo1, $t . "\t");
        $formHidden      = $cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, $t, true);
        $contElse        = $cxc->getClassAddElement('fileTray', $formHidden, $t . "\t");
        $ret             .= $pc->getPhpCodeConditions('$permissionUpload', null, null, $contIf, $contElse, "\t\t");
        $ret             .= $cxc->getClassAddElement('form', "\$fileTray, {$required}", $t);

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
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc            = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc           = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName    = $tf->getCamelCase($fieldName, false, true);
        $languageShort = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine('Form Url', 'Text File ' . $ccFieldName, $t);
        $ret           .= $cxc->getClassXoopsFormElementTray('formUrlFile', $language, '<br><br>', $t);
        $ret           .= $pc->getPhpCodeTernaryOperator('formUrl', '$this->isNew()', "'{$fieldDefault}'", "\$this->getVar('{$fieldName}')", $t);
        $ret           .= $cxc->getClassXoopsFormText('formText', $language . '_UPLOADS', $fieldName, 75, 255, 'formUrl', false, $t);
        $ret           .= $cxc->getClassAddElement('formUrlFile', '$formText' . $required, $t);
        $getConfig     = $xc->getXcGetConfig('maxsize_file');
        $xoopsFormFile = $cxc->getClassXoopsFormFile('', $languageShort . 'FORM_UPLOAD', $fieldName, $getConfig, true, '');
        $ret           .= $cxc->getClassAddElement('formUrlFile', $xoopsFormFile . $required, $t);
        $ret           .= $cxc->getClassAddElement('form', '$formUrlFile', $t);

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
        $tf              = Modulebuilder\Files\CreateFile::getInstance();
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc              = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc             = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfFieldName    = $tf->getCamelCase($fieldName, true);
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $languageShort   = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form Image', $ccFieldName, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form Image {$ccFieldName}:", 'Select Uploaded Image ', $t);
        $ret             .= $xc->getXcEqualsOperator('$get' . $ucfFieldName, "\$this->getVar('{$fieldName}')", null, $t);
        $ret             .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$get' . $ucfFieldName, '$get' . $ucfFieldName, "'blank.gif'", $t);
        $ret             .= $xc->getXcEqualsOperator('$imageDirectory', "'/uploads/{$moduleDirname}/images/{$tableName}'", null, $t);
        $ret             .= $cxc->getClassXoopsFormElementTray('imageTray', $language, '<br>', $t);
        $sprintf         = $pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$imageDirectory}/"');
        $ret             .= $cxc->getClassXoopsFormSelect('imageSelect', $sprintf, $fieldName, $ccFieldName, 5, 'false', false, $t);
        $ret             .= $xc->getXcXoopsListImgListArray('imageArray', 'XOOPS_ROOT_PATH . $imageDirectory', $t);
        $contForeach     = $cxc->getClassAddOption('imageSelect', '"{$image1}", $image1', $t . "\t");
        $ret             .= $pc->getPhpCodeForeach('imageArray', false, false, 'image1', $contForeach, $t);
        $setExtraParam   = "\"onchange='showImgSelected(\\\"imglabel_{$fieldName}\\\", \\\"{$fieldName}\\\", \\\"\" . \$imageDirectory . \"\\\", \\\"\\\", \\\"\" . XOOPS_URL . \"\\\")'\"";
        $ret             .= $cxc->getClassSetExtra('imageSelect', $setExtraParam, $t);
        $ret             .= $cxc->getClassAddElement('imageTray', '$imageSelect, false', $t);
        $paramLabel      = "\"<br><img src='\" . XOOPS_URL . \"/\" . \$imageDirectory . \"/\" . \${$ccFieldName} . \"' id='imglabel_{$fieldName}' alt='' style='max-width:100px' />\"";
        $xoopsFormLabel  = $cxc->getClassXoopsFormLabel('', "''", $paramLabel, true, '');
        $ret             .= $cxc->getClassAddElement('imageTray', $xoopsFormLabel, $t);
        $ret             .= $pc->getPhpCodeCommentLine("Form Image {$ccFieldName}:", 'Upload new image', $t);
        $getConfigSize   = $xc->getXcGetConfig('maxsize_image');
        $contIf          = $xc->getXcEqualsOperator('$maxsize', $getConfigSize,'', "\t\t\t");
        $xoopsFormFile   = $cxc->getClassXoopsFormFile('imageTray', "'<br>' . " . $languageShort . 'FORM_UPLOAD_NEW', $fieldName, '$maxsize', true, '');
        $contIf          .= $cxc->getClassAddElement('imageTray', $xoopsFormFile, $t . "\t");
        $configText      = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $contIf          .= $cxc->getClassAddElement('imageTray', $labelInfo1, $t . "\t");
        $getConfig       = $xc->getXcGetConfig('maxwidth_image');
        $labelInfo2      = $cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_IMG_WIDTH', $getConfig . " . ' px'", true, '');
        $contIf          .= $cxc->getClassAddElement('imageTray', $labelInfo2, $t . "\t");
        $getConfig       = $xc->getXcGetConfig('maxheight_image');
        $labelInfo3      = $cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_IMG_HEIGHT', $getConfig . " . ' px'", true, '');
        $contIf          .= $cxc->getClassAddElement('imageTray', $labelInfo3, $t . "\t");
        $formHidden      = $cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, $t, true);
        $contElse        = $cxc->getClassAddElement('imageTray', $formHidden, $t . "\t");
        $ret             .= $pc->getPhpCodeConditions('$permissionUpload', null, null, $contIf, $contElse, "\t\t");
        $ret             .= $cxc->getClassAddElement('form', "\$imageTray {$required}", $t);

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
        $tf             = Modulebuilder\Files\CreateFile::getInstance();
        $pc             = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc             = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc            = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName    = $tf->getCamelCase($fieldName, false, true);
        $languageShort  = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t              = "\t\t\t";
        $ret            = $pc->getPhpCodeCommentLine('Form File', 'Upload ' . $ccFieldName, "\t\t");
        $ret            .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', "''", "\$this->getVar('{$fieldName}')", "\t\t");
        $uForm          = $cxc->getClassXoopsFormElementTray('fileUploadTray', $language, '<br>', $t);
        $uForm          .= $xc->getXcEqualsOperator('$fileDirectory', "'/uploads/{$moduleDirname}/files/{$tableName}'", null, $t);
        $sprintf        = $pc->getPhpCodeSprintf($language . '_UPLOADS', '".{$fileDirectory}/"');
        $xoopsFormLabel = $cxc->getClassXoopsFormLabel('', $sprintf, $ccFieldName, true, "\t\t", true);
        $condIf         = $cxc->getClassAddElement('fileUploadTray', $xoopsFormLabel, $t . "\t");
        $uForm          .= $pc->getPhpCodeConditions('!$this->isNew()', null, null, $condIf, false, "\t\t\t");
        $getConfig      = $xc->getXcGetConfig('maxsize_file');
        $uForm          .= $xc->getXcEqualsOperator('$maxsize', $getConfig,'', "\t\t\t");
        $xoopsFormFile  = $cxc->getClassXoopsFormFile('', "''", $fieldName, '$maxsize', true, '');
        $uForm          .= $cxc->getClassAddElement('fileUploadTray', $xoopsFormFile, $t);
        $configText     = "(\$maxsize / 1048576) . ' '  . " . $languageShort . 'FORM_UPLOAD_SIZE_MB';
        $labelInfo1      = $cxc->getClassXoopsFormLabel('',  $languageShort . 'FORM_UPLOAD_SIZE', $configText, true, '');
        $uForm          .= $cxc->getClassAddElement('fileUploadTray', $labelInfo1, $t );
        $uForm          .= $cxc->getClassAddElement('form', "\$fileUploadTray, {$required}", $t);
        $formHidden     = $cxc->getClassXoopsFormHidden('', $fieldName, $ccFieldName, true, true, "\t\t", true);
        $contElse       = $cxc->getClassAddElement('form', $formHidden, $t);

        $ret           .= $pc->getPhpCodeConditions('$permissionUpload', null, null, $uForm, $contElse, "\t\t");

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
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc            = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc           = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName   = $tf->getCamelCase($fieldName, false, true);
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine('Form Color', 'Picker ' . $ccFieldName, $t);
        $getVar        = $xc->getXcGetVar('', 'this', $fieldName, true);
        $xoopsFormFile = $cxc->getClassXoopsFormColorPicker('', $language, $fieldName, $getVar, true, '');
        $ret           .= $cxc->getClassAddElement('form', $xoopsFormFile. $required, $t);

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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc           = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine($ucfTableName, 'Handler', $t);
        $ret          .= $xc->getXcHandlerLine($tableName, $t);
        $ret          .= $pc->getPhpCodeCommentLine('Form', 'Select ' . $ccFieldName, $t);
        $ret          .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "'Empty'", $t);
        $ret          .= $cxc->getClassAddOptionArray($ccFieldName . 'Select', "\${$tableName}Handler->getList()", $t);
        $ret          .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf              = Modulebuilder\Files\CreateFile::getInstance();
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc             = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName     = $tf->getCamelCase($fieldName, false, true);
        $t               = "\t\t";
        $ret             = $pc->getPhpCodeCommentLine('Form Select', 'User ' . $ccFieldName, $t);
        $xoopsSelectUser = $cxc->getClassXoopsFormSelectUser('', $language, $fieldName, 'false', $fieldName, true, $t);
        $ret             .= $cxc->getClassAddElement('form', $xoopsSelectUser . $required, $t);

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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine('Form Radio', 'Yes/No ' . $ccFieldName, $t);
        $ret          .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsRadioYN = $cxc->getClassXoopsFormRadioYN('', $language, $fieldName, $ccFieldName, true, $t);
        $ret          .= $cxc->getClassAddElement('form', $xoopsRadioYN . $required, $t);

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
        $tf                  = Modulebuilder\Files\CreateFile::getInstance();
        $pc                  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc                 = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $t                   = "\t\t";
        $ccFieldName         = $tf->getCamelCase($fieldName, false, true);
        $ret                 = $pc->getPhpCodeCommentLine('Form Text', 'Date Select ' . $ccFieldName, $t);
        $ret                 .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $cxc->getClassXoopsFormTextDateSelect('', $language, $fieldName, $fieldName, $ccFieldName, true, $t);
        $ret                 .= $cxc->getClassAddElement('form', $xoopsTextDateSelect . $required, $t);

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
        $tf                  = Modulebuilder\Files\CreateFile::getInstance();
        $pc                  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc                 = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $t                   = "\t\t";
        $ccFieldName         = $tf->getCamelCase($fieldName, false, true);
        $ret                 = $pc->getPhpCodeCommentLine('Form Text', 'Date Select ' . $ccFieldName, $t);
        $ret                 .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', 0, "\$this->getVar('{$fieldName}')", $t);
        $xoopsTextDateSelect = $cxc->getClassXoopsFormDateTime('', $language, $fieldName, $fieldName, $ccFieldName, true, $t);
        $ret                 .= $cxc->getClassAddElement('form', $xoopsTextDateSelect . $required, $t);

        return $ret;
    }

    /**
     * @private function getXoopsFormSelectStatus
     *
     * @param $language
     * @param $moduleDirname
     * @param $fieldName
     * @param string $required
     *
     * @return string
     */
    private function getXoopsFormSelectStatus($language, $moduleDirname, $fieldName, $required = 'false')
    {
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $languageShort = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine('Form Select', 'Status ' . $ccFieldName, $t);
        $ret          .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "Constants::STATUS_NONE, {$languageShort}STATUS_NONE", $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "Constants::STATUS_OFFLINE, {$languageShort}STATUS_OFFLINE", $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "Constants::STATUS_SUBMITTED, {$languageShort}STATUS_SUBMITTED", $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "Constants::STATUS_APPROVED, {$languageShort}STATUS_APPROVED", $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "Constants::STATUS_BROKEN, {$languageShort}STATUS_BROKEN", $t);
        $ret          .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc           = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $t             = "\t\t";
        $ccFieldName   = $tf->getCamelCase($fieldName, false, true);
        $ret           = $pc->getPhpCodeCommentLine('Form Text', 'Enter Password ' . $ccFieldName, $t);
        $xoopsPassword = $cxc->getClassXoopsFormPassword('', $language, $fieldName, 10, 32, true, $t);
        $ret           .= $cxc->getClassAddElement('form', $xoopsPassword . $required, $t);

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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $xc           = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine('Form Select', 'Country ' . $ccFieldName, $t);
        $ret          .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "'', _NONE", $t);
        $ret          .= $xc->getXcXoopsListCountryList('countryArray', $t);
        $ret          .= $cxc->getClassAddOptionArray($ccFieldName . 'Select', '$countryArray', $t);
        $ret          .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf           = Modulebuilder\Files\CreateFile::getInstance();
        $pc           = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc          = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $xc           = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ccFieldName  = $tf->getCamelCase($fieldName, false, true);
        $t            = "\t\t";
        $ret          = $pc->getPhpCodeCommentLine('Form Select', 'Lang ' . $ccFieldName, $t);
        $ret          .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret          .= $cxc->getClassAddOption($ccFieldName . 'Select', "'', _NONE", $t);
        $ret          .= $xc->getXcXoopsListLangList('langArray', $t);
        $ret          .= $cxc->getClassAddOptionArray($ccFieldName . 'Select', '$langArray', $t);
        $ret          .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cxc           = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ccFieldName   = $tf->getCamelCase($fieldName, false, true);
        $languageShort = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine('Form Radio', $ccFieldName, $t);
        $ret           .= $pc->getPhpCodeTernaryOperator($ccFieldName, '$this->isNew()', '0', "\$this->getVar('{$fieldName}')", $t);
        $ret           .= $cxc->getClassXoopsFormRadio($ccFieldName . 'Select', $language, $fieldName, "{$ccFieldName}", false, $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'0', _NONE", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'1', {$languageShort}LIST_1", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'2', {$languageShort}LIST_2", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'3', {$languageShort}LIST_3", $t);
        $ret           .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc            = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc           = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTableName  = ucfirst($tableName);
        $ccFieldName   = $tf->getCamelCase($fieldName, false, true);
        $languageShort = substr($language, 0, 4) . mb_strtoupper($moduleDirname) . '_';
        $t             = "\t\t";
        $ret           = $pc->getPhpCodeCommentLine($ucfTableName, 'Handler', $t);
        $ret           .= $xc->getXcHandlerLine($tableName, $t);
        $ret           .= $pc->getPhpCodeCommentLine('Form', 'Select ' . $ccFieldName, $t);
        $ret           .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", '5', '', false, $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'0', _NONE", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'1', {$languageShort}LIST_1", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'2', {$languageShort}LIST_2", $t);
        $ret           .= $cxc->getClassAddOption($ccFieldName . 'Select', "'3', {$languageShort}LIST_3", $t);
        $ret           .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tc  = Modulebuilder\Helper::getInstance();
        $tf  = Modulebuilder\Files\CreateFile::getInstance();
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();

        $t   = "\t\t";
        $ret = '';
        $fElement           = $tc->getHandler('Fieldelements')->get($fieldElement);
        $rpFieldelementName = mb_strtolower(str_replace('Table : ', '', $fElement->getVar('fieldelement_name')));
        $ret                .= $pc->getPhpCodeCommentLine('Form Table', $rpFieldelementName, $t);
        $ccFieldName        = $tf->getCamelCase($fieldName, false, true);
        $ret                .= $xc->getXcHandlerLine($rpFieldelementName, $t);
        $ret                .= $cxc->getClassXoopsFormSelect($ccFieldName . 'Select', $language, $fieldName, "this->getVar('{$fieldName}')", null, '', false, $t);
        $ret                .= $cxc->getClassAddOptionArray($ccFieldName . 'Select', "\${$rpFieldelementName}Handler->getList()", $t);
        $ret                .= $cxc->getClassAddElement('form', "\${$ccFieldName}Select{$required}", $t);

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
        $tf                = Modulebuilder\Files\CreateFile::getInstance();
        $pc                = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc                = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc               = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $ucfTopicTableName = ucfirst($topicTableName);
        $stlTopicTableName = mb_strtolower($topicTableName);
        $ccFieldPid        = $tf->getCamelCase($fieldPid, false, true);
        $t                 = "\t\t";
        $ret               = $pc->getPhpCodeCommentLine('Form Table', $ucfTopicTableName, $t);
        $ret               .= $xc->getXcHandlerLine($stlTopicTableName, $t);
        $ret               .= $xc->getXcCriteriaCompo('cr' . $ucfTopicTableName, $t);
        $ret               .= $xc->getXcHandlerCountClear($stlTopicTableName . 'Count', $stlTopicTableName, '$cr' . $ucfTopicTableName, $t);
        $contIf            = $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', $t . "\t");
        $contIf            .= $xc->getXcHandlerAllClear($stlTopicTableName . 'All', $stlTopicTableName, '$cr' . $ucfTopicTableName, $t . "\t");
        $contIf            .= $cxc->getClassXoopsObjectTree($stlTopicTableName . 'Tree', $stlTopicTableName . 'All', $fieldId, $fieldPid, $t . "\t");
        $contIf            .= $cxc->getClassXoopsMakeSelBox($ccFieldPid, $stlTopicTableName . 'Tree', $fieldPid, $fieldMain, '--', $fieldPid, $t . "\t");
        $formLabel         = $cxc->getClassXoopsFormLabel('', $language, "\${$ccFieldPid}", true, '');
        $contIf            .= $cxc->getClassAddElement('form', $formLabel, $t . "\t");
        $ret               .= $pc->getPhpCodeConditions("\${$stlTopicTableName}Count", null, null, $contIf, false, $t);
        $ret               .= $pc->getPhpCodeUnset('cr' . $ucfTopicTableName, $t);

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
        $pc        = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc        = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $cxc       = Modulebuilder\Files\Classes\ClassXoopsCode::getInstance();
        $t         = "\t\t";
        $ret       = $pc->getPhpCodeCommentLine('Use tag', 'module', $t);
        $isDir     = $pc->getPhpCodeIsDir("XOOPS_ROOT_PATH . '/modules/tag'");
        $ret       .= $pc->getPhpCodeTernaryOperator('dirTag', $isDir, 'true', 'false', $t);
        $paramIf   = '(' . $xc->getXcGetConfig('usetag') . ' == 1)';
        $condIf    = $pc->getPhpCodeTernaryOperator('tagId', '$this->isNew()', '0', "\$this->getVar('{$fieldId}')", $t . "\t");
        $condIf    .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'modules/tag/include/formtag', true, false, $type = 'include', $t . "\t");
        $paramElem = $cxc->getClassXoopsFormTag('', 'tag', 60, 255, 'tagId', 0, true, '');
        $condIf    .= $cxc->getClassAddElement('form', $paramElem . $required, $t . "\t");
        $ret       .= $pc->getPhpCodeConditions($paramIf, ' && ', '$dirTag', $condIf, false, $t);

        return $ret;
    }

    /**
     * @public function renderElements
     * @param null
     * @return string
     */
    public function renderElements()
    {
        $tc            = Modulebuilder\Helper::getInstance();
        $tf            = Modulebuilder\Files\CreateFile::getInstance();
        $ttf           = Modulebuilder\Files\CreateTableFields::getInstance();
        $module        = $this->getModule();
        $table         = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $languageFunct = $tf->getLanguage($moduleDirname, 'AM');
        //$language_table = $languageFunct . strtoupper($tableName);
        $ret            = '';
        $fields         = $ttf->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order ASC, field_id');
        $fieldId        = '';
        $fieldIdTopic   = '';
        $fieldPidTopic  = '';
        $fieldMainTopic = '';
        $fieldElementId = [];
        $counter        = 0;
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldElement = $fields[$f]->getVar('field_element');
            $fieldParent  = $fields[$f]->getVar('field_parent');
            $fieldInForm  = $fields[$f]->getVar('field_inform');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            $rpFieldName = $tf->getRightString($fieldName);
            $language    = $languageFunct . mb_strtoupper($tableSoleName) . '_' . mb_strtoupper($rpFieldName);
            $required    = (1 == $fields[$f]->getVar('field_required')) ? ', true' : ' ';

            $fieldElements    = $tc->getHandler('Fieldelements')->get($fieldElement);
            $fieldElementId[] = $fieldElements->getVar('fieldelement_id');

            if (1 == $fieldInForm) {
                $counter++;
                // Switch elements
                switch ($fieldElement) {
                    case 1:
                        break;
                    case 2:
                        $ret .= $this->getXoopsFormText($language, $fieldName, $fieldDefault, $required);
                        break;
                    case 3:
                        $ret .= $this->getXoopsFormTextArea($language, $fieldName, $required);
                        break;
                    case 4:
                        $ret .= $this->getXoopsFormDhtmlTextArea($language, $fieldName, $required);
                        break;
                    case 5:
                        $ret .= $this->getXoopsFormCheckBox($language, $tableSoleName, $fieldName, $fieldElementId, $required);
                        break;
                    case 6:
                        $ret .= $this->getXoopsFormRadioYN($language, $fieldName, $required);
                        break;
                    case 7:
                        $ret .= $this->getXoopsFormSelectBox($language, $tableName, $fieldName, $required);
                        break;
                    case 8:
                        $ret .= $this->getXoopsFormSelectUser($language, $fieldName, $required);
                        break;
                    case 9:
                        $ret .= $this->getXoopsFormColorPicker($language, $fieldName, $required);
                        break;
                    case 10:
                        $ret .= $this->getXoopsFormImageList($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 11:
                        $ret .= $this->getXoopsFormSelectFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 12:
                        $ret .= $this->getXoopsFormUrlFile($language, $moduleDirname, $fieldName, $fieldDefault, $required);
                        break;
                    case 13:
                        $ret .= $this->getXoopsFormUploadImage($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 14:
                        $ret .= $this->getXoopsFormUploadFile($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    case 15:
                        $ret .= $this->getXoopsFormTextDateSelect($language, $fieldName, $required);
                        break;
                    case 16:
                        $ret .= $this->getXoopsFormSelectStatus($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 17:
                        $ret .= $this->getXoopsFormPassword($language,  $fieldName, $required);
                        break;
                    case 18:
                        $ret .= $this->getXoopsFormSelectCountry($language, $fieldName, $required);
                        break;
                    case 19:
                        $ret .= $this->getXoopsFormSelectLang($language, $fieldName, $required);
                        break;
                    case 20:
                        $ret .= $this->getXoopsFormRadio($language, $moduleDirname, $fieldName, $required);
                        break;
                    case 21:
                        $ret .= $this->getXoopsFormDateTime($language, $fieldName, $required);
                        break;
                    case 22:
                        $ret .= $this->getXoopsFormSelectCombo($language, $moduleDirname, $tableName, $fieldName, $required);
                        break;
                    default:
                        // If we use tag module
                        if (1 == $table->getVar('table_tag')) {
                            $ret .= $this->getXoopsFormTag($fieldId, $required);
                        }
                        // If we want to hide XoopsFormHidden() or field id
                        if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                            $ret .= $this->getXoopsFormHidden($fieldName);
                        }
                        break;
                }

                $fieldElements    = $tc->getHandler('Fieldelements')->get($fieldElement);
                $fieldElementTid  = $fieldElements->getVar('fieldelement_tid');
                if ((int)$fieldElementTid > 0 ) {
                    if ((1 == $fieldParent) || 1 == $table->getVar('table_category')) {
                        $fieldElementMid  = $fieldElements->getVar('fieldelement_mid');
                        $fieldElementName = $fieldElements->getVar('fieldelement_name');
                        $fieldNameDesc    = mb_substr($fieldElementName, mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                        $topicTableName   = str_replace(': ', '', $fieldNameDesc);
                        $fieldsTopics     = $ttf->getTableFields($fieldElementMid, $fieldElementTid);
                        foreach (array_keys($fieldsTopics) as $g) {
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
