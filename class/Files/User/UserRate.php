<?php declare(strict_types=1);

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
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
     * @public function constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     *
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
     * @param $module
     * @param $tables
     * @param string $filename
     */
    public function write($module, $tables, string $filename)
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
        $ret .= $this->getRequire();
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'list', 'Cmd');
        $ret .= $this->xc->getXcXoopsRequest('source', 'source', '', 'Int');

        return $ret;
    }

    /**
     * @private function getUserRateSwitch
     * @param $tables
     * @param $language
     * @param $moduleDirname
     * @return string
     */
    private function getUserRateSwitch($tables, $language, $moduleDirname)
    {
        $t = "\t\t";
        $cases  = [
            'list' => [$this->getUserRateDefault( $t)],
            'save' => [$this->getUserRateSave($tables, $language,  $t, $moduleDirname)],
        ];

        return $this->xc->getXcSwitch('op', $cases, true);
    }

    /**
     * @public function getAdminPagesList
     * @param string $t
     * @return string
     */
    public function getUserRateDefault(string $t = '')
    {
        $ret = $this->pc->getPhpCodeCommentLine('default should not happen','', $t);
        $ret .= $this->xc->getXcRedirectHeader('index', '', '3', '\_NOPERM', true, $t);

        return $ret;
    }

    /**
     * @public function getUserRateSave
     * @param $tables
     * @param $language
     * @param $t
     * @param $moduleDirname
     * @return string
     */
    public function getUserRateSave($tables, $language, $t, $moduleDirname)
    {
        $ret                = $this->pc->getPhpCodeCommentLine('Security Check', '', $t);
        $xoopsSecurityCheck = $this->xc->getXcXoopsSecurityCheck();
        $securityError      = $this->xc->getXcXoopsSecurityErrors();
        $implode            = $this->pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader('index', '', '3', $implode, true, $t . "\t");
        $ret                .= $this->pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectError, false, $t);

        $ret .= $this->xc->getXcXoopsRequest('rating', 'rating', '', 'Int', $t);
        $ret .= $this->xc->getXcEqualsOperator('$itemid', '0','', $t);
        $ret .= $this->xc->getXcEqualsOperator('$redir ', "\Xmf\Request::getString('HTTP_REFERER', '', 'SERVER')",'', $t);
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
                $contIf = $this->xc->getXcXoopsRequest('itemid', $fieldId, '', 'Int', $t . "\t");
                $contIf .= $this->xc->getXcEqualsOperator('$redir', "'{$tableName}.php?op=show&{$fieldId}=' . \$itemid",'', $t . "\t");
                $const = $this->xc->getXcGetConstants('TABLE_' . $stuTableName);
                $ret .= $this->pc->getPhpCodeConditions('$source', ' === ', $const, $contIf, false, $t);
            }
        }
        $redirectError = $this->xc->getXcRedirectHeader('$redir', '', '3', "{$language}INVALID_PARAM", false, $t . "\t");
        $ret           .= $this->pc->getPhpCodeConditions('(int)$itemid', ' === ', '0', $redirectError, false, $t);

        $ret    .= $this->pc->getPhpCodeBlankLine();
        $ret    .= $this->pc->getPhpCodeCommentLine('Check permissions', '', $t);
        $ret    .= $this->xc->getXcEqualsOperator('$rate_allowed', 'false','', $t);
        $xUser  = $this->pc->getPhpCodeGlobals('xoopsUser');
        $ret    .= $this->pc->getPhpCodeTernaryOperator('groups', '(isset(' . $xUser . ') && \is_object(' . $xUser . '))', $xUser . '->getGroups()', '[\XOOPS_GROUP_ANONYMOUS]', "\t\t");
        $contIf = $this->xc->getXcEqualsOperator('$rate_allowed', 'true','', $t . "\t\t");
        $contIf .= $this->getSimpleString('break;', $t . "\t\t");
        $cond   = '\XOOPS_GROUP_ADMIN == $group || \in_array($group, $helper->getConfig(\'ratingbar_groups\'))';
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
        $cases[$const] = [$this->pc->getPhpCodeConditions('!\in_array($rating, [-1, 1], true)', '', '', $contIf, false, $t . "\t\t")];
        $const         = $this->xc->getXcGetConstants('RATING_5STARS');
        $cases[$const] = [$this->pc->getPhpCodeConditions('$rating > 5 || $rating < 1', '', '', $contIf, false, $t . "\t\t")];
        $const         = $this->xc->getXcGetConstants('RATING_10STARS');
        $cases[$const] = '';
        $const         = $this->xc->getXcGetConstants('RATING_10NUM');
        $cases[$const] = [$this->pc->getPhpCodeConditions('$rating > 10 || $rating < 1', '', '', $contIf, false, $t . "\t\t")];
        $config        = '(int)' . $this->xc->getXcGetConfig('ratingbars');
        $ret           .= $this->xc->getXcSwitch($config, $cases, true, $t, false, true);

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
        foreach ($tables as $table) {
            $tableName      = $table->getVar('table_name');
            $stuTableName   = \mb_strtoupper($tableName);
            if (1 == $table->getVar('table_rate')) {
                $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
                $fieldId      = '';
                $fieldRatings = '';
                $fieldVotes   = '';
                foreach (\array_keys($fields) as $f) {
                    if (0 == $f) {
                        $fieldId = $fields[$f]->getVar('field_name');
                    }
                    $fieldElement = $fields[$f]->getVar('field_element');
                    if($fieldElement == Modulebuilder\Constants::FIELD_ELE_TEXTRATINGS) {
                        $fieldRatings = $fields[$f]->getVar('field_name');
                    }
                    if($fieldElement == Modulebuilder\Constants::FIELD_ELE_TEXTVOTES) {
                        $fieldVotes = $fields[$f]->getVar('field_name');
                    }
                }
                $sql = $this->getSimpleString("\$sql = '", $t . "\t\t");
                $sql .= $this->getSimpleString("UPDATE ' . \$GLOBALS['xoopsDB']->prefix('" . $moduleDirname . '_' . $tableName . "') . ' t", $t . "\t\t\t");
                $sql .= $this->getSimpleString('LEFT JOIN (', $t . "\t\t\t");
                $sql .= $this->getSimpleString('SELECT', $t . "\t\t\t\t");
                $sql .= $this->getSimpleString('rate_itemid, rate_source, COUNT(*) AS votes, ROUND(AVG(rate_value), 2) AS avg_rating', $t . "\t\t\t\t\t");
                $sql .= $this->getSimpleString("FROM ' . \$GLOBALS['xoopsDB']->prefix('{$moduleDirname}_ratings') . '", $t . "\t\t\t\t");
                $sql .= $this->getSimpleString('GROUP BY rate_itemid, rate_source', $t . "\t\t\t\t");
                $sql .= $this->getSimpleString(") r ON r.rate_itemid = t." . $fieldId . " and r.rate_source = ' . \$source . '", $t . "\t\t\t");
                $sql .= $this->getSimpleString('SET', $t . "\t\t\t");
                $sql .= $this->getSimpleString('t.' . $fieldVotes . ' = COALESCE(r.votes, 0),', $t . "\t\t\t\t");
                $sql .= $this->getSimpleString('t.' . $fieldRatings . ' = COALESCE(r.avg_rating, 0)', $t . "\t\t\t\t");
                $sql .= $this->getSimpleString("WHERE t." . $fieldId . " = ' . \$itemid;", $t . "\t\t\t");
                $contIfInt = $sql;

                $insertInt = "\$GLOBALS['xoopsDB']->queryF(\$sql)";

                $insertOK  = $this->xc->getXcRedirectHeader('$redir', '', '2', "{$language}RATING_VOTE_THANKS", false, $t . "\t\t\t");
                $insertErr = $this->xc->getXcRedirectHeader($tableName, '', '3', "{$language}RATING_ERROR1", true, $t . "\t\t\t");
                $contIfInt .= $this->pc->getPhpCodeConditions($insertInt, '', '', $insertOK, $insertErr, $t. "\t\t");
                $contIfInt .= $this->pc->getPhpCodeUnset($tableName . 'Obj', $t . "\t\t");
                $const     = $this->xc->getXcGetConstants('TABLE_' . $stuTableName);
                $contIf    .= $this->pc->getPhpCodeConditions('$source', ' === ', $const, $contIfInt, false, $t . "\t");
            }
        }

        $contIf .= $this->pc->getPhpCodeBlankLine();
        $contIf .= $this->xc->getXcRedirectHeader('index', '', '2', "{$language}INVALID_PARAM", true, $t . "\t");
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
        return $this->getRequire('footer');
    }

    /**
     * @public function render
     * @return string
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
        $content            .= $this->getUserRateSwitch($tables, $language, $moduleDirname);
        $content            .= $this->getUserRateFooter();

        $this->create($moduleDirname, '/', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
