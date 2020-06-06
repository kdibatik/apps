<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class SoApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("SoModel","Kdimodel");
   
    }

    public function getdetailOrder_post(){

        $username = $this->post("username");
        $noso = $this->post("noso");

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

    public function getcustomer_post(){
        $username = $this->post("username");


        $omsetData = $this->Kdimodel->getCustomer($username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Customer Detail";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);
    }
}