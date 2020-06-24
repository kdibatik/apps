<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class MessageApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("MessageModel","Kdimodel");
   
    }


    public function getMessage_post(){

        $username = $this->post("username");

        $omsetData = $this->Kdimodel->getMessage($username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Get Message";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }
    public function saveMessage_post(){

        $username = $this->post("username");
        $picurl = $this->post("picurl");
        $message = $this->post("message");
        $title = $this->post("title");
        $url = $this->post("url");

        $tglskrg=date('Y-m-d H:i:s');

        $data = array(
            'username' => $username,
            'title' => $title,
            'message' => $message,
            'picurl' =>$picurl,
            'url' => $url,
            'tglsave' => $tglskrg,
        );

        $omsetData = $this->Kdimodel->postMessage($username,$data);
        
        if ($omsetData) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Save Message";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);

    }
}