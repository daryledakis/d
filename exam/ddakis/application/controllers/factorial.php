<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class factorial extends CI_Controller {

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
        
        $data['the_factorial_computation_url'] = __CLASS__."/the_factorial_computation";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url(); 
        $this->load->view('factorial', $data);
    }

    public function the_factorial_computation() {
        $etonumber = $this->input->post("text");
        $etomultiplier = $etonumber - 1;
        
        $output = $this->recursive_factorial_computation($etonumber, $etomultiplier, "");
        echo $output;
    }

    public function recursive_factorial_computation($number, $multiplier, $result = "") {
        if ($multiplier == 1) {
            $result = $result * $multiplier;
            return $result;
        }
        else {
            $result = $result > 0 ? $result * $multiplier : $number * $multiplier;
            $multiplier -= 1;
            $result = $this->recursive_factorial_computation($number, $multiplier, $result);
            return $result;
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */