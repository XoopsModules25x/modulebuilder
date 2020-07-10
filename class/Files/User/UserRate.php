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

/**
 * Class UserRate.
 */
class UserRate extends Files\CreateFile
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
    private $uxc = null;
	
	
	/**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return UserRate
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
     * @param $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserRateHeader
     *
     * @param $moduleDirname
     * @return string
     */
    public function getUserRateHeader($moduleDirname)
    {
        $ret = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret .= $this->getInclude();
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'list', 'Cmd');
        $ret .= $this->xc->getXcXoopsRequest('source', 'source', '0', 'Int');

        return $ret;
    }

    /**
     * @private function getUserRateSwitch
     * @param $tables
     * @param $language
     * @return string
     */
    private function getUserRateSwitch($tables, $language)
    {
        $t = "\t\t";
        $cases  = [
            'list' => [$this->getUserRateDefault( $t)],
            'save' => [$this->getUserRateSave($tables, $language,  $t)],
        ];

        return $this->xc->getXcSwitch('op', $cases, true);
    }

    /**
     * @public function getAdminPagesList
     * @param string $t
     * @return string
     */
    public function getUserRateDefault($t = '')
    {
        $ret = $this->pc->getPhpCodeCommentLine('default should not happen','', $t);
        $ret .= $this->xc->getXcRedirectHeader('index', '', '3', '_NOPERM', true, $t);

        return $ret;
    }

    /**
     * @public function getUserRateSave
     * @param $tables
     * @param $language
     * @param $t
     * @return string
     */
    public function getUserRateSave($tables, $language, $t)
    {
        $ret                = $this->pc->getPhpCodeCommentLine('Security Check', '', $t);
        $xoopsSecurityCheck = $this->xc->getXcXoopsSecurityCheck();
        $securityError      = $this->xc->getXcXoopsSecurityErrors();
        $implode            = $this->pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader('index', '', '3', $implode, true, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $ret .= $this->xc->getXcXoopsRequest('rating', 'rating', '0', 'Int', false, $t);
        $ret .= $this->xc->getXcEqualsOperator('$itemid', '0','', $t);
        $ret .= $this->xc->getXcEqualsOperator('$redir ', "\$_SERVER['HTTP_REFERER']",'', $t);
        foreach ($tables as $table) {
            $tableName = $table->getVar('table_name');
            $stuTableName = \mb_strtoupper($tableName);
            if (1 == $table->getVar('table_rate')) {
                $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
                $fieldId = '';
                foreach (\array_keys($fields) as $f) {
                    if (0 == $f) {
                        $fieldId = $fields[$f]->getVar('field_name');
                    }
                }
                $contIf = $this->xc->getXcXoopsRequest('itemid', $fieldId, '0', 'Int', false, $t . "\t");
                $contIf .= $this->xc->getXcEqualsOperator('$redir', "'{$tableName}.php?op=show&amp;{$fieldId}=' . \$itemid",'', $t . "\t");
                $const = $this->xc->getXcGetConstants('TABLE_' . $stuTableName);
                $ret .= $this->pc->getPhpCodeConditions('$source', ' === ', $const, $contIf, false, $t);
            }
        }

        $ret    .= $this->pc->getPhpCodeBlankLine();
        $ret    .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $ret    .= $this->xc->getXcEqualsOperator('$rate_allowed', 'false','', $t);
        $xUser  = $this->pc->getPhpCodeGlobals('xoopsUser');
        $ret    .= $this->pc->getPhpCodeTernaryOperator('groups', '(isset(' . $xUser . ') && \is_object(' . $xUser . '))', $xUser . '->getGroups()', 'XOOPS_GROUP_ANONYMOUS', "\t\t");
        $contIf = $this->xc->getXcEqualsOperator('$rate_allowed', 'true','', $t . "\t\t");
        $contIf .= $this->getSimpleString('break;', $t . "\t\t");
        $cond   = 'XOOPS_GROUP_ADMIN == $group || \in_array($group, $helper->getConfig(\'ratingbar_groups\'))';
        $contFe = $this->pc->getPhpCodeConditions($cond, '', '', $contIf, false, $t . "\t");
        $ret    .= $this->pc->getPhpCodeForeach('groups', false, false, 'group', $contFe, $t);
        $contIf = $this->xc->getXcRedirectHeader('index', '', '3', $language . 'RATING_NOPERM', true, $t . "\t");
        $ret    .= $this->pc->getPhpCodeConditions('!$rate_allowed', '', '', $contIf, false, $t);

        $ret           .= $this->pc->getPhpCodeBlankLine();
        $ret           .= $this->pc->getPhpCodeCommentLine('Check rating value', null, $t);
        $cases         = [];
        $contIf        = $this->xc->getXcRedirectHeader('index', '', '3', $language . 'RATING_VOTE_BAD', true, $t . "\t\t");
        $contIf        .= $this->getSimpleString('exit;', $t . "\t\t");
        $const         = $this->xc->getXcGetConstants('RATING_NONE');
        $cases[$const] =  [$contIf];
        $contIf        = $this->xc->getXcRedirectHeader('index', '', '3', $language . 'RATING_VOTE_BAD', true, $t . "\t\t\t");
        $contIf        .= $this->getSimpleString('exit;', $t . "\t\t\t");
        $const         = $this->xc->getXcGetConstants('RATING_LIKES');
        $cases[$const] = [$this->pc->getPhpCodeConditions('$rating > 1 || $rating < -1', '', '', $contIf, false, $t . "\t\t")];
        $const         = $this->xc->getXcGetConstants('RATING_5STARS');
        $cases[$const] = [$this->pc->getPhpCodeConditions('$rating > 5 || $rating < 1', '', '', $contIf, false, $t . "\t\t")];
        $const         = $this->xc->getXcGetConstants('RATING_10STARS');
        $cases[$const] = '';
        $const         = $this->xc->getXcGetConstants('RATING_10NUM');
        $cases[$const] = [$this->pc->getPhpCodeConditions('$rating > 10 || $rating < 1', '', '', $contIf, false, $t . "\t\t")];
        $config        = '(int)' . $this->xc->getXcGetConfig('ratingbars');
        $ret           .= $this->xc->getXcSwitch($config, $cases, true, false, $t, false, true);

        $ret .= $this->pc->getPhpCodeBlankLine();
        $ret .= $this->pc->getPhpCodeCommentLine('Get existing rating', null, $t);
        $ret .= $this->xc->getXcEqualsOperator('$itemrating', '$ratingsHandler->getItemRating($itemid, $source)','', $t);

        $ret      .= $this->pc->getPhpCodeBlankLine();
        $ret      .= $this->pc->getPhpCodeCommentLine('Set data rating', null, $t);
        $contIf   = $this->pc->getPhpCodeCommentLine('If yo want to avoid revoting then activate next line', null, $t . "\t");
        $contIf   .= $t . "\t//" . $this->xc->getXcRedirectHeader('index', '', '3', $language . 'RATING_VOTE_BAD');
        $contIf   .= $this->xc->getXcHandlerGet('ratings', "itemrating['id']",'Obj', 'ratingsHandler', false, $t . "\t");
        $contElse = $this->xc->getXcHandlerCreateObj('ratings', $t . "\t");
        $ret      .= $this->pc->getPhpCodeConditions("\$itemrating['voted']", '', '', $contIf, $contElse, $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_source', '$source', $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_itemid', '$itemid', $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_value', '$rating', $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_uid', "\$itemrating['uid']", $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_ip', "\$itemrating['ip']", $t);
        $ret      .= $this->xc->getXcSetVarObj('ratings', 'rate_date', '\time()', $t);
        $ret      .= $this->pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert   = $this->xc->getXcHandlerInsert('ratings', 'ratings', 'Obj');
        $contIf   = $this->pc->getPhpCodeUnset('ratingsObj', $t . "\t");

        $contIf       .= $this->pc->getPhpCodeCommentLine('Calc average rating value', null, $t . "\t");
        $contIf       .= $this->xc->getXcEqualsOperator('$nb_ratings    ', '0','', $t . "\t");
        $contIf       .= $this->xc->getXcEqualsOperator('$avg_rate_value', '0','', $t . "\t");
        $contIf       .= $this->xc->getXcEqualsOperator('$current_rating', '0','', $t . "\t");
        $tableName    = 'ratings';
        $ucfTableName = \ucfirst($tableName);
        $critName     = 'cr' . $ucfTableName;
        $contIf       .= $this->xc->getXcCriteriaCompo($critName, $t . "\t");
        $crit         = $this->xc->getXcCriteria('', "'rate_source'", '$source','',true);
        $contIf       .= $this->xc->getXcCriteriaAdd($critName, $crit, $t . "\t");
        $crit         = $this->xc->getXcCriteria('', "'rate_itemid'", '$itemid','',true);
        $contIf       .= $this->xc->getXcCriteriaAdd($critName, $crit, $t . "\t");
        $contIf       .= $this->xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName, $t . "\t");
        $contIf       .= $this->xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$' . $critName, $t . "\t");
        $contFe       = $this->xc->getXcEqualsOperator('$current_rating', "\$ratingsAll[\$i]->getVar('rate_value')",'+', $t . "\t\t");
        $contIf       .= $this->pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $contFe, $t . "\t");
        $contIf       .= $this->pc->getPhpCodeUnset($tableName . 'All', $t . "\t");
        $contIfInt    = $this->xc->getXcEqualsOperator('$avg_rate_value', 'number_format($current_rating / $ratingsCount, 2)','', $t . "\t\t");
        $contIf       .= $this->pc->getPhpCodeConditions('$ratingsCount', ' > ', '0', $contIfInt, false, $t . "\t");

        $contIf .= $this->pc->getPhpCodeCommentLine('Update related table', null, $t . "\t");
        foreach ($tables as $table) {
            $tableName      = $table->getVar('table_name');
            $tableFieldName = $table->getVar('table_fieldname');
            $stuTableName   = \mb_strtoupper($tableName);
            if (1 == $table->getVar('table_rate')) {
                $contIfInt = $this->xc->getXcEqualsOperator('$tableName', "'{$tableName}'",'', $t . "\t\t");
                $contIfInt .= $this->xc->getXcEqualsOperator('$fieldRatings', "'{$tableFieldName}_ratings'",'', $t . "\t\t");
                $contIfInt .= $this->xc->getXcEqualsOperator('$fieldVotes  ', "'{$tableFieldName}_votes'",'', $t . "\t\t");
                $contIfInt .= $this->xc->getXcHandlerGetObj($tableName, 'itemid', $t . "\t\t");
                $contIfInt .= $this->xc->getXcSetVarObj($tableName, "{$tableFieldName}_ratings", '$avg_rate_value', $t . "\t\t");
                $contIfInt .= $this->xc->getXcSetVarObj($tableName, "{$tableFieldName}_votes", '$ratingsCount', $t . "\t\t");
                $insertInt = $this->xc->getXcHandlerInsert($tableName, $tableName, 'Obj');

                $insertOK  = $this->xc->getXcRedirectHeader('$redir', '', '2', "{$language}RATING_VOTE_THANKS", false, $t . "\t\t\t");
                $insertErr = $this->xc->getXcRedirectHeader($tableName, '', '3', "{$language}RATING_ERROR1", true, $t . "\t\t\t");
                $contIfInt .= $this->pc->getPhpCodeConditions($insertInt, '', '', $insertOK, $insertErr, $t. "\t\t");
                $contIfInt .= $this->pc->getPhpCodeUnset($tableName . 'Obj', $t . "\t\t");
                $const     = $this->xc->getXcGetConstants('TABLE_' . $stuTableName);
                $contIf    .= $this->pc->getPhpCodeConditions('$source', ' === ', $const, $contIfInt, false, $t . "\t");
            }
        }

        $contIf .= $this->pc->getPhpCodeBlankLine();
        $contIf .= $this->xc->getXcRedirectHeader('index', '', '2', "{$language}RATING_VOTE_THANKS", true, $t . "\t");
        $ret    .= $this->pc->getPhpCodeConditions($insert, '', '', $contIf, false, $t);

        $ret .= $this->pc->getPhpCodeCommentLine('Get Error', null, $t);
        $ret .= $this->getSimpleString("echo 'Error: ' . \$ratingsObj->getHtmlErrors();", $t);


        return $ret;
    }

    /**
     * @public function getUserRateFooter
     * @return string
     */
    public function getUserRateFooter()
    {
        return $this->getInclude('footer');
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $tables             = $this->getTables();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getHeaderFilesComments($module);
        $content            .= $this->getUserRateHeader($moduleDirname);
        $content            .= $this->getUserRateSwitch($tables, $language);
        $content            .= $this->getUserRateFooter();

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
