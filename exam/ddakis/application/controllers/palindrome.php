<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class palindrome extends CI_Controller {

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
        
        $data['is_it_a_palindrome_url'] = __CLASS__."/is_it_a_palindrome";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url();
        $this->load->view('palindrome', $data);
    }

    public function is_it_a_palindrome() {
        $text = $this->input->post("text");
        $ctr = strlen($text) - 1;
        $reversed = "";
        $zero = 0;
        while ($ctr >= $zero) {
            $reversed .= $text[$ctr];
            $ctr -= 1;
        }
        //echo $text."|".$reversed;
        echo $text == $reversed ? 1 : 0;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */