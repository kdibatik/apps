<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class SoApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("Kdimodel","SoModel");
   
    }

    public function getdetailOrder_post(){

        $username = $this->post("username");
        $noso = $this->post("noso");
            // echo "<pre>";
            // print_r($ket);
            // echo "</pre>";

        $omsetData = $this->Kdimodel->getOrderHeader($username,$noso);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Order Detail";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }
}