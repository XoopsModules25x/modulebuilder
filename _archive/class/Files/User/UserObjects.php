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
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserObjects.
 */
class UserObjects_old extends Files\CreateFile
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
     * @return UserObjects
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
     * @public function getUserHeaderTpl
     * @param string $moduleDirname
     * @param string $tableName
     * @return string
     */
    public function getUserHeaderTpl($moduleDirname, $tableName)
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';\n
EOT;

        return $ret;
    }

    /**
     * @public function getUserIndex
     * @param string $moduleDirname
     * @return string
     */
    public function getUserIndex($moduleDirname)
    {
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_index.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';\n
EOT;

        return $ret;
    }

    /**
     * @public function getUserFooter
     * @param null
     * @return string
     */
    public function getUserFooter()
    {
        $ret = <<<'EOT'
include  __DIR__ . '/footer.php';
EOT;

        return $ret;
    }

    /**
     * @public function getSimpleSetVar
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getSimpleSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);\n
EOT;

        return $ret;
    }

    /**
     * @public function getTextDateSelectSetVar
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getTextDateSelectSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', strtotime(\$_POST['{$fieldName}']));\n
EOT;

        return $ret;
    }

    /**
     * @public function getCheckBoxOrRadioYNSetVar
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', ((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0'));\n
EOT;

        return $ret;
    }

    /**
     * @public function getUrlFileSetVar
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldName
     * @return string
     */
    public function getUrlFileSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = <<<EOT
        // Set Var {$fieldName}
        \${$tableName}Obj->setVar('{$fieldName}', formatUrl(\$_REQUEST['{$fieldName}']));\n
		// Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = {$stuModuleDirname}_UPLOAD_PATH.'/Files/{$tableName}';
        \$uploader = new \XoopsMediaUploader(\$uploaddir, \$helper->getConfig('mimetypes_file'),
                                                         \$helper->getConfig('maxsize_file'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            \$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        }\n
EOT;

        return $ret;
    }

    /**
     * @public function getImageListSetVar
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getImageListSetVar($moduleDirname, $tableName, $fieldName)
    {
        $ret = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32';
        \$uploader = new \XoopsMediaUploader(\$uploaddir, \$helper->getConfig('mimetypes_image'),
                                                         \$helper->getConfig('maxsize_image'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            //\$uploader->setPrefix('{$fieldName}_');
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        } else {
            \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);
        }\n
EOT;

        return $ret;
    }

    /**
     * @public function getUploadImageSetVar
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @param        $fpmf
     * @return string
     */
    public function getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fpmf)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = {$stuModuleDirname}_UPLOAD_PATH.'/images/{$tableName}';
        \$uploader = new \XoopsMediaUploader(\$uploaddir, \$helper->getConfig('mimetypes_image'),
                                                         \$helper->getConfig('maxsize_image'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][0])) {
			\$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , \$_FILES['attachedfile']['name']);
            \$imgName = str_replace(' ', '', \$_POST['{$fpmf}']).'.'.\$extension;
			\$uploader->setPrefix(\$imgName);
            \$uploader->fetchMedia(\$_POST['xoops_upload_file'][0]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        } else {
            \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);
        }\n
EOT;

        return $ret;
    }

    /**
     * @public function getUploadFileSetVar
     * @param string $moduleDirname
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getUploadFileSetVar($moduleDirname, $tableName, $fieldName)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = <<<EOT
        // Set Var {$fieldName}
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        \$uploaddir = {$stuModuleDirname}_UPLOAD_PATH.'/Files/{$tableName}';
        \$uploader = new \XoopsMediaUploader(\$uploaddir, \$helper->getConfig('mimetypes_file'),
                                                         \$helper->getConfig('maxsize_file'), null, null);
        if (\$uploader->fetchMedia(\$_POST['xoops_upload_file'][])) {
            //\$uploader->setPrefix('{$fieldName}_') ;
            //\$uploader->fetchMedia(\$_POST['xoops_upload_file'][]);
            if (!\$uploader->upload()) {
                \$errors = \$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, \$errors);
            } else {
                \${$tableName}Obj->setVar('{$fieldName}', \$uploader->getSavedFileName());
            }
        }\n
EOT;

        return $ret;
    }

    /**
     * @public function getUserSaveFieldId
     * @param $fields
     *
     * @return string
     */
    public function getUserSaveFieldId($fields)
    {
        foreach (array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldId;
    }

    /**
     * @public function getUserSaveFieldMain
     * @param $fields
     *
     * @return string
     */
    public function getUserSaveFieldMain($fields)
    {
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldMain;
    }

    /**
     * @public function getUserSaveElements
     * @param $moduleDirname
     * @param $tableName
     * @param $fields
     *
     * @return string
     */
    public function getUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getSimpleSetVar($tableName, $fieldName);
            }
        }

        return $ret;
    }

    /**
     * @public function getSimpleGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getSimpleGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }

    /**
     * @public function getTopicGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $tableNameTopic
     * @param string $fieldNameParent
     * @param string $fieldNameTopic
     * @return string
     */
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldNameParent}
\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /**
     * @public function getUploadImageGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\$upload_image = \${$fieldName} ? \${$fieldName} : 'blank.gif';
\t\t\${$lpFieldName}['{$rpFieldName}'] = \$upload_image;\n
EOT;

        return $ret;
    }

    /**
     * @public function getUrlFileGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n
EOT;

        return $ret;
    }

    /**
     * @public function getTextAreaGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n
EOT;

        return $ret;
    }

    /**
     * @public function getSelectUserGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$lpFieldName}['{$rpFieldName}'] = \XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }

    /**
     * @public function getTextDateSelectGetVar
     * @param string $lpFieldName
     * @param string $rpFieldName
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    public function getTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n
EOT;

        return $ret;
    }
}
