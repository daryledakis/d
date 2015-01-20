<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class numbers extends CI_Controller {

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
        
        $data['show_me_the_numbers_url'] = __CLASS__."/show_me_the_numbers";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url(); 
        $this->load->view('numbers', $data);
    }

    public function show_me_the_numbers() {
        $text = $this->input->post("text");
        /** My code which works but not using regex.
        $text_count = strlen($text);
        $ctr = 0;
        $output = "";
        while ($ctr < $text_count) {
            $output .= is_numeric($text[$ctr]) ? $text[$ctr] : "";
            $ctr += 1;
        }
        **/
        
        // Alternate code's up there, commented.
        $output = preg_replace("/[^0-9]/", "", $text);
        echo $output;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */