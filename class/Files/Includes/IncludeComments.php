<?php

namespace XoopsModules\Modulebuilder\Files\Includes;

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
 * Class IncludeComments.
 */
class IncludeComments extends Files\CreateFile
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
     * @return IncludeComments
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
     * @param string $module
     * @param mixed  $table
     */
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /**
     * @public function getCommentsIncludes
     * @param string $module
     * @param string $filename
     *
     * @return bool|string
     */
    public function renderCommentsIncludes($module, $filename)
    {
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $pc->getPhpCodeIncludeDir("__DIR__ . '/../../../mainfile.php'",'',true, true);
        $content       .= $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH.'/include/{$filename}.php'",'',true, true);

        $this->create($moduleDirname, 'include', $filename . '.php', $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
     * @public function getCommentsNew
     * @param string $module
     * @param string $filename
     *
     * @return bool|string
     */
    public function renderCommentsNew($module, $filename)
    {
        $pc            = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc            = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $table         = $this->getTable();
        $moduleDirname = mb_strtolower($module->getVar('mod_dirname'));
        $tableName     = $table->getVar('table_name');
        $fields        = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldMain     = '';
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }
        $content = $this->getHeaderFilesComments($module);
        $content .= $pc->getPhpCodeUseNamespace(['Xmf', 'Request']);
        $content .= $pc->getPhpCodeIncludeDir("__DIR__ . '/../../../mainfile.php'",'',true, true);
        $content .= $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php'",'',true, true);
        $content .= $xc->getXcXoopsRequest('com_itemid', 'com_itemid', '0', 'Int');
        $contIf  = $xc->getXcEqualsOperator("\${$tableName}Handler", "xoops_getModuleHandler('{$tableName}', '{$moduleDirname}')",'',"\t");
        $contIf  .= $xc->getXcHandlerGet("{$tableName}", 'com_itemid','Obj', "{$tableName}Handler", false, "\t");
        $contIf  .= $xc->getXcGetVar('com_replytitle', "{$tableName}Obj", $fieldMain,false, "\t");
        $contIf  .= $pc->getPhpCodeIncludeDir("XOOPS_ROOT_PATH.'/include/{$filename}.php'",'',true, true, '',"\t");
        $content .= $pc->getPhpCodeConditions('$com_itemid',' > ', '0', $contIf);

        $this->create($moduleDirname, 'include', $filename . '.php', $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
     * @public function render
     * @param null
     */
    /*public function render() {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');

        $content = $this->getHeaderFilesComments($module, $filename);
        switch($filename) {
            case 'comment_edit.php':
                $content .= $this->getCommentsIncludes('comment_edit');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_delete.php':
                $content .= $this->getCommentsIncludes('comment_delete');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_post.php':
                $content .= $this->getCommentsIncludes('comment_post');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_reply.php':
                $content .= $this->getCommentsIncludes('comment_reply');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_new.php':
                $content .= $this->getCommentsNew($moduleDirname, 'comment_new');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);
                return $this->render();
            break;
        }
    }*/
}
