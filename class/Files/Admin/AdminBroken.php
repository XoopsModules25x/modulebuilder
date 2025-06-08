<?php declare(strict_types=1);

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org
 *                  Goffy https://myxoops.org
 */

/**
 * Class AdminBroken.
 */
class AdminBroken extends Files\CreateFile
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
     * @return AdminBroken
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
     * @param $tables
     * @param $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getAdminBrokenHeader
     * @param        $moduleDirname
     * @param        $tableName
     * @param string $t
     * @return string
     */
    private function getAdminBrokenHeader($moduleDirname, $tableName, $t = '')
    {
        $ret        = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret        .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret        .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret        .= $this->getRequire();
        $ret        .= $this->pc->getPhpCodeBlankLine();
        $ret        .= $this->pc->getPhpCodeCommentLine('Define Stylesheet', '', $t);
        $ret        .= $this->xc->getXcXoThemeAddStylesheet('style', $t);
        $ret        .= $this->axc->getAdminTemplateMain($moduleDirname, $tableName, $t);
        $navigation = $this->axc->getAdminDisplayNavigation($tableName);
        $ret        .= $this->xc->getXcXoopsTplAssign('navigation', $navigation, true, $t);

        return $ret;
    }

    /**
     * @private  function getAdminBrokenList
     * @param        $tables
     * @param        $language
     * @param string $t
     * @return string
     */
    private function getAdminBrokenList($tables, $language, $t = '')
    {
        $ret = '';
        foreach (\array_keys($tables) as $i) {
            if (1 === (int)$tables[$i]->getVar('table_broken')) {
                $tableName     = $tables[$i]->getVar('table_name');
                $tableSoleName = $tables[$i]->getVar('table_solename');
                $ucfTableName  = \ucfirst($tableName);
                $ret           .= $this->pc->getPhpCodeBlankLine();
                $ret           .= $this->pc->getPhpCodeCommentLine('Check table', $tableName, $t);
                $ret           .= $this->xc->getXcXoopsRequest('start', 'start' . $ucfTableName, '', 'Int', false, $t);
                $adminpager    = $this->xc->getXcGetConfig('adminpager');
                $ret           .= $this->xc->getXcXoopsRequest('limit', 'limit' . $ucfTableName, $adminpager, 'Int', false, $t);
                $critName      = 'cr' . $ucfTableName;

                $fields     = $this->getTableFields($tables[$i]->getVar('table_mid'), $tables[$i]->getVar('table_id'));
                $fieldId    = '';
                $fieldMain  = '';
                $fieldSatus = '';
                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (0 == $f) {
                        $fieldId = $fieldName;
                    }
                    if (1 == $fields[$f]->getVar('field_main')) {
                        $fieldMain = $fieldName;
                    }
                    if (16 == $fields[$f]->getVar('field_element')) {
                        $fieldSatus = $fieldName;
                    }
                }

                $ret      .= $this->xc->getXcCriteriaCompo($critName, $t);
                $constant = $this->xc->getXcGetConstants('STATUS_BROKEN');
                $crit     = $this->xc->getXcCriteria('', "'{$fieldSatus}'", $constant, '', true);
                $ret      .= $this->xc->getXcCriteriaAdd($critName, $crit, $t);
                $ret      .= $this->xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName, $t);
                $ret      .= $this->xc->getXcXoopsTplAssign($tableName . '_count', "\${$tableName}Count", true, $t);
                $sprintf  = $this->pc->getPhpCodeSprintf($language . 'BROKEN_RESULT', "'{$ucfTableName}'");
                $ret      .= $this->xc->getXcXoopsTplAssign($tableName . '_result', $sprintf, true, $t);

                $ret      .= $this->xc->getXcCriteriaSetStart($critName, '$start', $t);
                $ret      .= $this->xc->getXcCriteriaSetLimit($critName, '$limit', $t);
                $contIf   = $this->xc->getXcHandlerAllClear("{$tableName}All", $tableName, "\${$critName}", $t . "\t");
                $foreach  = $this->xc->getXcEqualsOperator("\${$tableSoleName}['table']", "'{$ucfTableName}'", '', $t . "\t\t");
                $foreach  .= $this->xc->getXcEqualsOperator("\${$tableSoleName}['key']", "'{$fieldId}'", '', $t . "\t\t");
                $foreach  .= $this->xc->getXcGetVar("{$tableSoleName}['keyval']", "{$tableName}All[\$i]", "{$fieldId}", false, $t . "\t\t");
                $foreach  .= $this->xc->getXcGetVar("{$tableSoleName}['main']", "{$tableName}All[\$i]", "{$fieldMain}", false, $t . "\t\t");
                $foreach  .= $this->xc->getXcXoopsTplAppend("{$tableName}_list", "\${$tableSoleName}", $t . "\t\t");
                $contIf   .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t . "\t");
                $contIf   .= $this->xc->getXcPageNav($tableName, $t . "\t", 'start' . $ucfTableName, "'op=list&limit{$ucfTableName}=' . \$limit");
                $sprintf  = $this->pc->getPhpCodeSprintf($language . 'BROKEN_NODATA', "'{$ucfTableName}'");
                $contElse = $this->xc->getXcXoopsTplAssign('nodata' . $ucfTableName, $sprintf, true, $t . "\t");

                $ret .= $this->pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contIf, $contElse, $t);
                $ret .= $this->pc->getPhpCodeUnset($critName, $t);
            }
        }

        $ret .= $this->pc->getPhpCodeBlankLine();

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
        $tf = Modulebuilder\Files\CreateFile::getInstance();

        $module        = $this->getModule();
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');

        $content = $this->getHeaderFilesComments($module);
        $content .= $this->getAdminBrokenHeader($moduleDirname, 'broken');
        $content .= $this->getAdminBrokenList($tables, $language);
        $content .= $this->getRequire('footer');

        $tf->create($moduleDirname, 'admin', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $tf->renderFile();
    }
}
