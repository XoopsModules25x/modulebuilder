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
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 * @param mixed      $module
 * @param null|mixed $prev_version
 */

/**
 * @param      $module
 * @param null $prev_version
 *
 * @return bool|null
 */
function xoops_module_update_modulebuilder(&$module, $prev_version = null)
{

    $ret = null;
    if ($prev_version < 191) {
        update_modulebuilder_v191($module);
    }

	if (!modulebuilder_check_db($module)) {
        print_r($module->getErrors());
    }

    if (!clean_index_files()) {
        print_r($module->getErrors());
    }
	
	//check upload directory
	require_once __DIR__ . '/install.php';
    xoops_module_install_modulebuilder($module);
	
    $errors = $module->getErrors();
    if (!empty($errors)) {
        print_r($errors);
    }

    return $ret;

}

// irmtfan bug fix: solve templates duplicate issue
/**
 * @param $module
 *
 * @return bool
 */
function update_modulebuilder_v191($module)
{
    global $xoopsDB;
    $result = $xoopsDB->query(
        'SELECT t1.tpl_id FROM ' . $xoopsDB->prefix('tplfile') . ' t1, ' . $xoopsDB->prefix('tplfile') . ' t2 WHERE t1.tpl_refid = t2.tpl_refid AND t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_type = t2.tpl_type AND t1.tpl_id > t2.tpl_id'
    );
    $tplids = [];
    while (list($tplid) = $xoopsDB->fetchRow($result)) {
        $tplids[] = $tplid;
    }
    if (\count($tplids) > 0) {
        $tplfileHandler  = \xoops_getHandler('tplfile');
        $duplicate_files = $tplfileHandler->getObjects(
            new \Criteria('tpl_id', '(' . \implode(',', $tplids) . ')', 'IN')
        );

        if (\count($duplicate_files) > 0) {
            foreach (\array_keys($duplicate_files) as $i) {
                $tplfileHandler->delete($duplicate_files[$i]);
            }
        }
    }
    $sql = 'SHOW INDEX FROM ' . $xoopsDB->prefix('tplfile') . " WHERE KEY_NAME = 'tpl_refid_module_set_file_type'";
    if (!$result = $xoopsDB->queryF($sql)) {
        xoops_error($xoopsDB->error() . '<br />' . $sql);

        return false;
    }
    $ret = [];
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[] = $myrow;
    }
    if (!empty($ret)) {
        $module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is exist. Note: check 'tplfile' table to be sure this index is UNIQUE because XOOPS CORE need it."
        );

        return true;
    }
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tplfile') . ' ADD UNIQUE tpl_refid_module_set_file_type ( tpl_refid, tpl_module, tpl_tplset, tpl_file, tpl_type )';
    if (!$result = $xoopsDB->queryF($sql)) {
        xoops_error($xoopsDB->error() . '<br />' . $sql);
        $module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is not added to 'tplfile' table. Warning: do not use XOOPS until you add this unique index."
        );

        return false;
    }

    return true;
}
// irmtfan bug fix: solve templates duplicate issue

/**
 * function to add code for db checking
 * @param $module
 *
 * @return bool
 */
