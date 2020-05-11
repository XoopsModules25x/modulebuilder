<?php

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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class AdminIndex.
 */
class AdminIndex extends Files\CreateFile
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
     * @return AdminIndex
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
     * @param mixed  $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function render
     * @param $module
     *
     * @return string
     */
    private function getAdminIndex($module)
    {
        $pc               = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc               = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $axc              = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
        $moduleDirname    = $module->getVar('mod_dirname');
        $tables           = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $language         = $this->getLanguage($moduleDirname, 'AM');
        $languageThereAre = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');

        $ret              = $this->getSimpleString('');
        $ret              .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Common']);
        $ret              .= $pc->getPhpCodeIncludeDir('dirname(__DIR__)', 'preloads/autoloader', true);
        $ret              .= $this->getInclude();
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Template Index');
        $ret              .= $axc->getAdminTemplateMain((string)$moduleDirname, 'index');
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Count elements');
        $tableName        = null;
        foreach (array_keys($tables) as $i) {
            $tableName    = $tables[$i]->getVar('table_name');
            $ucfTableName = ucfirst($tableName);
            $ret          .= $xc->getXcEqualsOperator("\$count{$ucfTableName}", "\${$tableName}Handler->getCount()");
        }
        $ret .= $pc->getPhpCodeBlankLine();
        $ret .= $pc->getPhpCodeCommentLine('InfoBox Statistics');
        $ret .= $axc->getAxcAddInfoBox($language . 'STATISTICS');
        $ret .= $pc->getPhpCodeCommentLine('Info elements');
        $tableInstall = [];
        foreach (array_keys($tables) as $i) {
            $tableName      = $tables[$i]->getVar('table_name');
            $tableInstall[] = $tables[$i]->getVar('table_install');
            $stuTableName   = $languageThereAre . mb_strtoupper($tableName);
            $ucfTableName   = ucfirst($tableName);
            $ret            .= $axc->getAxcAddInfoBoxLine($stuTableName, "\$count{$ucfTableName}");
        }

        if (null === $tableName) {
            $ret .= $axc->getAxcAddInfoBoxLine('No statistics', '0');
        }

        if (is_array($tables) && in_array(1, $tableInstall)) {
            $ret       .= $pc->getPhpCodeBlankLine();
            $ret       .= $pc->getPhpCodeCommentLine('Upload Folders');
            $ret       .= $xc->getXcEqualsOperator('$configurator', 'new Common\Configurator()');
            $cond      = '$configurator->uploadFolders && is_array($configurator->uploadFolders)';
            $fe_action = $xc->getXcEqualsOperator('$folder[]', '$configurator->uploadFolders[$i]', '',"\t\t");
            $condIf    = $pc->getPhpCodeForeach('configurator->uploadFolders', true, false, 'i', $fe_action, "\t");
            $ret       .= $pc->getPhpCodeConditions($cond, '', '', $condIf, false);

            $ret       .= $pc->getPhpCodeCommentLine('Uploads Folders Created');
            $boxLine   = $axc->getAxcAddConfigBoxLine('$folder[$i]', 'folder', '', "\t");
            $boxLine   .= $axc->getAxcAddConfigBoxLine("array(\$folder[\$i], '777')", 'chmod', '', "\t");
            $ret       .= $pc->getPhpCodeForeach('folder', true, false, 'i', $boxLine, '') . PHP_EOL;
        }
        $ret    .= $pc->getPhpCodeCommentLine('Render Index');
        $ret    .= $xc->getXcXoopsTplAssign('navigation', "\$adminObject->displayNavigation('index.php')");
        $ret    .= $pc->getPhpCodeCommentLine('Test Data');
        $condIf = $xc->getXcXoopsLoadLanguage('admin/modulesadmin',"\t", 'system');
        $condIf .= $pc->getPhpCodeIncludeDir('dirname(__DIR__)', 'testdata/index', true, '','',"\t");
        $condIf .= $axc->getAdminItemButton("constant('CO_' . \$moduleDirNameUpper . '_ADD_SAMPLEDATA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=load', $type = 'samplebutton', $t = "\t");
        $condIf .= $axc->getAdminItemButton("constant('CO_' . \$moduleDirNameUpper . '_SAVE_SAMPLEDATA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=save', $type = 'samplebutton', $t = "\t");
        $condIf .= "//" . $axc->getAdminItemButton("constant('CO_' . \$moduleDirNameUpper . '_EXPORT_SCHEMA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=exportschema', $type = 'samplebutton', $t = "\t");
        $condIf .= $axc->getAdminDisplayButton('left', "\t");
        $cond   = $xc->getXcGetConfig('displaySampleButton');
        $ret    .= $pc->getPhpCodeConditions($cond, '', '', $condIf, false);
        $ret    .= $xc->getXcXoopsTplAssign('index', '$adminObject->displayIndex()');
        $ret    .= $pc->getPhpCodeCommentLine('End Test Data');

        $ret    .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $moduleDirname = $module->getVar('mod_dirname');
        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getAdminIndex($module);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
