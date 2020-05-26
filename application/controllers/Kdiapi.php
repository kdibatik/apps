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

}
