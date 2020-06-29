<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SoModel extends CI_Model
{
  public $user = 'user';
  public $soh = 'so_h';
  public $cst = 'customer';
  public $sod = 'so_d';
  public $orderrs_d = 'tempso_d';
  public $orderps_d = 'tempsops_d';
  public $orderrs_h = 'tempso_h';
  public $orderps_h = 'tempsops_h';
  public $prod = 'product';

  public function __construct()

  {
    parent::__construct();
    $this->load->database();
  }

  public function getOrderHeader($username,$noso){
    $data = array();
    $soh=array();
    //$ukur=array();
    $this->db->select('A.noso,B.perusahaan,B.alamat,B.tel,A.tgl,A.grandtotal,A.total,A.ppn,A.ref,A.stsapprove');
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
                'ref'=>$item->ref,
              );
              $ambildata=$this->getOrderDetail($item->noso);
              $soh["sod"]=$ambildata;
              $data = $soh;
        }
              
      }
      return $data;
  }

  public function getOrderHeaderrs($username,$noso){
    $data = array();
    $soh=array();
    //$ukur=array();
    $this->db->select('B.perusahaan,B.alamat,B.tel,A.*, ROUND(SUM(D.qty * D.ukuran * D.price),2) AS total ,IF(A.ppn=0,0,ROUND(SUM(D.qty * D.ukuran * D.price) * 10/100, 2)) AS nilaippn');
    $this->db->from("{$this->orderrs_h} A");
    $this->db->join("{$this->orderrs_d} D", 'A.noso = D.noso');
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.username = C.email');
    $this->db->where('A.username', $username);
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
                'total'=>$item->total,
                'grandtotal'=>(floatval($item->total + $item->nilaippn)),
                'ppn'=>$item->nilaippn,
                'ref'=>$item->ref,
                'stsapprove'=>$item->stsapprove,
              );
              $ambildata=$this->getOrderDetailrs($item->noso);
              $soh["sod"]=$ambildata;
              $data = $soh;
        }
              
      }
      return $data;
  }

  public function getOrderHeaderps($username,$noso){
    $data = array();
    $soh=array();
    //$ukur=array();
    $this->db->select('B.perusahaan,B.alamat,B.tel,A.*, SUM(D.qty * D.price) AS total ,IF(A.ppn=0,0,SUM(D.qty * D.price) * 10/100) AS nilaippn');
    $this->db->from("{$this->orderps_h} A");
    $this->db->join("{$this->orderps_d} D", 'A.noso = D.noso');
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.username = C.email');
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
                'total'=>$item->total,
                'grandtotal'=>($item->total + $item->nilaippn),
                'ppn'=>$item->nilaippn,
                'ref'=>$item->ref,
                 'stsapprove'=>$item->stsapprove,
              );
              $ambildata=$this->getOrderDetailps($item->noso);
              $soh["sod"]=$ambildata;
              $data = $soh;
        }
              
      }
      return $data;
  }

  public function getOrderDetail($noso){
    $this->db->select('A.noso,A.kodebrg,A.namabrg,A.ukuran,A.unitqty,A.qty,A.unit,A.warna,A.price,A.total');
    $this->db->from("{$this->sod} A");
    $this->db->where('A.noso', $noso);
    $this->db->order_by("A.Warna DESC");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getOrderDetailrs($noso){
    $this->db->select('A.noso,A.kodepro,B.namapro,A.ukuran,A.unitqty,A.qty,A.unit,A.warna,A.price,(A.price * A.qty * A.ukuran) as total');
    $this->db->from("{$this->orderrs_d} A");
     $this->db->join("{$this->prod} B", 'A.kodepro = B.kodepro');
    $this->db->where('A.noso', $noso);
    $this->db->order_by("A.Warna DESC");
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getOrderDetailps($noso){
    $this->db->select('A.noso,A.kodepro,B.namapro,A.unitqty,A.qty,A.warna,A.price,(A.price * A.qty) as total');
    $this->db->from("{$this->orderps_d} A");
    $this->db->join("{$this->prod} B", 'A.kodepro = B.kodepro');
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

    $this->db->select("B.perusahaan,A.noso,A.tgl,A.ref,A.grandtotal,A.stsapprove");
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
      $this->db->set('qtykrm', 'qtykrm +'.$data["qty"], FALSE);
      $this->db->where('kodepro', $data["kodepro"]);
      $this->db->where('warna', $data["warna"]);
      $this->db->update('stockpre');
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

  public function getmaxdata(){
    $this->db->select("MAX(noso) as noso");
    $this->db->from("tempso_h");
    $query = $this->db->get();
    return $query->row_array();
  }

  public function submitorderrs($username,$datasave,$noso){
    $this->db->trans_begin();
    if($this->db->insert('tempso_h', $datasave)){

      $this->db->set('noso', $noso, FALSE);
      $this->db->where('noso', 0);
      $this->db->where('username', $username);
      $this->db->update('tempso_d');

      $this->db->trans_commit();
      return true;
    }else{
      $this->db->trans_rollback();
      return false;
    }

  }

  public function getorderrs($username){
    $this->db->select("A.id,A.kodepro,A.warna,A.ukuran,A.unitqty,A.qty,A.unit,A.price,A.note");
    $this->db->from("{$this->orderrs_d} A");
    $this->db->where("A.username", $username);
    $this->db->where("A.noso","0"); 
    $this->db->order_by("A.warna","DESC");
    $query = $this->db->get();
    return $query->result();
  }

  public function getorderrstotal($username){
    $this->db->select("(sum(A.price * A.qty * A.ukuran)) as total");
    $this->db->from("{$this->orderrs_d} A");
    $this->db->where("A.username", $username);
    $this->db->where("A.noso","0"); 
    $query = $this->db->get();
    return $query->result();
  }

  public function delorderrs($username){
      $this->db->trans_begin();
      $this->db->where('username', $username);
      $this->db->where('noso', 0);
      $this->db->delete('tempso_d');
        if($this->db->affected_rows()){
          $this->db->where('username',$username);
          $this->db->delete('tempso_h');
          if($this->db->affected_rows()){
            $this->db->trans_commit();
            return true;
          }else{
          
            $this->db->trans_rollback();
            return false;
          }
        }
        else{
          $this->db->trans_rollback();
          return false;
        }
  }

  public function deldetailorderrs($username,$iddata,$idstsdel){
    
    if($idstsdel == 1){
      $this->db->select('A.kodepro,A.ukuran,A.warna,A.id,A.qty');
      $this->db->from("{$this->orderrs_d} A");
      $this->db->where('A.id', $iddata);
      $this->db->where('A.username', $username);
      $query = $this->db->get();
    }else{
      $this->db->select('A.kodepro,A.ukuran,A.warna,A.id,A.qty');
      $this->db->from("{$this->orderrs_d} A");
      $this->db->where('A.username', $username);
      $query = $this->db->get();
    }
      if(!empty($query))
      {
        $cekpoin = false;
        $this->db->trans_begin();
        foreach($query->result() as $key=>$item){
          $this->db->set('sisasls', 'sisasls +'.$item->qty, FALSE);
          $this->db->set('booking', 'booking -'.$item->qty, FALSE);
          $this->db->where('kodepro', $item->kodepro);
          $this->db->where('ukuran', $item->ukuran);
          $this->db->where('warna', $item->warna);
          $this->db->update('stock');
          if ($this->db->trans_status() === FALSE)//checks transaction status
            {
              $this->db->trans_rollback();//if update fails rollback and  return false
               return FALSE;
            }
            else
            {   
              // $this->db->where('username', $username);
              // $this->db->where('id', $iddata);
              // $this->db->delete('tempso_d');
              // $this->db->affected_rows();
              // if ($this->db->trans_status() === FALSE){
              //   //update stock
              //   $this->db->trans_rollback();
              // }
              if($idstsdel == 1){
                $result = $this->db
                ->where('username', $username)
                ->where('id', $iddata)
                ->delete('tempso_d');
              }else{
                  $result = $this->db
                  ->where('username', $username)
                  ->where('noso', 0)
                  ->delete('tempso_d');
              }
              
              if($result === false){
                $this->db->trans_rollback();
                $cekpoin = true;
                break;
              }
             
            }
        }

        if($cekpoin === true){
          return false;
        }else{
           $this->db->trans_commit();
          return true;
        }
       
    }else{
      return false;
    }
  }
  

  public function getorderps($username){
    $this->db->select("A.id,A.kodepro,A.warna,A.unitqty,A.qty,A.price,A.note");
    $this->db->from("{$this->orderps_d} A");
    $this->db->where("A.username", $username);
    $this->db->where("A.noso","0"); 
    $this->db->order_by("A.warna","DESC");
    $query = $this->db->get();
    return $query->result();
  }
  public function getorderpstotal($username){
    $this->db->select("(sum(A.price * A.qty)) as total");
    $this->db->from("{$this->orderps_d} A");
    $this->db->where("A.username", $username);
    $this->db->where("A.noso","0"); 
    $query = $this->db->get();
    return $query->result();
  }

  public function deldetailorderps($username,$iddata,$idstsdel){
    
    if($idstsdel == 1){
      $this->db->select('A.kodepro,A.warna,A.id,A.qty');
      $this->db->from("{$this->orderps_d} A");
      $this->db->where('A.id', $iddata);
      $this->db->where('A.username', $username);
      $query = $this->db->get();
    }else{
      $this->db->select('A.kodepro,A.warna,A.id,A.qty');
      $this->db->from("{$this->orderps_d} A");
      $this->db->where('A.username', $username);
      $query = $this->db->get();
    }
      if(!empty($query))
      {
        $cekpoin = false;
        $this->db->trans_begin();
        foreach($query->result() as $key=>$item){
          $this->db->set('sisa', 'sisa +'.$item->qty, FALSE);
          $this->db->set('qtykrm', 'qtykrm -'.$item->qty, FALSE);
          $this->db->where('kodepro', $item->kodepro);
          $this->db->where('warna', $item->warna);
          $this->db->update('stockpre');
          if ($this->db->trans_status() === FALSE)//checks transaction status
            {
              $this->db->trans_rollback();//if update fails rollback and  return false
               return FALSE;
            }
            else
            {   
         
              if($idstsdel == 1){
                $result = $this->db
                ->where('username', $username)
                ->where('id', $iddata)
                ->delete('tempsops_d');
              }else{
                  $result = $this->db
                  ->where('username', $username)
                  ->where('noso', 0)
                  ->delete('tempsops_d');
              }
              
              if($result === false){
                $this->db->trans_rollback();
                $cekpoin = true;
                break;
              }
             
            }
        }

        if($cekpoin === true){
          return false;
        }else{
           $this->db->trans_commit();
          return true;
        }
       
    }else{
      return false;
    }
  }

  public function getmaxdataps(){
    $this->db->select("MAX(noso) as noso");
    $this->db->from("tempsops_h");
    $query = $this->db->get();
    return $query->row_array();
  }

  public function submitorderps($username,$datasave,$noso){
    $this->db->trans_begin();
    if($this->db->insert('tempsops_h', $datasave)){

      $this->db->set('noso', $noso, FALSE);
      $this->db->where('noso', 0);
      $this->db->where('username', $username);
      $this->db->update('tempsops_d');

      $this->db->trans_commit();
      return true;
    }else{
      $this->db->trans_rollback();
      return false;
    }

  }

  public function getOrderrslimit($username,$stsdata){
    $month = date('m');
    $this->db->select('A.noso,A.tgl,A.ppn,A.ref,B.perusahaan,sum(C.price * C.qty * C.ukuran) as grandtotal,stsapprove');
    $this->db->from("{$this->orderrs_h} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->orderrs_d} C", 'A.noso =C.noso');
    $this->db->where('A.username', $username);
    $this->db->where('A.stsapprove', '0'); // harus 0 karena untuk yg 1 sudah ada di menu search mobile
    $this->db->where('Month(A.tgl)', $month);
    $this->db->order_by("A.tgl", "DESC");
    $this->db->group_by("A.noso");
    if ($stsdata=="DS"){
      $this->db->limit(5);
    }
     
    $query = $this->db->get();
    return $query->result();
  }

  public function getOrderpslimit($username,$stsdata){
    $month = date('m');
    $this->db->select('A.noso,A.tgl,A.ppn,A.ref,B.perusahaan,sum(C.price * C.qty) as grandtotal');
    $this->db->from("{$this->orderps_h} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->orderps_d} C", 'A.noso =C.noso');
    $this->db->where('A.username', $username);
    $this->db->where('A.stsapprove', '0'); // harus 0 karena untuk yg 1 sudah ada di menu search mobile
    $this->db->where('Month(A.tgl)', $month);
    $this->db->order_by("A.tgl", "DESC");
    $this->db->group_by("A.noso");
    if($stsdata=="DS"){
      $this->db->limit(5); 
    }
    $query = $this->db->get();
    return $query->result();
  }
}