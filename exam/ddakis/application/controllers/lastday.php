<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class lastday extends CI_Controller {

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
        
        $data['get_lastday_url'] = __CLASS__."/get_lastday";
        $data['controllers'] = base_url().$this->controllers;
        $data['models'] = base_url().$this->models;
        $data['views'] = base_url().$this->views;
        $data['root'] = base_url();
        $this->load->view('lastday', $data);
    }

    public function get_lastday() {
        $months = array("1" => 31,
                        "2" => 28,
                        "3" => 31,
                        "4" => 30,
                        "5" => 31,
                        "6" => 30,
                        "7" => 31,
                        "8" => 31,
                        "9" => 30,
                        "10" => 31,
                        "11" => 30,
                        "12" => 31);
        
        $date = list($year, $month, $day) = explode("-", $this->input->post("date"));
        
        $month = ltrim($month, "0");
        
        $tempmonth = $month;
        $tempyear = $year;
        $output = "";
        for ($x = 1; $x <= 12; $x++) {
            $tempyear = $tempmonth <= 12 ? $tempyear : $tempyear += 1;
            $tempmonth = $tempmonth <= 12 ? $tempmonth : 1;
            if ($tempmonth == 2) {
                //var_dump(checkdate($tempmonth, 29, $tempyear));
                if (checkdate($tempmonth, 29, $tempyear)) {
                    $output .= "{$tempyear}-".str_pad($tempmonth, 2, "0", STR_PAD_LEFT)."-29<br />";
                }
                else {
                    $output .= "{$tempyear}-".str_pad($tempmonth, 2, "0", STR_PAD_LEFT)."-{$months[$tempmonth]}<br />";
                }
            }
            else {
                $output .= "{$tempyear}-".str_pad($tempmonth, 2, "0", STR_PAD_LEFT)."-{$months[$tempmonth]}<br />";
            }
            $tempmonth += 1;
        }
        echo $output;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */