<?php

namespace XoopsModules\Modulebuilder\Files\Admin;

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
 * Class Axc.
 */
class AdminXoopsCode
{
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
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return AdminXoopsCode
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
     * @public function getAdminTemplateMain
     * @param        $moduleDirname
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getAdminTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n";
    }

    /**
     * @public function getAdminTemplateMain
     * @param        $language
     * @param        $tableName
     * @param        $stuTableSoleName
     * @param string $op
     * @param string $type
     *
     * @param string $t
     * @return string
     */
    public function getAdminItemButton($language, $tableName, $stuTableSoleName, $op = '?op=new', $type = 'add', $t = '')
    {
        $stuType = \mb_strtoupper($type);
        $aM      = $t . '$adminObject->addItemButton(';
        switch ($type) {
            case 'add';
                $ret = $aM . "{$language}ADD_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
                break;
            case 'samplebutton';
                $ret = $aM . "{$language}, '{$op}', 'add');\n";
                break;
            case 'default':
            default:
                $ret = $aM . "{$language}{$stuTableSoleName}_{$stuType}, '{$tableName}.php{$op}', '{$type}');\n";
                break;
        }

        return $ret;
    }

    /**
     * @public function getAdminAddNavigation
     *
     * @param        $type
     * @param string $t
     * @return string
     */
    public function getAdminDisplayButton($type, $t = '')
    {
        return "{$t}\$adminObject->displayButton('{$type}');\n";
    }

    /**
     * @public function getAdminAddNavigation
     *
     * @param        $tableName
     *
     * @param string $t
     * @return string
     */
    public function getAdminDisplayNavigation($tableName, $t = '')
    {
        return "{$t}\$adminObject->displayNavigation('{$tableName}.php')";
    }

    /**
     * @public function getAxcAddInfoBox
     * @param        $language
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddInfoBox($language, $t = '')
    {
        return "{$t}\$adminObject->addInfoBox({$language});\n";
    }

    /**
     * @public function getAxcAddInfoBoxLine
     * @param string $label
     * @param string $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddInfoBoxLine($label = '', $var = '', $t = '')
    {
        $aMenu = $t . '$adminObject->addInfoBoxLine(\sprintf(';
        if ('' != $var) {
            $ret = $aMenu . " '<label>' . {$label} . '</label>', {$var}));\n";
        } else {
            $ret = $aMenu . " '<label>' . {$label} . '</label>'));\n";
        }

        return $ret;
    }

    /**
     * @public function getAxcAddConfigBoxLine
     * @param        $language
     * @param string $label
     * @param string $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcAddConfigBoxLine($language, $label = '', $var = '', $t = '')
    {
        $aMenu = $t . '$adminObject->addConfigBoxLine(';
        if ('' != $var) {
            $ret = $aMenu . "{$language}, '{$label}', {$var});\n";
        } else {
            $ret = $aMenu . "{$language}, '{$label}');\n";
        }

        return $ret;
    }

    /**
     * @public function getAxcSetVarImageList
     * @param string $tableName
     * @param string $fieldName
     * @param string $t
     * @param int    $countUploader
     * @return string
     */
    public function getAxcSetVarImageList($tableName, $fieldName, $t = '', $countUploader = 0)
    {
        $ret         = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret         .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath   = "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret         .= $this->xc->getXcMediaUploader('uploader', $xRootPath, 'mimetypes_image', 'maxsize_image', $t);
        $post        = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $ifelse      = $t . "\t//" . $this->getAxcSetPrefix('uploader', "{$fieldName}_") . ";\n";
        $ifelse      .= $t . "\t//{$fetchMedia};\n";
        $contElseInt = $this->xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contIf      = $this->xc->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $contIf      .= $this->xc->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse      .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $contElseExt = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarUploadImage
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param        $fieldMain
     * @param string $t
     * @param int    $countUploader
     * @return string
     */
    public function getAxcSetVarUploadImage($moduleDirname, $tableName, $fieldName, $fieldMain, $t = '', $countUploader = 0)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ucfModuleDirname = \ucfirst($moduleDirname);
        $ret              = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret              .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file             = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret              .= $this->xc->getXcEqualsOperator('$filename      ', $file, null, $t);
        $mimetype         = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['type']";
        $ret              .= $this->xc->getXcEqualsOperator('$imgMimetype   ', $mimetype, null, $t);
        $ret              .= $this->xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret              .= $this->xc->getXcEqualsOperator('$uploaderErrors', "''", null, $t);
        $xUploadImage     = "{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $ret              .= $this->xc->getXcMediaUploader('uploader', $xUploadImage . " . '/{$tableName}/'", 'mimetypes_image', 'maxsize_image', $t);
        $post             = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia       = $this->getAxcFetchMedia('uploader', $post);
        $expr             = '/^.+\.([^.]+)$/sU';
        $ifelse           = $this->pc->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse           .= $t . "\t\$imgName = \str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";
        $ifelse           .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse           .= $t . "\t{$fetchMedia};\n";
        $contElseInt      = $this->xc->getXcEqualsOperator('$savedFilename', '$uploader->getSavedFileName()', null, $t . "\t\t");
        $config           = $this->xc->getXcGetConfig('maxwidth_image');
        $contElseInt      .= $this->xc->getXcEqualsOperator('$maxwidth ', "(int){$config}", null, $t . "\t\t");
        $config           = $this->xc->getXcGetConfig('maxheight_image');
        $contElseInt      .= $this->xc->getXcEqualsOperator('$maxheight', "(int){$config}", null, $t . "\t\t");
        $resizer          = $this->pc->getPhpCodeCommentLine('Resize image', '', $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler               ', "new {$ucfModuleDirname}\Common\Resizer()", null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler->sourceFile   ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler->endFile      ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler->imageMimetype', '$imgMimetype', null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler->maxWidth     ', '$maxwidth', null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$imgHandler->maxHeight    ', '$maxheight', null, $t . "\t\t\t");
        $resizer          .= $this->xc->getXcEqualsOperator('$result                   ', '$imgHandler->resizeImage()', null, $t . "\t\t\t");
        $contElseInt      .= $this->pc->getPhpCodeConditions('$maxwidth > 0 && $maxheight > 0', '', '', $resizer, false, $t . "\t\t");
        $contElseInt      .= $this->xc->getXcSetVarObj($tableName, $fieldName, '$savedFilename', $t . "\t\t");
        $contIf           = $this->xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse           .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $ifelseExt        = $this->xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contElseExt      = $this->pc->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contElseExt      .= $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcFileSetVar
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool   $formatUrl
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @return string
     */
    public function getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader = 0, $fieldMain = '')
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        $ret              = $this->getAxcSetVarImageFile($stuModuleDirname . '_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t, $countUploader, $fieldMain, 'mimetypes_file', 'maxsize_file');

        return $ret;
    }

    /**
     * @private function getAxcSetVarImageFile
     * @param        $dirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool   $formatUrl
     * @param string $t
     * @param int    $countUploader
     * @param string $fieldMain
     * @param string $mimetype
     * @param string $maxsize
     * @return string
     */
    private function getAxcSetVarImageFile($dirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader = 0, $fieldMain = '', $mimetype = 'mimetypes_image', $maxsize = 'maxsize_image')
    {
        $ret    = '';
        $files  = '';
        $contIf = '';

        if ($formatUrl) {
            $ret .= $this->xc->getXcSetVarObj($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])", $t);
        }
        $ret         .= $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret         .= $this->pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file        = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret         .= $this->xc->getXcEqualsOperator('$filename      ', $file, null, $t);
        $ret         .= $this->xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret         .= $this->xc->getXcMediaUploader('uploader', $dirname . " . '/{$tableName}{$files}/'", $mimetype, $maxsize, $t);
        $post        = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $expr        = '/^.+\.([^.]+)$/sU';
        $ifelse      = $this->pc->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse      .= $t . "\t\$imgName = \str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";
        $ifelse      .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse      .= $t . "\t{$fetchMedia};\n";
        $contElseInt = $this->xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contIf      .= $this->xc->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse      .= $this->pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $ifelseExt   = $this->xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contElseExt = $this->pc->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contElseExt .= $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarPassword
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getAxcSetVarPassword($tableName, $fieldName, $t = '')
    {
        $cf        = Modulebuilder\Files\CreateFile::getInstance();
        $ccFieldId = $cf->getCamelCase($fieldName, false, true);
        $ret       = $this->xc->getXcEqualsOperator("\${$ccFieldId}", "Request::getString('{$fieldName}', '')", '', $t);
        $contIf    = $this->xc->getXcSetVarObj($tableName, $fieldName, "password_hash(\${$ccFieldId}, PASSWORD_DEFAULT)", $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' !== ', "''", $contIf, false, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarMisc
     * @param        $tableName
     * @param        $fieldName
     * @param        $fieldType
     * @param        $fieldElement
     * @param string $t
     * @return string
     */
    public function getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, $t = '')
    {
        switch ((int)$fieldType) {
            case 2:
            case 3:
            case 4:
            case 5:
                $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getInt('{$fieldName}', 0)", $t);
                break;
            case 6:
            case 7:
            case 8:
                $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getFloat('{$fieldName}', 0)", $t);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                if ((int)$fieldElement == 4) {
                    $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getText('{$fieldName}', '')", $t);
                } else {
                    $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}', '')", $t);
                }
                break;
            case 0:
            default:
                //TODO: should be finally
                $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
                break;
        }

        return $ret;
    }

    /**
     * @public function getAxcFetchMedia
     *
     * @param        $anchor
     * @param        $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcFetchMedia($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->fetchMedia({$var})";
    }

    /**
     * @public function getAxcSetPrefix
     *
     * @param        $anchor
     * @param        $var
     *
     * @param string $t
     * @return string
     */
    public function getAxcSetPrefix($anchor, $var, $t = '')
    {
        return "{$t}\${$anchor}->setPrefix({$var})";
    }
}
