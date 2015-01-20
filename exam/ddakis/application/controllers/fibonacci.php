<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class fibonacci extends CI_Controller {

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
        
        $data['fibonacci_pizza_url'] = __CLASS__."/fibonacci_pizza";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url(); 
        $this->load->view('fibonacci', $data);
    }

    public function fibonacci_pizza() {
        $max = $this->input->post("text") - 1;
        
        $output = "";
        $ctr = 1;
        $prev = 0;
        for ($start = 1; $ctr <= $max; $start += $prev) {
            $output .= $start.", ";
            $ctr += 1;
            if ($ctr <= $max) {
                $prev = $start + $prev;
                $output .= $prev.", ";
                $ctr += 1;
            }
            else {
                break;
            }
        }
        $output = rtrim($output, ", ");
        
        echo $output;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */