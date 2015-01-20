<?php
/**
 * Normal Index Visible Factory Class File
 *
 * PHP Version: 5.3
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */

/**
 * Normal Index Visible Factory Class
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */
class NormalIndexVisibleFactory
{

    protected $drivers;


    /**
     * Class Constructor
     *
     * @param NormalIndexFactoryDriverInterface $driver Data fetching driver
     */
    public function __construct (NormalIndexVisibleFactoryDriverInterface $driver)
    {
        $this->drivers[] = $driver;
    }


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect ()
    {
        foreach ($this->drivers as $driver) {
            $driver->connect();
        }
    }


    /**
     * Adds a driver
     *
     * @param type $driver Driver
     *
     * @return \NormalIndexVisibleFactory
     */
    public function addDriver($driver)
    {
        $this->drivers[] = $driver;
        return $this;
    }


    /**
     * Sets the table name
     *
     * @param type $table_name Table name
     *
     * @return \NormalIndexVisibleFactory
     */
    public function setTableName($table_name)
    {
        foreach ($this->drivers as $driver) {
            $driver->setTable($table_name);
        }
        return $this;
    }


    /**
     * Empties the table
     *
     * @return boolean Result
     */
    public function truncate()
    {
        $last_result = null;
        foreach ($this->drivers as $driver) {
            $last_result = $driver->truncate();
        }
        return $last_result;
    }


    /**
     * Inserts data
     *
     * @param array $data Data to be inserted
     *
     * @return integer Insert Id
     */
    public function insert($data)
    {
        $last_insert_id = null;
        foreach ($this->drivers as $driver) {
            $last_insert_id = $driver->insert($data);
        }
        return $last_insert_id;
    }


    /**
     * Updates a record
     *
     * @param array $data      Data to be updated
     * @param array $condition Collection of criterias
     *
     * @return integer Number of affected records
     */
    public function update($data, $condition)
    {
        $last_affected_numbers_count = null;
        foreach ($this->drivers as $driver) {
            $last_affected_numbers_count = $driver->update($data, $condition);
        }
        return $last_affected_numbers_count;
    }

}
