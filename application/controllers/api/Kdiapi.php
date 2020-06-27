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
           
                
                $filename = "uploads/profile/". $nama.time().".jpg";
                $imagedata=str_replace(' ', '+', $linkgbr);
                base64_to_jpeg($imagedata, $filename);

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
    

    
}
