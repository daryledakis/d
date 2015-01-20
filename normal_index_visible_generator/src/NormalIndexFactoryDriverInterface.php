<?php
/**
 * Normal Index Factory Driver Interface File
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
 * Normal Index Factory Driver Interface
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */
interface NormalIndexFactoryDriverInterface
{
    /**
     * Throws an error message
     *
     * @param integer $error_num Error number
     * @param string  $error_msg Error message to be displayed
     *
     * @return boolean
     */
    public function throwError($error_num, $error_msg);


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect();


    /**
     * Sets the table name
     *
     * @param string $table_name Name of the table
     *
     * @return this
     */
    public function setTable($table_name);

    /**
     * Disconnects from the source of data
     *
     * @return boolean
     */
    public function disconnect();


    /**
     * Sets the limit
     *
     * @param integer $limit Maximum number of records to be returned
     *
     * @return this
     */
    public function setLimit($limit);


    /**
     * Gets normal_index records
     *
     * @return array
     */
    public function getRecords();


    /**
     * Sets the throttle interval if we're going to fetch data by chunks
     *
     * @param integer $seconds Interval in seconds
     *
     * @return this
     */
    public function setQueryThrottle($seconds);


    /**
     * Sets the amount of records to be fetched if we're going to fetch by chunk
     *
     * @param integer $amount Maximum number of records to be fetched
     *
     * @return this
     */
    public function setChunkAmount($amount);


}
