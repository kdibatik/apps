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

    public function getdetailOrderrs_post(){

        $username = $this->post("username");
        $noso = $this->post("noso");

        $omsetData = $this->Kdimodel->getOrderHeaderrs($username,$noso);
        
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

    public function saveorderrs_post(){
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
        //$stsstc=$this->post("stsstc");

        if($username!="" && $kodepro!="" && $warna!="" && $ukuran!="" && $unitqty!="" && $qty!="" && $unit!="" && $price!=""){

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

            $omsetData = $this->Kdimodel->saveordermodelrs($data);
            if (!$omsetData) {
                $hasil["message"] = "Something Wrong";
                $hasil["success"] = 0;
            }else{
                $hasil["success"] = 1;
                $hasil["message"] = "Success Add To Cart";
            }
               
        }else{
            $hasil["message"] = "Update Error";
            $hasil["success"] = 0;
        }

        $this->response($hasil, REST_Controller::HTTP_OK);
    }

    public function saveorderps_post(){
        // username,ppn,note,ref,term
        // username,kodepro,warna,ukuran,unitqty,qty,unit,price
        $username = $this->post("username");
        $kodepro=$this->post("kodepro");
        $warna=$this->post("warna");
        $unitqty=$this->post("unitqty");
        $qty=$this->post("qty");
        $price=$this->post("price");
        //$stsstc=$this->post("stsstc");

        if($username!="" && $kodepro!="" && $warna!="" && $unitqty!="" && $qty!="" && $price!=""){

            $data = array(
                'username' => $username,
                'kodepro' => $kodepro,
                'warna' => $warna,
                'unitqty' => $unitqty,
                'qty' => $qty,
                'price' => $price,
               
            );

            $omsetData = $this->Kdimodel->saveordermodelps($data);
            if (!$omsetData) {
                $hasil["message"] = "Something Wrong";
                $hasil["success"] = 0;
            }else{
                $hasil["success"] = 1;
                $hasil["message"] = "Success Add To Cart";
            }
               
        }else{
            $hasil["message"] = "Update Error";
            $hasil["success"] = 0;
        }

        $this->response($hasil, REST_Controller::HTTP_OK);
    }

    public function getorderrs_post(){

        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getorderrs($username);
        $gettotal =$this->Kdimodel->getorderrstotal($username);
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Filter Omset Detail";
        }
           
        $data["data"] = $omsetData;
        $data["total"]=$gettotal;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    
    public function delorderrs_post(){

        $username = $this->post("username");
        $omsetData = $this->Kdimodel->delorderrs($username);
        
        if (!$omsetData) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Delete Order";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function deldetailorderrs_post(){

        $username = $this->post("username");
        $iddata=$this->post("iddata");
        $idstsdel=$this->post("idstsdel"); // ini menandakan del 1 item /del semuanya 1 untuk 1 item 0 untuk semua item
        $omsetData = $this->Kdimodel->deldetailorderrs($username,$iddata,$idstsdel);
        
        if (!$omsetData) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Delete Order Detail";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function submitorderrstemp_post(){
        $date = new DateTime(date('Y-m-d H:i:s'));
        $username = $this->post("username");
        $term= $this->post("term");
        $note= $this->post("note");
        $cst= $this->post("cst");
        $ref= $this->post("ref");
        $ppn=$this->post("ppn");

        $surfixnoso=date('ym');

        $cekmax=$this->Kdimodel->getmaxdata();
       //print_r($cekmax['noso']);
        if($cekmax['noso']!=null){
            if($cekmax['noso'] >= ($surfixnoso * 10000) + 1){
                $noso=$cekmax['noso'] + 1;
            }else{
                $noso=($surfixnoso * 10000) + 1;
            }
        }else{
            $noso=($surfixnoso * 10000) + 1;
        }
        
        if($username!="" && $term!="" && $cst!=""){

            $datasave = array(
                'username' => $username,
                'term' => $term,
                'note' => $note,
                'cst' => $cst,
                'ref' => $ref,
                'ppn' => $ppn,
                'tgl' => date('Y-m-d H:i:s'),
                'noso' => strval($noso),
            );

       // print_r($datasave);
        $omsetDataresult = $this->Kdimodel->submitorderrs($username,$datasave,$noso);
   
        if (!$omsetDataresult) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Submit Order";
        }
           
        $data["data"] = $omsetDataresult;
        $this->response($data, REST_Controller::HTTP_OK);
    }
    }

    public function getorderps_post(){
        $username = $this->post("username");
        $omsetData = $this->Kdimodel->getorderps($username);
        $gettotal =$this->Kdimodel->getorderpstotal($username);
        if (count($omsetData) == 0) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Filter Omset Detail";
        }
           
        $data["data"] = $omsetData;
        $data["total"]=$gettotal;
        $this->response($data, REST_Controller::HTTP_OK);
    }
    public function deldetailorderps_post(){

        $username = $this->post("username");
        $iddata=$this->post("iddata");
        $idstsdel=$this->post("idstsdel"); // ini menandakan del 1 item /del semuanya 1 untuk 1 item 0 untuk semua item
        $omsetData = $this->Kdimodel->deldetailorderps($username,$iddata,$idstsdel);
        
        if (!$omsetData) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Delete Order Detail";
        }
           
        $data["data"] = $omsetData;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function submitorderpstemp_post(){
        $date = new DateTime(date('Y-m-d H:i:s'));
        $username = $this->post("username");
        $term= $this->post("term");
        $note= $this->post("note");
        $cst= $this->post("cst");
        $ref= $this->post("ref");
        $ppn=$this->post("ppn");

        $surfixnoso=date('ym');

        $cekmax=$this->Kdimodel->getmaxdataps();
       //print_r($cekmax['noso']);
        if($cekmax['noso']!=null){
            if($cekmax['noso'] >= ($surfixnoso * 10000) + 1){
                $noso=$cekmax['noso'] + 1;
            }else{
                $noso=($surfixnoso * 10000) + 1;
            }
        }else{
            $noso=($surfixnoso * 10000) + 1;
        }
        
        if($username!="" && $term!="" && $cst!=""){

            $datasave = array(
                'username' => $username,
                'term' => $term,
                'note' => $note,
                'cst' => $cst,
                'ref' => $ref,
                'ppn' => $ppn,
                'tgl' => date('Y-m-d H:i:s'),
                'noso' => strval($noso),
            );

       // print_r($datasave);
        $omsetDataresult = $this->Kdimodel->submitorderps($username,$datasave,$noso);
   
        if (!$omsetDataresult) {
            $data["message"] = "User Tidak ditemukan";
            $data["success"] = 0;
        }else{
            $data["success"] = 1;
            $data["message"] = "Success Submit Order";
        }
           
        $data["data"] = $omsetDataresult;
        $this->response($data, REST_Controller::HTTP_OK);
    }
    }

    public function getorderrslimit_post(){
        $username = $this->post("username");

        $omsetData = $this->Kdimodel->getOrderrslimit($username);
        
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

    public function getorderpslimit_post(){
        $username = $this->post("username");

        $omsetData = $this->Kdimodel->getOrderpslimit($username);
        
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
}