<?php
/**
 * Normal Index Visible Factory MySQL Driver File
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
 * Normal Index Visible Factory MySQL Driver
 *
 * @category  Scripts
 * @package   ImportGenius_Scripts
 * @author    Erickson Reyes <erickson@importgenius.com>
 * @copyright 2014 ImportGenius
 * @license   http://www.importgenius.com Commercial, Proprietary License
 * @link      http://www.importgenius.com
 */

class NormalIndexVisibleFactoryMySQLDriver implements
NormalIndexVisibleFactoryDriverInterface
{
    protected $server;
    protected $username;
    protected $password;
    protected $database;
    protected $db;
    protected $table;

    /**
     * Class constructor
     *
     * @param array $settings Host and security settings of MySQL
     */
    public function __construct ($settings)
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

        $this->server = $settings['server'];
        $this->username = $settings['username'];
        $this->password = $settings['password'];
        $this->database = $settings['database'];

    }


    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->disconnect();
    }


    /**
     * Connects to the source of data
     *
     * @return boolean
     */
    public function connect ()
    {
        $this->db = @mysql_connect(
            $this->server,
            $this->username,
            $this->password,
            true
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
    public function disconnect ()
    {
        if ($this->db && is_resource($this->db)) {
            mysql_close($this->db);

            return true;
        }

        return false;

    }


    /**
     * Inserts a record
     *
     * @param array $data Collection of data to be inserted
     *
     * @return integer Insert ID
     */
    public function insert ($data)
    {
        $fields = array();
        $values = array();

        foreach ($data as $field => $value) {
            $fields[] = "`" . $field . "`";
            $values[] = "'" . mysql_real_escape_string($value, $this->db) . "'";
        }

        $fields_string = implode(', ', $fields);
        $values_string = implode(', ', $values);

        $query = "INSERT INTO `{$this->table}` ({$fields_string}) "
        . "VALUES ({$values_string}); ";
        $this->execute($query);

        return mysql_insert_id($this->db);
    }


    /**
     * Sets table name
     *
     * @param type $table_name Table name
     *
     * @return \NormalIndexVisibleFactoryMySQLDriver
     */
    public function setTable ($table_name)
    {
        $this->table = $table_name;
        return $this;
    }


    /**
     * Throws an error message
     *
     * @param integer $error_num Error number
     * @param string  $error_msg Error message to be displayed
     *
     * @return boolean
     */
    public function throwError ($error_num, $error_msg)
    {
        if ($error_num > 0) {
            throw new \Exception($error_msg);
        }

        return true;

    }


    /**
     * Empties a table
     *
     * @return boolean Operation result
     */
    public function truncate ()
    {
        mysql_query("TRUNCATE TABLE `{$this->table}`; ", $this->db);
        $this->throwError(mysql_errno($this->db), mysql_error($this->db));

        return true;
    }


    /**
     * Executes a MySQL query
     *
     * @param string $query MySQL query statement
     *
     * @return boolean Execution result
     */
    public function execute ($query)
    {
        mysql_query($query, $this->db);
        $this->throwError(
            mysql_errno($this->db),
            mysql_error($this->db) . "\n" . $query
        );

        return true;
    }


    /**
     * Updates a record
     *
     * @param array $data      Data to be updated
     * @param array $condition Collection of criterias
     *
     * @return integer Number of affected records
     */
    public function update ($data, $condition)
    {
        $updates = array();
        $conditions = array();

        foreach ($data as $field => $value) {
            $value = mysql_real_escape_string($value, $this->db);
            $updates[] = " `{$field}` = '{$value}' ";
        }

        foreach ($condition as $field => $value) {
            $value = mysql_real_escape_string($value, $this->db);
            $conditions[] = " `{$field}` = '{$value}' ";
        }

        $update_string = implode(', ', $updates);
        $where_string = implode(' AND ', $conditions);

        $query = "UPDATE `{$this->table}` SET {$update_string} "
        . "WHERE {$where_string}";
        mysql_query($query, $this->db);
        $this->throwError(
            mysql_errno($this->db),
            mysql_error($this->db) . "\n" . $query
        );

        return mysql_affected_rows($this->db);
    }

}
