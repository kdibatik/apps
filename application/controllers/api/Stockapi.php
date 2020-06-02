<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class StockApi extends REST_Controller {
 
    function __construct()
    {
        parent::__construct();
        $this->load->model("StockModel","Kdimodel");
       
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

    public function getsisastock_post(){
            
        $sts=$this->post("idsts");
        $gol=$this->post("gol");
        $kodepro=$this->post("kodepro");
        $sisaProduct = $this->Kdimodel->getstock($sts,$gol,$kodepro);
        if (count($sisaProduct) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{

            $data["success"] = 1;
            $data["message"] = "Success Get Order Detail";
        }
        $data= $sisaProduct;
        $this->response($data, REST_Controller::HTTP_OK);

    }
}