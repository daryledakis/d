<?php

/**
 * Normal Index Factory MySQL Driver Interface File
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
 * Normal Index Factory MySQL Driver Interface
 * Fetches data from a MySQL database
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */
class NormalIndexFactoryMySQLDriver implements NormalIndexFactoryDriverInterface
{

    protected $limit = false;
    protected $tableName;
    protected $server;
    protected $username;
    protected $password;
    protected $database;
    protected $db;
    protected $throttle    = 0;
    protected $chunkAmount = 0;
    protected $rows        = array();


    /**
     * Throws an error message
     *
     * @param integer $error_num Error number
     * @param string  $error_msg Error message to be displayed
     *
     * @return boolean
     */
    public function throwError($error_num, $error_msg)
    {
        if ($error_num > 0) {
            throw new \Exception($error_msg);
        }

        return true;

    }


    /**
     * Class constructor
     *
     * @param array $settings Host and security settings of MySQL
     */
    public function __construct($settings)
    {
        if (!isset($settings['server'])) {
            $this->throwError(503, "MySQL host is required");
        }
        if (!isset($settings['username'])) {
            $this->throwError(503, "MySQL username is required");
        }
        if (!isset($settings['password'])) {
            $this->throwError(503, "MySQL password is required");
        }
        if (!isset($settings['database'])) {
            $this->throwError(503, "MySQL database name is required");
        }

        $this->server   = $settings['server'];
        $this->username = $settings['username'];
        $this->password = $settings['password'];
        $this->database = $settings['database'];

    }


    /**
     * Returns all records but the method of fetching is by chunk and
     * throttled
     *
     * @param string $query MySQL query statement
     *
     * @return boolean
     */
    public function getChunkRecords($query)
    {
        if ($this->chunkAmount === 0 && $this->limit === false) {
            $this->getBulkRecords($query);
            return true;
        }

        $offset = 0;
        while (true) {
            $query_new = "{$query} LIMIT {$offset}, {$this->chunkAmount}";
            if ($this->getBulkRecords($query_new) == true) {
                return true;
            }
            sleep($this->throttle);
            $offset += $this->chunkAmount;
        }
        return true;

    }


    /**
     * Returns all records
     *
     * @param string $query MySQL query statement
     *
     * @return boolean
     */
    public function getBulkRecords($query)
    {
        $result = mysql_query($query, $this->db);
        $this->throwError(mysql_errno($this->db), mysql_error($this->db));

        if (mysql_num_rows($result) === 0) {
            return true;
        }

        while ($row = mysql_fetch_assoc($result)) {
            $this->rows[] = $row;
            if (count($this->rows) === $this->limit) {
                return true;
            }
        }
        return false;

    }


