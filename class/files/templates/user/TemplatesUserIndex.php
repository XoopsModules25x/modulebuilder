<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: templates_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserIndex.
 */
class TemplatesUserIndex extends TDMCreateHtmlSmartyCodes
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserIndex
     */
    public static function &getInstance()
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
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @public function getTemplateUserIndexHeader
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexHeader($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header').PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserIndexTable
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserIndexTable($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('table_type');
        $table = $this->getTemplatesUserIndexTableThead($tableName, $language);
        $table .= $this->getTemplatesUserIndexTableTBody($moduleDirname, $tableName, $tableSolename, $language);

        return $this->htmlcode->getHtmlTag('table', array('class' => 'table table-'.$single), $table).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserIndexThead
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserIndexTableThead($tableName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $lang = $this->htmlcode->getSmartyConst($language, $stuTableName);
        $single = $this->htmlcode->getSmartySingleVar('numb_col');
        $th = $this->htmlcode->getHtmlTag('th', array('colspan' => $single), $lang).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;

        return $this->htmlcode->getHtmlTag('thead', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserIndexTbody
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserIndexTableTbody($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('panel_type');
        $include = $this->htmlcode->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSolename);
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'panel panel-'.$single), $include);
        $cont = $this->htmlcode->getHtmlTag('td', array(), $div).PHP_EOL;
        $html = $this->htmlcode->getHtmlEmpty('</tr><tr>').PHP_EOL;
        $cont   .= $this->htmlcode->getSmartyConditions($tableSolename.'.count', ' is div by ', '$divideby', $html).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSolename, $tableName, $cont).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $foreach).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tbody', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserIndexTfoot
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserIndexTableTfoot()
    {
        $td = $this->htmlcode->getHtmlTag('td', array(), '&nbsp;').PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $td).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tfoot', array(), $tr).PHP_EOL;
    }

    /*
    *  @public function getTemplatesUserIndexBodyDefault
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplatesUserIndexBodyDefault($module, $table, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $ret = <<<EOT
<{if count(\${$tableName}) == 0}>
<table class="table table-<{\$table_type}>">
    <thead>
        <tr class="center">
            <th><{\$smarty.const.{$language}TITLE}>  -  <{\$smarty.const.{$language}DESC}></th>
        </tr>
    </thead>
    <tbody>
        <tr class="center">
            <td class="bold pad5">
                <ul class="menu text-center">
                    <li><a href="<{\${$moduleDirname}_url}>"><{\$smarty.const.{$language}INDEX}></a></li>\n
EOT;
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $ret .= <<<EOT
                    <li> | </li>
                    <li><a href="<{\${$moduleDirname}_url}>/{$tableName}.php"><{\$smarty.const.{$language}{$stuTableName}}></a></li>\n
EOT;
        }
        $ret .= <<<EOT
                </ul>
				<div class="justify pad5"><{\$smarty.const.{$language}INDEX_DESC}></div>
            </td>
        </tr>
    </tbody>
    <tfoot>
    <{if \$adv != ''}>
        <tr class="center"><td class="center bold pad5"><{\$adv}></td></tr>
    <{else}>
        <tr class="center"><td class="center bold pad5">&nbsp;</td></tr>
    <{/if}>
    </tfoot>
</table>
<{/if}>\n
EOT;

        return $ret;
    }

    /*
    *  @public function getTemplateUserIndexCategories
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexCategories($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
<div class="table-responsive">
    <table class="table table-<{\$table_type}>">
		<thead>
			<tr>
				<th colspan="<{\$numb_col}>"><{\$smarty.const.{$language}{$stuTableName}}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<{foreach item={$tableSoleName} from=\${$tableName}}>
				<td>
					<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" {$tableSoleName}=\${$tableSoleName}}>
				</td>
			<{if \${$tableSoleName}.count is div by \$numb_col}>
			</tr><tr>
			<{/if}>
				<{/foreach}>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<{\$numb_col}>" class="{$tableSoleName}-thereare"><{\$lang_thereare}></td>
			</tr>
		</tfoot>
	</table>
</div>
<{/if}>\n
EOT;

        $single = $this->htmlcode->getSmartySingleVar('table_type');
        $table = $this->getTemplatesUserIndexTableThead($tableName, $language);
        $table .= $this->getTemplatesUserIndexTableTBody($moduleDirname, $tableName, $tableSoleName, $language);
        $table .= $this->htmlcode->getHtmlTag('table', array('class' => 'table table-'.$single), $table).PHP_EOL;
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'table-responsive'), $table).PHP_EOL;

        return $this->htmlcode->getSmartyConditions($tableName, ' gt ', '0', $div, false, true).PHP_EOL;
    }

    /*
    *  @public function getTemplateUserIndexTable
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexTable($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
	<!-- Start Show new {$tableName} in index -->
	<div class="{$moduleDirname}-linetitle"><{\$smarty.const.{$language}INDEX_LATEST_LIST}></div>
	<table class="table table-<{\$table_type}>">
		<tr>
			<!-- Start new link loop -->
			<{section name=i loop=\${$tableName}}>
				<td class="col_width<{\$numb_col}> top center">
					<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" {$tableSoleName}=\${$tableName}[i]}>
				</td>
	<{if \${$tableName}[i].count is div by \$divideby}>
		</tr><tr>
	<{/if}>
			<{/section}>
	<!-- End new link loop -->
		</tr>
	</table>
<!-- End Show new files in index -->
<{/if}>\n
EOT;

        return $ret;
    }

    /*
    *  @public function getTemplateUserIndexFooter
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexFooter($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplateUserIndexHeader($moduleDirname);
        $content      .= $this->getTemplatesUserIndexBodyDefault($module, $table, $language);
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableCategory = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex = $tables[$t]->getVar('table_index');
            if ((1 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getTemplateUserIndexCategories($moduleDirname, $tableName, $tableSoleName, $language);
            }
            if ((0 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getTemplateUserIndexTable($moduleDirname, $tableName, $tableSoleName, $language);
            }
        }
        $content .= $this->getTemplateUserIndexFooter($moduleDirname);

        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
