<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SoModel extends CI_Model
{
  public $user = 'user';
  public $soh = 'so_h';
  public $cst = 'customer';
  public $sod = 'so_d';
 

  public function __construct()

  {
    parent::__construct();
    $this->load->database();
  }

  public function getOrderHeader ($username,$noso){
    $data = array();
    $soh=array();
    //$ukur=array();
    $this->db->select('A.noso,B.perusahaan,B.alamat,B.tel,A.tgl,A.grandtotal,A.total,A.ppn,A.stsapprove');
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.sales = C.username');
    $this->db->where('C.email', $username);
    $this->db->where('A.noso', $noso);
    $query = $this->db->get();
      if(!empty($query))
      {
        foreach($query->result() as $key=>$item){
              $noso = $item->noso;
              // $kodemerek=$merek;
              //buat array keterangan
              $soh["soh"]=array(
                'noso'=>$item->noso,
                'perusahaan'=>$item->perusahaan,
                'alamat'=>$item->alamat,
                'tel'=>$item->tel,
                'tgl'=>$item->tgl,
                'grandtotal'=>$item->grandtotal,
                'stsapprove'=>$item->stsapprove,
                'total'=>$item->total,
                'ppn'=>$item->ppn,
              );
              $ambildata=$this->getOrderDetail($item->noso);
              $soh["sod"]=$ambildata;
              $data = $soh;
        }
              
      }
      return $data;
  }

  public function getOrderDetail($noso){
    $this->db->select('A.noso,A.kodebrg,A.ukuran,A.unitqty,A.qty,A.unit,A.warna,A.price,A.total');
    $this->db->from("{$this->sod} A");
    $this->db->where('A.noso', $noso);
    $this->db->order_by("A.Warna DESC");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getCustomer($username){
    $this->db->select('A.kodecst,A.perusahaan');
    $this->db->from("{$this->cst} A");
    $this->db->join("{$this->user} B", 'A.sls = B.username');
    $this->db->where('B.email', $username);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getfilter($cst,$fromdate,$todate,$username){
    $datritgl=date($fromdate);
    $smptgl=date($todate);
    print_r($datritgl);
    $this->db->select("B.perusahaan,A.noso,A.tgl,A.grandtotal,A.stsapprove");
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", "A.cst = B.kodecst");
    $this->db->join("{$this->user} C", "A.sales = C.username");
    $this->db->where('A.tgl BETWEEN "'. date('Y-m-d', strtotime($datritgl)). '" and "'. date('Y-m-d', strtotime($smptgl)).'"');
    $this->db->where("C.email", $username);
    $this->db->where("A.cst", $cst);  
    $this->db->order_by("A.tgl","DESC");
    $query = $this->db->get();
    return $query->result();
  }
}