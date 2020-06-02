<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class OmsetApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("OmsetModel","OmsetModel");
       
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
}