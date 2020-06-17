<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class OmsetApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("OmsetModel","Kdimodel");
       
    }

    public function gettotalomset_post(){

        $username = $this->post("username");
            $profileData = $this->Kdimodel->getTotalOmset($username);
        if (count($profileData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
        $data["data"] = $profileData;
        $this->response($data, REST_Controller::HTTP_OK);

    }


    public function getomsetlimit_post(){

        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getOmsetLimit($username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }
    public function getomset_post(){

        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getOmset($username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }

    public function getomsetyear_post(){
        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getOmsetYear($username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }

    public function getomsetmonth_post(){

        $username = $this->post("username");
        $bln=$this->post("bln");
        $omsetData = $this->Kdimodel->getOmsetMonth($username,$bln);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }

    public function getomsetcustomer_post(){

        $username = $this->post("username");
        $bln=$this->post("bln");
        $cst=$this->post("cst");
        $omsetData = $this->Kdimodel->getOmsetCustomer($username,$bln,$cst);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Profile";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }
   
}