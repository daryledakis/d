<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class signup extends CI_Controller {

    protected $index = "index.php/";
    protected $controllers = "application/controllers/";
    protected $models = "application/models/";
    protected $views = "application/views/";
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index($data = array()) {
        $url = $this->load->helper('url');
        
        $data['insert_user_url'] = __CLASS__."/insert_user";
        $data['check_username_url'] = __CLASS__."/check_username";
        $data['check_email_url'] = __CLASS__."/check_email";
        $data['check_phone_url'] = __CLASS__."/check_phone";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url(); 
        $this->load->view('signup', $data);
    }

    public function check_username() {
        $username = $this->input->post("username");
        $this->load->model('signupmodel');
        $result = $this->signupmodel->check_username($username);
        echo $result;
    }
    
    public function check_email() {
        $email = $this->input->post("email");
        $this->load->model('signupmodel');
        $result = $this->signupmodel->check_email($email);
        echo $result;
    }
    
    public function check_phone() {
        $phone = $this->input->post("phone");
        echo is_numeric($phone) ? 1 : 0;
    }
    
    public function insert_user() {
        $first_name = $this->input->post("first_name");
        $last_name = $this->input->post("last_name");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $email = $this->input->post("email");
        $phone = $this->input->post("phone");
        
        $this->load->model('signupmodel');

        $result = $this->signupmodel->insert_user($first_name, $last_name, $username, $password, $email, $phone);
        
        echo $result;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */