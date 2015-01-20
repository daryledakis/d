<?php

class signupmodel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function check_username($username) {
        $this->load->database();
        $username = strtoupper($username);
        $sql = "SELECT id FROM `users` WHERE UPPER(username) = '{$username}'";
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function check_email($email) {
        $this->load->database();
        $email = strtoupper($email);
        $sql = "SELECT id FROM `users` WHERE UPPER(email) = '{$email}'";
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function insert_user($first_name, $last_name, $username, $password, $email, $phone) {
        $this->load->database();
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;

        $this->db->insert('users', $this);
        return $this->db->insert_id();
    }

    function update_user($first_name, $last_name, $username, $password, $email, $phone) {
        $this->load->database();
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;

        $this->db->update('users', $this, array('id' => $this->id));
    }

}
