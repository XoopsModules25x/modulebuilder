<?php

namespace XoopsModules\Modulebuilder;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * settings class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          TDM TEAM DEV MODULE
 *
 */

/**
 * Class SettingsHandler.
 */
class SettingsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|\XoopsDatabase|\XoopsMySQLDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'modulebuilder_settings', Settings::class, 'set_id', 'set_name');
    }

    /**
     * @param bool $isNew
     *
     * @return \XoopsObject
     */
    public function create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field.
     *
     * @param int  $i field id
     * @param null $fields
     *
     * @return mixed reference to the <a href='psi_element://Settings'>Settings</a> object
     *               object
     */
    public function get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id.
     *
     * @param null
     *
     * @return int reference to the {@link Tables} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Settings.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountSettings($start = 0, $limit = 0, $sort = 'set_id ASC, set_name', $order = 'ASC')
    {
        $crCountSettings = new \CriteriaCompo();
        $crCountSettings = $this->getSettingsCriteria($crCountSettings, $start, $limit, $sort, $order);

        return $this->getCount($crCountSettings);
    }

    /**
     * Get All Settings.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllSettings($start = 0, $limit = 0, $sort = 'set_id ASC, set_name', $order = 'ASC')
    {
        $crAllSettings = new \CriteriaCompo();
        $crAllSettings = $this->getSettingsCriteria($crAllSettings, $start, $limit, $sort, $order);

        return $this->getAll($crAllSettings);
    }

    /**
     * Get All Settings.
     *
     * @return array
     */
    public function getActiveSetting()
    {
        $crActiveSetting = new \CriteriaCompo();
        $crActiveSetting->add(new \Criteria('set_type', '1'));

        return $this->getAll($crActiveSetting);
    }


    /**
     * Get Settings Criteria.
     *
     * @param $crSettings
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getSettingsCriteria($crSettings, $start, $limit, $sort, $order)
    {
        $crSettings->setStart($start);
        $crSettings->setLimit($limit);
        $crSettings->setSort($sort);
        $crSettings->setOrder($order);

        return $crSettings;
    }
}
