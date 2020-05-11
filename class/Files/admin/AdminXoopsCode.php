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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
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
        $stuType = mb_strtoupper($type);
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
     * @param $type
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
        $aMenu = $t . '$adminObject->addInfoBoxLine(sprintf(';
        if ('' != $var) {
            $ret = $aMenu . " '<label>'.{$label}.'</label>', {$var}));\n";
        } else {
            $ret = $aMenu . " '<label>'.{$label}.'</label>'));\n";
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
     * @param int $countUploader
     * @return string
     */
    public function getAxcSetVarImageList($tableName, $fieldName, $t = '', $countUploader = 0)
    {
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ret         = $pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret         .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath   = "XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret         .= $xc->getXcMediaUploader('uploader', $xRootPath, 'mimetypes_image', 'maxsize_image', $t);
        $post        = $pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $ifelse      = $t . "\t//" . $this->getAxcSetPrefix('uploader', "{$fieldName}_") . ";\n";
        $ifelse      .= $t . "\t//{$fetchMedia};\n";
        $contElseInt = $xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contIf      = $xc->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $contIf      .= $xc->getXcRedirectHeader('javascript:history.go(-1)', '', '3', '$errors', true, $t . "\t\t");
        $ifelse      .= $pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $contElseExt = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret         .= $pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

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
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret          = $pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret          .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file         = $pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret          .= $xc->getXcEqualsOperator('$filename      ', $file, null, $t);
        $mimetype     = $pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['type']";
        $ret          .= $xc->getXcEqualsOperator('$imgMimetype   ', $mimetype, null, $t);
        $ret          .= $xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret          .= $xc->getXcEqualsOperator('$uploaderErrors', "''", null, $t);
        $xUploadImage = "{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $ret          .= $xc->getXcMediaUploader('uploader', $xUploadImage . " . '/{$tableName}/'", 'mimetypes_image', 'maxsize_image', $t);
        $post         = $pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia   = $this->getAxcFetchMedia('uploader', $post);
        $expr         = '/^.+\.([^.]+)$/sU';
        $ifelse       = $pc->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse       .= $t . "\t\$imgName = str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";
        $ifelse       .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse       .= $t . "\t{$fetchMedia};\n";
        $contElseInt  = $xc->getXcEqualsOperator('$savedFilename', '$uploader->getSavedFileName()', null, $t . "\t\t");
        $config       = $xc->getXcGetConfig('maxwidth_image');
        $contElseInt  .= $xc->getXcEqualsOperator('$maxwidth ', "(int){$config}", null, $t . "\t\t");
        $config       = $xc->getXcGetConfig('maxheight_image');
        $contElseInt  .= $xc->getXcEqualsOperator('$maxheight', "(int){$config}", null, $t . "\t\t");
        $resizer      = $pc->getPhpCodeCommentLine('Resize image', '', $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler               ', "new {$ucfModuleDirname}\Common\Resizer()", null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler->sourceFile   ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler->endFile      ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler->imageMimetype', '$imgMimetype', null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler->maxWidth     ', '$maxwidth', null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$imgHandler->maxHeight    ', '$maxheight', null, $t . "\t\t\t");
        $resizer      .= $xc->getXcEqualsOperator('$result                   ', '$imgHandler->resizeImage()', null, $t . "\t\t\t");
        $contElseInt  .= $pc->getPhpCodeConditions('$maxwidth > 0 && $maxheight > 0', '', '', $resizer, false, $t . "\t\t");
        $contElseInt  .= $xc->getXcSetVarObj($tableName, $fieldName, '$savedFilename', $t . "\t\t");
        $contIf       = $xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse       .= $pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $ifelseExt    = $xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contElseExt  = $pc->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contElseExt  .= $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret          .= $pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

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
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $this->getAxcSetVarImageFile($stuModuleDirname . '_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t, $countUploader, $fieldMain, 'mimetypes_file', 'maxsize_file');

        return $ret;
    }

    /**
     * @private function getAxcSetVarImageFile
     * @param        $dirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool $formatUrl
     * @param string $t
     * @param int $countUploader
     * @param string $fieldMain
     * @param string $mimetype
     * @param string $maxsize
     * @return string
     */
    private function getAxcSetVarImageFile($dirname, $tableName, $fieldName, $formatUrl = false, $t = '', $countUploader = 0, $fieldMain = '', $mimetype = 'mimetypes_image', $maxsize = 'maxsize_image')
    {
        $pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc     = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ret    = '';
        $files  = '';
        $contIf = '';

        if ($formatUrl) {
            $ret .= $xc->getXcSetVarObj($tableName, $fieldName, "formatUrl(\$_REQUEST['{$fieldName}'])", $t);
        }
        $ret         .= $pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret         .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $file        = $pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret         .= $xc->getXcEqualsOperator('$filename      ', $file, null, $t);
        $ret         .= $xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t);
        $ret         .= $xc->getXcMediaUploader('uploader', $dirname . " . '/{$tableName}{$files}/'", $mimetype, $maxsize, $t);
        $post        = $pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $expr        = '/^.+\.([^.]+)$/sU';
        $ifelse      = $pc->getPhpCodePregFunzions('extension', $expr, '', "\$filename", 'replace', false, $t . "\t");
        $ifelse      .= $t . "\t\$imgName = str_replace(' ', '', \$imgNameDef) . '.' . \$extension;\n";
        $ifelse      .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t") . ";\n";
        $ifelse      .= $t . "\t{$fetchMedia};\n";
        $contElseInt = $xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contIf      .= $xc->getXcEqualsOperator('$errors', '$uploader->getErrors()', null, $t . "\t\t");
        $ifelse      .= $pc->getPhpCodeConditions('!$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $ifelseExt   = $xc->getXcEqualsOperator('$uploaderErrors', '$uploader->getErrors()', null, $t . "\t\t");
        $contElseExt = $pc->getPhpCodeConditions("\$filename", ' > ', "''", $ifelseExt, false, $t . "\t");
        $contElseExt .= $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret         .= $pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

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
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $cf  = Modulebuilder\Files\CreateFile::getInstance();
        $ccFieldId = $cf->getCamelCase($fieldName, false, true);
        $ret       = $xc->getXcEqualsOperator("\${$ccFieldId}", "Request::getString('{$fieldName}', '')", '',$t);
        $contIf    = $xc->getXcSetVarObj($tableName, $fieldName, "password_hash(\${$ccFieldId}, PASSWORD_DEFAULT)", $t . "\t");
        $ret       .= $pc->getPhpCodeConditions("''", ' !== ', "\${$ccFieldId}",$contIf, false, $t);

        return $ret;
    }


    /**
     * @public function getAxcSetVarMisc
     * @param        $tableName
     * @param        $fieldName
     * @param $fieldType
     * @param string $t
     * @return string
     */
    public function getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, $t = '')
    {
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        switch ((int)$fieldType){
            case 2:
            case 3:
            case 4:
            case 5:
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getInt('{$fieldName}', 0)", $t);
                break;
            case 6:
            case 7:
            case 8:
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getFloat('{$fieldName}', 0)", $t);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                if ((int)$fieldElement == 4) {
                    $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getText('{$fieldName}', '')", $t);
                } else {
                    $ret = $xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}', '')", $t);
                }
                break;
            case 0:
            default:
                //TODO: should be finally
                $ret = $xc->getXcSetVarObj($tableName, $fieldName, "\$_POST['{$fieldName}']", $t);
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
