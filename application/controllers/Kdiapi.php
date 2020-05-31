<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class KdiApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("KdiModel","Kdimodel");
       
    }
    public function getprofile_post(){

        $username = $this->post("username");
            // echo "<pre>";
            // print_r($ket);
            // echo "</pre>";

            $profileData = $this->Kdimodel->getProfileData($username);
        
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
            // echo "<pre>";
            // print_r($ket);
            // echo "</pre>";

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
            // echo "<pre>";
            // print_r($ket);
            // echo "</pre>";

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

    public function getGolProduct_post(){
            
        $sts=$this->post("idsts");
        $golData = $this->Kdimodel->getproductgol($sts);
        if (count($golData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Order Detail";
        }
        $data["data"] = $golData;
        $this->response($data, REST_Controller::HTTP_OK);

    }

    
    public function getGolWarna_post(){
            
        $sts=$this->post("idsts");
        $gol=$this->post("gol");
        $golPoduct = $this->Kdimodel->getproductwarna($sts,$gol);
        if (count($golPoduct) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Order Detail";
        }
        $data["data"] = $golPoduct;
        $this->response($data, REST_Controller::HTTP_OK);

    }
}
