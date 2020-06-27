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

    
    public function addpicture_post(){
        $linkgbr = $this->post("linkgbr");
        $username = $this->post("username");
        
        if ($linkgbr == "" || $username == "") {
            $resp["success"] = 0;
            $resp["message"] = "All field is required";
            $this->response($resp, REST_Controller::HTTP_OK);
          }else{
           
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $alphaLength=10;
            $filename=substr(str_shuffle($alphabet),0,$alphaLength).".jpg";
               // $filename = date('Y-m-d h:i:s').".jpg";
                //$imagedata=str_replace(' ', '+', $linkgbr);
                //base64_to_jpeg($imagedata, $filename);

                $decoded=base64_decode($linkgbr);
                file_put_contents("uploads/profile/".$filename,$decoded);

                $data = array(
                    "picture" => $filename,
                   
                  );
              
                $saveData = $this->Kdimodel->savepciture($username,$data);
                if ($saveData) {
                  $resp["success"] = 1;
                  $resp["message"] = "Success Submit Banner";
                }else{
                  $resp["success"] = 0;
                  $resp["message"] = "Failed to Submit Banner, Already Exists";
                }
                $this->response($resp, REST_Controller::HTTP_OK);
        }
              
    }
            
}

