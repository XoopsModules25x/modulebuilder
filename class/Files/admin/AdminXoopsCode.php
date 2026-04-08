<?php declare(strict_types=1);

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
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
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
     * @var mixed
     */
    private $cf = null;

    /**
     * @public function constructor
     */
    public function __construct()
    {
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->cf = Modulebuilder\Files\CreateFile::getInstance();
    }

    /**
     * @static function getInstance

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
    public function getAdminTemplateMain($moduleDirname, $tableName, string $t = '')
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
    public function getAdminItemButton($language, $tableName, $stuTableSoleName, string $op = '?op=new', string $type = 'add', string $t = '')
    {
        $stuType = \mb_strtoupper($type);
        $aM      = $t . '$adminObject->addItemButton(';
        switch ($type) {
            case 'add':
                $ret = $aM . "{$language}ADD_{$stuTableSoleName}, '{$tableName}.php{$op}');\n";
            break;
            case 'samplebutton':
                $ret = $aM . "{$language}, '{$op}', 'add');\n";
                break;
            case 'default':
            default:
                $ret = $aM . "{$language}{$stuType}_{$stuTableSoleName}, '{$tableName}.php{$op}', '{$type}');\n";
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
    public function getAdminDisplayButton($type, string $t = '')
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
    public function getAdminDisplayNavigation($tableName, string $t = '')
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
    public function getAxcAddInfoBox($language, string $t = '')
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
    public function getAxcAddInfoBoxLine(string $label = '', string $var = '', string $t = '')
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
    public function getAxcAddConfigBoxLine($language, string $label = '', string $var = '', string $t = '')
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
    public function getAxcSetVarImageList(string $tableName, string $fieldName, string $t = '', int $countUploader = 0)
    {
        $ret         = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $ret         .= $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t);
        $xRootPath   = "\XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32'";
        $ret         .= $this->xc->getXcMediaUploader('uploader', $xRootPath, 'mimetypes_image', 'maxsize_image', $t);
        $post        = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $ifelse      = $t . "\t//" . $this->getAxcSetPrefix('uploader', "{$fieldName}_") . ";\n";
        $ifelse      .= $t . "\t//{$fetchMedia};\n";
        $contIf      = $this->xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t");
        $contElseInt = $this->xc->getXcEqualsOperator('$uploaderErrors', "'<br>' . \$uploader->getErrors()", '.', $t . "\t\t");
        $ifelse      .= $this->pc->getPhpCodeConditions('$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t");
        $contElseExt = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");

        $ret         .= $this->pc->getPhpCodeConditions($fetchMedia, '', '', $ifelse, $contElseExt, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarUploadImage
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param        $fieldMain
     * @param string $t
     * @param int $countUploader
     * @return string
     */
    public function getAxcSetVarUploadImage(string $moduleDirname, string $tableName, string $fieldName, $fieldMain, string $t = '', int $countUploader = 0)
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);

        $ret          = $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $condIf       = $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t . "\t");
        $file         = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret          .= $this->xc->getXcEqualsOperator('$filename', $file, null, $t);
        $mimetype     = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['type']";
        $condIf       .= $this->xc->getXcEqualsOperator('$imgMimetype   ', $mimetype, null, $t . "\t");
        $condIf       .= $this->xc->getXcEqualsOperator('$imgNameDef    ', "Request::getString('{$fieldMain}')", null, $t . "\t");
        $xUploadImage = "\\{$stuModuleDirname}_UPLOAD_IMAGE_PATH";
        $condIf       .= $this->xc->getXcMediaUploader('uploader', $xUploadImage . " . '/{$tableName}/'", 'mimetypes_image', 'maxsize_image', $t . "\t");
        $isset       = "isset(\$_POST['xoops_upload_file'][" . $countUploader . '])';
        $post         = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia   = $this->getAxcFetchMedia('uploader', $post);
        $ifelse       = $this->xc->getXcEqualsOperator('$extension', '\pathinfo($filename, \PATHINFO_EXTENSION)',null,  $t . "\t\t");
        $ifelse       .= $this->xc->getXcEqualsOperator('$imgName',  "\str_replace(' ', '', \$imgNameDef) . '.' . \$extension", null,$t . "\t\t");
        $ifelse       .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t\t") . ";\n";
        $contIf       = $this->xc->getXcEqualsOperator('$savedFilename', '$uploader->getSavedFileName()', null, $t . "\t\t\t");
        $config       = $this->xc->getXcGetConfig('maxwidth_image');
        $contIf       .= $this->xc->getXcEqualsOperator('$maxwidth ', "(int){$config}", null, $t . "\t\t\t");
        $config       = $this->xc->getXcGetConfig('maxheight_image');
        $contIf       .= $this->xc->getXcEqualsOperator('$maxheight', "(int){$config}", null, $t . "\t\t\t");
        $resizer      = $this->pc->getPhpCodeCommentLine('Resize image', '', $t . "\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler               ', "new Common\Resizer()", null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler->sourceFile   ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler->endFile      ', $xUploadImage . " . '/{$tableName}/' . \$savedFilename", null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler->imageMimetype', '$imgMimetype', null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler->maxWidth     ', '$maxwidth', null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$imgHandler->maxHeight    ', '$maxheight', null, $t . "\t\t\t\t");
        $resizer      .= $this->xc->getXcEqualsOperator('$result                   ', '$imgHandler->resizeImage()', null, $t . "\t\t\t\t");
        $contIf       .= $this->pc->getPhpCodeConditions('$maxwidth > 0 && $maxheight > 0', '', '', $resizer, false, $t . "\t\t\t");
        $contIf       .= $this->xc->getXcSetVarObj($tableName, $fieldName, '$savedFilename', $t . "\t\t\t");
        $contElseInt  = $this->xc->getXcEqualsOperator('$uploaderErrors', "'<br>' . \$uploader->getErrors()", '.', $t . "\t\t\t");
        $ifelse       .= $this->pc->getPhpCodeConditions('$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t\t");
        $ifelseExt    = $this->xc->getXcEqualsOperator('$uploaderErrors', "'<br>' . \$uploader->getErrors()", '.', $t . "\t\t\t");
        $contElseExt  = $this->pc->getPhpCodeConditions('$filename', ' > ', "''", $ifelseExt, false, $t . "\t\t");
        $contElseExt  .= $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t\t");
        $condIf       .= $this->pc->getPhpCodeConditions($isset . ' && ' . $fetchMedia, '', '', $ifelse, $contElseExt, $t . "\t");
        $condElse    = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");
        $ret         .= $this->pc->getPhpCodeConditions("'' !== (string)\$filename", '', '', $condIf, $condElse, $t);

        return $ret;
    }

    /**
     * @public function getAxcFileSetVar
     * @param        $moduleDirname
     * @param        $tableName
     * @param        $fieldName
     * @param bool $formatUrl
     * @param string $t
     * @param int $countUploader
     * @param string $fieldMain
     * @return string
     */
    public function getAxcSetVarUploadFile($moduleDirname, $tableName, $fieldName, bool $formatUrl = false, string $t = '', int $countUploader = 0, string $fieldMain = '')
    {
        $stuModuleDirname = \mb_strtoupper($moduleDirname);
        return $this->getAxcSetVarImageFile('\\' . $stuModuleDirname . '_UPLOAD_FILES_PATH', $tableName, $fieldName, $formatUrl, $t, $countUploader, $fieldMain, 'mimetypes_file', 'maxsize_file');
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
    private function getAxcSetVarImageFile($dirname, $tableName, $fieldName, bool $formatUrl = false, string $t = '', int $countUploader = 0, string $fieldMain = '', string $mimetype = 'mimetypes_image', string $maxsize = 'maxsize_image')
    {
        $ret    = '';
        $files  = '';

        $rpFieldName = '$' . $this->cf->getCamelCase($fieldName, false, true);

        if ($formatUrl) {
            $ret .= $this->xc->getXcEqualsOperator($rpFieldName, "formatURL((string)(\$_REQUEST['{$fieldName}'] ?? ''))", null, $t);
        }
        $ret         .= $this->pc->getPhpCodeCommentLine('Set Var', $fieldName, $t);
        $file        = $this->pc->getPhpCodeGlobalsVariables($fieldName, 'FILES') . "['name']";
        $ret         .= $this->xc->getXcEqualsOperator('$filename', $file, null, $t);
        $condIf      = $this->pc->getPhpCodeIncludeDir('\XOOPS_ROOT_PATH', 'class/uploader', true, false, '', $t . "\t");
        $condIf      .= $this->xc->getXcEqualsOperator('$imgNameDef', "Request::getString('{$fieldMain}')", null, $t . "\t");
        $condIf      .= $this->xc->getXcMediaUploader('uploader', $dirname . " . '/{$tableName}{$files}/'", $mimetype, $maxsize, $t . "\t");
        $isset       = "isset(\$_POST['xoops_upload_file'][" . $countUploader . '])';
        $post        = $this->pc->getPhpCodeGlobalsVariables('xoops_upload_file', 'POST') . '[' . $countUploader . ']';
        $fetchMedia  = $this->getAxcFetchMedia('uploader', $post);
        $ifelse      = $this->xc->getXcEqualsOperator('$extension', '\pathinfo($filename, \PATHINFO_EXTENSION)',null,  $t . "\t\t");
        $ifelse      .= $this->xc->getXcEqualsOperator('$imgName',  "\str_replace(' ', '', \$imgNameDef) . '.' . \$extension", null,$t . "\t\t");
        $ifelse      .= $this->getAxcSetPrefix('uploader', '$imgName', $t . "\t\t") . ";\n";
        $contIf      = $this->xc->getXcSetVarObj($tableName, $fieldName, '$uploader->getSavedFileName()', $t . "\t\t\t");
        $contElseInt = $this->xc->getXcEqualsOperator('$uploaderErrors', "'<br>' . \$uploader->getErrors()", '.', $t . "\t\t\t");
        $ifelse      .= $this->pc->getPhpCodeConditions('$uploader->upload()', '', '', $contIf, $contElseInt, $t . "\t\t");
        $ifelseExt   = $this->xc->getXcEqualsOperator('$uploaderErrors', "'<br>' . \$uploader->getErrors()", '.', $t . "\t\t\t");
        $contElseExt = $this->pc->getPhpCodeConditions('$filename', ' > ', "''", $ifelseExt, false, $t . "\t\t");
        if ($formatUrl) {
            $contElseExt .= $this->xc->getXcSetVarObj($tableName, $fieldName, $rpFieldName, $t . "\t\t");
        } else {
            $contElseExt .= $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t\t");
        }
        $condIf.= $this->pc->getPhpCodeConditions($isset . ' && ' . $fetchMedia, '', '', $ifelse, $contElseExt, $t . "\t");
        if ($formatUrl)  {
            $condElse= $this->xc->getXcSetVarObj($tableName, $fieldName, $rpFieldName, $t . "\t");
        } else {
            $condElse = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t . "\t");
        }
        $ret .= $this->pc->getPhpCodeConditions("'' !== (string)\$filename", '', '', $condIf, $condElse, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarPassword
     * @param        $tableName
     * @param        $fieldName
     * @param string $t
     * @return string
     */
    public function getAxcSetVarPassword($tableName, $fieldName, string $t = '')
    {
        $cf  = Modulebuilder\Files\CreateFile::getInstance();
        $ccFieldId = $cf->getCamelCase($fieldName, false, true);
        $ret       = $this->xc->getXcEqualsOperator("\${$ccFieldId}", "Request::getString('{$fieldName}')", '',$t);
        $contIf    = $this->xc->getXcSetVarObj($tableName, $fieldName, "password_hash(\${$ccFieldId}, PASSWORD_DEFAULT)", $t . "\t");
        $ret       .= $this->pc->getPhpCodeConditions("\${$ccFieldId}", ' !== ', "''", $contIf, false, $t);

        return $ret;
    }

    /**
     * @public function getAxcSetVarMisc
     * @param        $tableName
     * @param        $fieldName
     * @param $fieldType
     * @param $fieldElement
     * @param string $t
     * @return string
     */
    public function getAxcSetVarMisc($tableName, $fieldName, $fieldType, $fieldElement, string $t = '')
    {
        switch ((int)$fieldType){
            case 2:
            case 3:
            case 4:
            case 5:
                $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getInt('{$fieldName}')", $t);
                break;
            case 6:
            case 7:
            case 8:
                $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getFloat('{$fieldName}')", $t);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                if ((int)$fieldElement == 4) {
                    $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getText('{$fieldName}')", $t);
                } else {
                    $ret = $this->xc->getXcSetVarObj($tableName, $fieldName, "Request::getString('{$fieldName}')", $t);
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
    public function getAxcFetchMedia($anchor, $var, string $t = '')
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
    public function getAxcSetPrefix($anchor, $var, string $t = '')
    {
        return "{$t}\${$anchor}->setPrefix({$var})";
    }
}