function modulebuilder_check_db($module)
{
    $ret = true;
	//insert here code for database check
    global $xoopsDB;

    // new form field SelectStatus
    $fname  = 'SelectStatus';
    $fid    = 16;
    $fvalue = 'XoopsFormSelectStatus';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field Password
    $fname  = 'Password';
    $fid    = 17;
    $fvalue = 'XoopsFormPassword';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 17
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 17 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field SelectCountry
    $fname  = 'SelectCountry';
    $fid    = 18;
    $fvalue = 'XoopsFormSelectCountry';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 18
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 18 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field SelectLanguage
    $fname  = 'SelectLang';
    $fid    = 19;
    $fvalue = 'XoopsFormSelectLang';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 19
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 19 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field Radio
    $fname  = 'Radio';
    $fid    = 20;
    $fvalue = 'XoopsFormRadio';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 20
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 20 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field DateTime
    $fname  = 'DateTime';
    $fid    = 21;
    $fvalue = 'XoopsFormDateTime';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 21
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 21 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field DateTime
    $fname  = 'SelectCombo';
    $fid    = 22;
    $fvalue = 'XoopsFormSelectCombo';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 22
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 22 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // update table 'modulebuilder_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fieldelements');
    $field   = 'fieldelement_sort';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `fieldelement_value`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fieldelements');
    $field   = 'fieldelement_deftype';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(10) NOT NULL DEFAULT '0' AFTER `fieldelement_sort`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fieldelements');
    $field   = 'fieldelement_defvalue';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` int(10) NULL DEFAULT '0' AFTER `fieldelement_deftype`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_fields'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fields');
    $field   = 'field_ifoot';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `field_user`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }
    // update table 'modulebuilder_fields'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fields');
    $field   = 'field_ibody';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `field_user`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }
    // update table 'modulebuilder_fields'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fields');
    $field   = 'field_ihead';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `field_user`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_morefiles'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_morefiles');
    $field   = 'file_type';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `file_mid`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_morefiles'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_morefiles');
    $field   = 'file_upload';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` varchar(255) NOT NULL DEFAULT '' AFTER `file_extension`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // new form field text UUID
    $fname     = 'TextUuid';
    $fid       = 23;
    $fvalue    = 'XoopsFormTextUuid';
    $fsort     = 22;
    $fdeftype  = 14;
    $fdefvalue = 45;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 23
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 23 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field text IP
    $fname     = 'TextIp';
    $fid       = 24;
    $fvalue    = 'XoopsFormTextIp';
    $fsort     = 23;
    $fdeftype  = 14;
    $fdefvalue = 16;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 23
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 23 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // update table 'modulebuilder_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fieldelements');
    $field   = 'fieldelement_defvalue';
    $sql = "ALTER TABLE `$table` CHANGE `$field` `$field` varchar(5) NULL DEFAULT NULL;";
    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
        $module->setErrors("Error when changing '$field' in table '$table'.");
        $ret = false;
    }

    // new form field text comments
    $fname     = 'TextComments';
    $fid       = 25;
    $fvalue    = 'XoopsFormTextComments';
    $fsort     = 24;
    $fdeftype  = 2;
    $fdefvalue = 10;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 25
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 25 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }
    // new form field text ratings
    $fname     = 'TextRatings';
    $fid       = 26;
    $fvalue    = 'XoopsFormTextRatings';
    $fsort     = 25;
    $fdeftype  = 7;
    $fdefvalue = '10, 2';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 26
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 26 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }
    // new form field text votes
    $fname     = 'TextVotes';
    $fid       = 27;
    $fvalue    = 'XoopsFormTextVotes';
    $fsort     = 26;
    $fdeftype  = 2;
    $fdefvalue = 10;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 27
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 27 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }
    // new form field text votes
    $fname     = 'TextReads';
    $fid       = 28;
    $fvalue    = 'XoopsFormTextReads';
    $fsort     = 27;
    $fdeftype  = 2;
    $fdefvalue = 10;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 28
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 28 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // update table 'modulebuilder_tables'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_tables');
    $field   = 'table_reads';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` TINYINT(1) NOT NULL DEFAULT '0' AFTER `table_rss`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // update table 'modulebuilder_settings'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_settings');
    $field   = 'set_min_xoops';
    $sql = "ALTER TABLE `$table` CHANGE `$field` `$field` varchar(15) NOT NULL DEFAULT '1.0';";
    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
        $module->setErrors("Error when changing '$field' in table '$table'.");
        $ret = false;
    }

    // update table 'modulebuilder_modules'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_modules');
    $field   = 'mod_min_xoops';
    $sql = "ALTER TABLE `$table` CHANGE `$field` `$field` varchar(15) NOT NULL DEFAULT '1.0';";
    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
        $module->setErrors("Error when changing '$field' in table '$table'.");
        $ret = false;
    }

    // update table 'modulebuilder_modules'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_modules');
    $field   = 'mod_since';
    $sql = "ALTER TABLE `$table` CHANGE `$field` `$field` varchar(10) NOT NULL DEFAULT '1.0';";
    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
        $module->setErrors("Error when changing '$field' in table '$table'.");
        $ret = false;
    }

    // update table 'modulebuilder_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('modulebuilder_fieldelements');
    $field   = 'fieldelement_deffield';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` VARCHAR(5) NOT NULL DEFAULT '' AFTER `fieldelement_defvalue`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // new form field text votes
    $fname     = 'TextInteger';
    $fid       = 29;
    $fvalue    = 'XoopsFormText';
    $fsort     = 14;
    $fdeftype  = 2;
    $fdefvalue = 10;
    $fdeffield = 0;
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue, $fe_deffield) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`, `fieldelement_deffield`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}', '{$fe_deffield}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 29
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 29 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}', `fieldelement_deffield` = '{$fdeffield}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`, `fieldelement_deffield`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}', '{$fdeffield}')";
            $result = $xoopsDB->query($sql);
        }
    }
    // new form field text votes
    $fname     = 'TextFloat';
    $fid       = 30;
    $fvalue    = 'XoopsFormText';
    $fsort     = 14;
    $fdeftype  = 6;
    $fdefvalue = '16,2';
    $fdeffield = '0.00';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('modulebuilder_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value, $fe_sort, $fe_deftype, $fe_defvalue, $fe_deffield) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`, `fieldelement_deffield`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}', '{$fe_sort}', '{$fe_deftype}', '{$fe_defvalue}', '{$fe_deffield}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 30
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('modulebuilder_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 30 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}', `fieldelement_sort` = '{$fsort}', `fieldelement_deftype` = '{$fdeftype}', `fieldelement_defvalue` = '{$fdefvalue}', `fieldelement_deffield` = '{$fdeffield}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`, `fieldelement_sort`, `fieldelement_deftype`, `fieldelement_defvalue`, `fieldelement_deffield`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}', '{$fsort}', '{$fdeftype}', '{$fdefvalue}', '{$fdeffield}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // resorting elements
    $sortElements = [];
    $sortElements[] = 'Text';
    $sortElements[] = 'TextInteger';
    $sortElements[] = 'TextFloat';
    $sortElements[] = 'TextArea';
    $sortElements[] = 'DhtmlTextArea';
    $sortElements[] = 'CheckBox';
    $sortElements[] = 'RadioYN';
    $sortElements[] = 'Radio';
    $sortElements[] = 'SelectBox';
    $sortElements[] = 'SelectCombo';
    $sortElements[] = 'SelectUser';
    $sortElements[] = 'ImageList';
    $sortElements[] = 'SelectFile';
    $sortElements[] = 'UploadImage';
    $sortElements[] = 'UploadFile';
    $sortElements[] = 'UrlFile';
    $sortElements[] = 'DateTime';
    $sortElements[] = 'TextDateSelect';
    $sortElements[] = 'SelectStatus';
    $sortElements[] = 'SelectCountry';
    $sortElements[] = 'SelectLang';
    $sortElements[] = 'Password';
    $sortElements[] = 'ColorPicker';
    $sortElements[] = 'TextUuid';
    $sortElements[] = 'TextIp';
    $sortElements[] = 'TextComments';
    $sortElements[] = 'TextRatings';
    $sortElements[] = 'TextVotes';
    $sortElements[] = 'TextReads';
    foreach ($sortElements as $key => $sortElement) {
        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('modulebuilder_fieldelements') . ' SET `fieldelement_sort` = ' . ($key + 1) . ' WHERE `' . $xoopsDB->prefix('modulebuilder_fieldelements') . "`.`fieldelement_name` = '" . $sortElement . "'");

    }

    return $ret;
}

/**
 * function remove unnecessary index.php from files folder
 * which could be added by index_scan module
 *
 * @return bool
 */
function clean_index_files()
{
    $files = [];
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/admin/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/assets/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/class/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/include/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/language/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/commonfiles/preloads/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/ratingfiles/assets/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/ratingfiles/class/index.php';
    $files[] = \XOOPS_ROOT_PATH . '/modules/modulebuilder/files/ratingfiles/templates/index.php';

    foreach($files as $file) {
        if (\file_exists($file)) {
            \unlink($file);
        }
    }

    return true;
}
