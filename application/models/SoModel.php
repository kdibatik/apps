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

    $this->db->select("B.perusahaan,A.noso,A.tgl,A.grandtotal,A.stsapprove");
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", "A.cst = B.kodecst");
    $this->db->join("{$this->user} C", "A.sales = C.username");
    $this->db->where('A.tgl BETWEEN "'. date('Y-m-d', strtotime($fromdate)). '" and "'. date('Y-m-d', strtotime($todate)).'"');
    $this->db->where("C.email", $username);
    $this->db->where("A.cst", $cst);  
    $this->db->order_by("A.tgl","DESC");
    $query = $this->db->get();
    return $query->result();
  }

  public function saveordermodelrs($data){
    //save ke table tempso_d
    //$this->db->insert('tempso_d', $data);
    // Return the id of inserted row
    //return $idOfInsertedData = $this->db->insert_id();
    // kurangi stock

  $this->db->trans_begin();
  $this->db->set('sisasls', 'sisasls -'.$data["qty"], FALSE);
  $this->db->set('booking', 'booking +'.$data["qty"], FALSE);
  $this->db->where('kodepro', $data["kodepro"]);
  $this->db->where('ukuran', $data["ukuran"]);
  $this->db->where('warna', $data["warna"]);
  $this->db->update('stock');
  if ($this->db->trans_status() === FALSE)//checks transaction status
    {
        $this->db->trans_rollback();//if update fails rollback and  return false
       return FALSE;

    }
    else
    {   
        
        //if success commit transaction and returns true
        $this->db->insert('tempso_d', $data);
        if($this->db->affected_rows() > 0){
          $this->db->trans_commit();
          return TRUE;
        }else{
          return FALSE;
        }
       
    }

  }
  public function saveordermodelps($data){
  $this->db->trans_begin();
  $this->db->set('sisa', 'sisa -'.$data["qty"], FALSE);
  $this->db->set('qtytrm', 'qtytrm +'.$data["qty"], FALSE);
  $this->db->where('kodepro', $data["kodepro"]);
  $this->db->where('warna', $data["warna"]);
  $this->db->update('stcokpre');
  if ($this->db->trans_status() === FALSE)//checks transaction status
    {
        $this->db->trans_rollback();//if update fails rollback and  return false
       return FALSE;

    }
    else
    {
        $this->db->trans_commit();//if success commit transaction and returns true
        if($this->db->insert('tempsops_d', $data)){
          return TRUE;
        }else{
          return FALSE;
        }
        
    }

  }
}