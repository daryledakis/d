<?php
/**
 * Normal Index Factory Class File
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
 * Normal Index Factory Class
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */
class NormalIndexFactory
{

    protected $driver;


    /**
     * Class Constructor
     *
     * @param NormalIndexFactoryDriverInterface $driver Data fetching driver
     */
    public function __construct (NormalIndexFactoryDriverInterface $driver)
    {
        $this->driver = $driver;
    }


    /**
     * Sets the Factory driver
     *
     * @param NormalIndexFactoryDriverInterface $driver Data fetching driver
     *
     * @return \NormalIndexFactory
     */
    public function setDriver (NormalIndexFactoryDriverInterface $driver)
    {
        $this->driver = $driver;
        return $this;

    }


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect ()
    {
        return $this->driver->connect();

    }


    /**
     * Gets normal_index records
     *
     * @return array
     */
    public function getRecords ()
    {
        return $this->driver->getRecords();

    }



    /**
     * Sets the limit
     *
     * @param integer $limit Maximum number of records to be returned
     *
     * @return \NormalIndexFactory
     */
    public function setLimit ($limit)
    {
        $this->driver->setLimit($limit);
        return $this;

    }


    /**
     * Disconnects from the source
     *
     * @return boolean
     */
    public function disconnect ()
    {
        return $this->driver->disconnect();

    }


    /**
     * Sets the table name
     *
     * @param string $table_name Table Name
     * 
     * @return \NormalIndexFactory
     */
    public function setTableName($table_name)
    {
        $this->driver->setTable($table_name);
        return $this;
    }
}
