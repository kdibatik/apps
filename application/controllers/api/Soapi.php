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

    public function getsofilter_post(){
        $cst = $this->post("cst");
        $fromdate = $this->post("fromdate");
        $todate = $this->post("todate");
        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getfilter($cst,$fromdate,$todate,$username);
        
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Filter Omset Detail";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function saveorder_post(){
        // username,ppn,note,ref,term
        // username,kodepro,warna,ukuran,unitqty,qty,unit,price
        $username = $this->post("username");
        $kodepro=$this->post("kodepro");
        $warna=$this->post("warna");
        $ukuran=$this->post("ukuran");
        $unitqty=$this->post("unitqty");
        $qty=$this->post("qty");
        $unit=$this->post("unit");
        $price=$this->post("price");
        $stsstc=$this->post("stsstc")

        if ($username && $kodepro && $warna && $ukuran && $unitqty && $qty && $unit && $price) {

            $data = array(
                'username' => $username,
                'kodepro' => $kodepro,
                'warna' => $warna,
                'ukuran' => $ukuran,
                'unitqty' => $unitqty,
                'qty' => $qty,
                'unit' => $unit,
                'price' => $price
            );

            $omsetData = $this->Kdimodel->saveordermodel($data,$stsstc);
            if (count($omsetData) == 0) {
                $data["message"] = "User Tidak ditemukan";
                $data["success"] = 0;
            }else{
                $data["success"] = 1;
                $data["message"] = "Success Filter Omset Detail";
            }
               
            $data["data"] = $omsetData;
            
        }else{
            $data["message"] = "Update Error";
            $data["success"] = 0;
        }

        $this->response($data, REST_Controller::HTTP_OK);
    }

}