    /**
     * Fetches records
     *
     * @return array
     */
    public function getRecords()
    {
        $this->rows   = array();
        $igdata1      = str_replace('normal_index_', 'igalldata_', $this->tableName);
        $igdata       = str_replace('normal_index', 'igalldata', $igdata1);
        $normal_index = $this->tableName;
        $sort         = 'actdate';
        if (strpos($this->tableName, 'normal_index_') !== false) {
            $sort = 'adate';
        }

        $fromDate = strtotime("-3 months");

        $not_found = 0;
        $ctr = 0;
        $last_entryid = 10000000000000;
        while(true) {
            $query = "SELECT `{$normal_index}`.`n_id`, `{$normal_index}`.`n_sample`, `{$normal_index}`.`n_url`, `{$normal_index}`.`is_consignee`, `{$normal_index}`.`is_shipper`, `last_entryid` "
            . "FROM `{$normal_index}` WHERE `n_individual` = 0 AND `last_entryid` < {$last_entryid} ORDER BY `last_entryid` DESC LIMIT 100";

            $result = mysql_query($query, $this->db) or die("\n\nError: {$query}");

            if (\mysql_num_rows($result) > 0) {
                while($row = \mysql_fetch_assoc($result)) {
                    $query = "SELECT `{$sort}` FROM `{$igdata}` WHERE `entryid` = {$row['last_entryid']} ";
                    $result2 = mysql_query($query, $this->db) or die("\n\nError: {$query}");
                    $actdate = \mysql_fetch_assoc($result2);
                    $last_entryid = $row['last_entryid'];

                    if (isset($actdate[$sort]) && trim($actdate[$sort]) != '' && \strtotime($actdate[$sort]) >= $fromDate) {
//                        echo "\n{$ctr}\t{$actdate[$sort]}";
                        unset($row['last_entryid']);
                        $this->rows[] = $row;
                        $ctr++;
                        $not_found = 0;
                    }
                    else {
                        $not_found++;
                    }

                    if ($not_found === 1000) {
                        if ($sort != 'actdate') {
                            $this->rows = array();
                            $query = "SELECT `{$normal_index}`.`n_id`, `{$normal_index}`.`n_sample`, `{$normal_index}`.`n_url`, `{$normal_index}`.`is_consignee`, `{$normal_index}`.`is_shipper`, `last_entryid` "
                            . "FROM `{$normal_index}` WHERE `n_individual` = 0 ORDER BY `last_entryid` DESC LIMIT {$this->limit}";

                            $result = mysql_query($query, $this->db) or die("\n\nError: {$query}");
                            while($row = \mysql_fetch_assoc($result)) {
//                                echo "\n{$ctr}\t{$row['n_id']}";
                                unset($row['last_entryid']);
                                $this->rows[] = $row;
                            }
                        }
                        return $this->rows;
                    }

                    if ($ctr == $this->limit) {
                        return $this->rows;
                    }
                }
            }
            else {
                return $this->rows;
            }

        }
//
//
//        $query = "SELECT `{$normal_index}`.`n_id`, `{$normal_index}`.`n_sample`, `{$normal_index}`.`n_url`, `{$normal_index}`.`is_consignee`, `{$normal_index}`.`is_shipper`"
//            . " FROM `{$normal_index}` INNER JOIN `{$igdata}` ON `{$igdata}`.`entryid` = `{$normal_index}`.`last_entryid`"
//            . "WHERE `{$normal_index}`.`n_individual` = 0 AND `{$igdata}`.`{$sort}` >= (NOW() - INTERVAL 3 MONTH) ORDER BY `{$normal_index}`.`last_entryid` DESC";
//
//        $query = "SELECT `{$normal_index}`.`n_id`, `{$normal_index}`.`n_sample`, `{$normal_index}`.`n_url`, `{$normal_index}`.`is_consignee`, `{$normal_index}`.`is_shipper`, `{$igdata}`.`{$sort}`"
//            . " FROM `{$normal_index}` INNER JOIN `{$igdata}` ON `{$igdata}`.`entryid` = `{$normal_index}`.`last_entryid` "
//            . "WHERE `{$normal_index}`.`n_individual` = 0 ORDER BY `{$normal_index}`.`last_entryid` DESC";
//
//        $this->getChunkRecords($query);
//
//        foreach ($this->rows as $index => $row) {
//            if (strtotime($row[$sort]) < $fromDate) {
//                unset($this->rows[$index]);
//                continue;
//            }
//            else {
//                echo "\n\nRemoving: {$row[$sort]}";
//            }
//            unset($this->rows[$index][$sort]);
//        }
        return $this->rows;

    }


    /**
     * Sets the limit
     *
     * @param integer $limit Maximum number of records to be returned
     *
     * @return \NormalIndexFactoryMySQLDriver
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;

    }


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect()
    {
        $this->db = @mysql_connect(
            $this->server, $this->username, $this->password, true
        );
        $this->throwError(mysql_errno(), mysql_error());

        mysql_select_db($this->database, $this->db);
        $this->throwError(mysql_errno($this->db), mysql_error($this->db));

        return true;

    }


    /**
     * Disconnects from the source of data
     *
     * @return boolean
     */
    public function disconnect()
    {
        if ($this->db && is_resource($this->db)) {
            mysql_close($this->db);
            return true;
        }

        return false;

    }


    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->disconnect();

    }


    /**
     * Sets the throttle interval if we're going to fetch data by chunks
     *
     * @param integer $seconds Interval in seconds
     *
     * @return this
     */
    public function setQueryThrottle($seconds)
    {
        $this->throttle = 0;
        return $this;

    }


    /**
     * Sets the amount of records to be fetched if we're going to fetch by chunk
     *
     * @param integer $amount Maximum number of records to be fetched
     *
     * @return this
     */
    public function setChunkAmount($amount)
    {
        $this->chunkAmount = $amount;
        return $this;

    }


    /**
     * Sets the table name
     *
     * @param string $table_name Table name
     *
     * @return \NormalIndexFactoryMySQLDriver
     */
    public function setTable($table_name)
    {
        $this->tableName = $table_name;
        return $this;

    }
}

