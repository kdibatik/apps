<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KdiModel extends CI_Model
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

  public function getProfileData ($username){
    $month = date('m');
    $this->db->select('A.username,A.laveluser,A.name,A.email,A.idgoogle,A.picture,SUM(B.grandtotal) as ttl');
    $this->db->from("{$this->user} A");
    $this->db->join("{$this->soh} B", 'A.username = B.sales');
    $this->db->where('A.email', $username);
    $this->db->where('Month(B.tgl)', $month);
    $query = $this->db->get();
    return $query->result();
  }

  public function getOmsetLimit ($username){
    $month = date('m');
    $this->db->select('A.noso,B.perusahaan,A.tgl,A.grandtotal,A.stsapprove');
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.sales =C.username');
    $this->db->where('C.email', $username);
    $this->db->where('Month(A.tgl)', $month);
    $this->db->order_by("A.tgl", "DESC");
    $this->db->limit(5); 
    $query = $this->db->get();
    return $query->result();
  }

  public function getOmset ($username){
    $month = date('m');
    $this->db->select('A.noso,B.perusahaan,A.tgl,A.grandtotal,A.stsapprove');
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.sales = C.username');
    $this->db->where('C.email', $username);
    $this->db->where('Month(A.tgl)', $month);
    $this->db->order_by("A.tgl", "DESC");
    $query = $this->db->get();
    return $query->result();
  }

  public function getOrderHeader ($username,$noso){
    $data = array();
    $soh=array();
    //$ukur=array();
    $this->db->select('A.noso,B.perusahaan,B.alamat,B.notel,A.tgl,A.grandtotal,A.total,A.ppn,A.stsapprove');
    $this->db->from("{$this->soh} A");
    $this->db->where('C.email', $username);
    $this->db->where('A.noso', $noso);
    $query = $this->db->get();
      if(!empty($query))
      {
        
              $noso = $item->noso;
              // $kodemerek=$merek;
              //buat array keterangan
              $soh[]=array(
                'noso'=>$item->noso,
                'perusahaan'=>$item->perusahaan,
                'alamat'=>$item->alamat,
                'notel'=>$item->notel,
                'tgl'=>$item->tgl,
                'grandtotal'=>$item->grandtotal,
                'stsapprove'=>$item->stapprove,
                'total'=>$item->total,
                'ppn'=>$item->ppn,
              );
              $ambildata=$this->getOrderDetail($item->noso);
              $soh["sod"]=$ambildata;
              $data = $soh;
              
      }
      return $data;
  }

  public function getOrderDetail($noso){
    $this->db->select('A.noso,A.kodebrg,A.ukuran,A.unitqty,A.qty,A.unit,A.warna,A.price,A.total');
    $this->db->from("{$this->sod} A");
    $this->db->where('A.noso', $noso);
    $this->db->order_by("A.kodebrg DESC", "A.Warna DESC");
    $query = $this->db->get();
    return $query->result_array();
  }
}