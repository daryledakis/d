<?php
/**
 * Normal Index Visible Factory Driver Interface File
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
 * Normal Index Visible Factory Driver Interface
 * Thou shall not pass a driver to Normal Index Visible factory without
 * implementing this interface. Violators will be shot.
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */
interface NormalIndexVisibleFactoryDriverInterface
{


    /**
     * Throws an error message
     *
     * @param integer $error_num     Error number
     * @param string  $error_message Error Message
     *
     * @return boolean
     */
    public function throwError($error_num, $error_message);


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect();

    /**
     * Disconnects from the source of data
     *
     * @return boolean
     */
    public function disconnect();

    /**
     * Sets the table name
     *
     * @param string $table_name Name of the table
     *
     * @return this
     */
    public function setTable($table_name);

    /**
     * Inserts a record
     *
     * @param array $data Collection of data to be inserted
     *
     * @return integer Last insert ID
     */
    public function insert($data);


    /**
     * Updates a record
     *
     * @param array $data      Data to be updated
     * @param array $condition Collection of criterias
     *
     * @return integer Number of affected records
     */
    public function update($data, $condition);

    /**
     * Empties a table
     *
     * @return boolean Success
     */
    public function truncate();


    /**
     * Executes a query
     *
     * @param string $query MySQL query statement
     *
     * @return boolean Execution result
     */
    public function execute($query);


}
