<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KdiModel extends CI_Model
{
  public $user = 'user';
  public $soh = 'so_h';
  public $cst = 'customer';
  public $sod = 'so_d';
  public $viewgolstockpre = 'golstockpre';
  public $viewgolstock = 'golstock';
  public $prod = 'product';
  public $stock = 'stock';
  public $stockpre = 'stockpre';
  public $golstock = 'gol';

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


  public function getproductgol($sts){
    if($sts =="PS"){
      
      $this->db->select('A.id,A.nama,COUNT(B.id) AS ttl');
      $this->db->from("{$this->golstock} A");
      $this->db->join("{$this->viewgolstockpre} B", 'A.id = B.id');
      $this->db->group_by("B.id");

    }else if($sts =="RS"){
     
      $this->db->select('A.id,A.nama,COUNT(B.id) AS ttl');
      $this->db->from("{$this->golstock} A");
      $this->db->join("{$this->viewgolstock} B", 'A.id = B.id');
      $this->db->group_by("B.id");

    }

    $query = $this->db->get();
    return $query->result_array();
  }

  public function getproductwarna($sts,$gol){
    if($sts =="RS"){
      
      $this->db->select('A.kodepro,count(B.kodepro) as warna');
      $this->db->from("{$this->prod} A");
      $this->db->join("{$this->stock} B", 'A.kodepro = B.kodepro');
      $this->db->where('A.gol', $gol);
      $this->db->where('B.sisasls >', 0);
      $this->db->group_by("A.kodepro,A.namapro");

    }else if($sts =="PS"){
     
      $this->db->select('A.kodepro,count(B.kodepro) as warna');
      $this->db->from("{$this->prod} A");
      $this->db->join("{$this->stockpre} B", 'A.kodepro = B.kodepro');
      $this->db->where('A.gol', $gol);
      $this->db->where('B.sisa >', 0);
      $this->db->group_by("A.kodepro,A.namapro");

    }

    $query = $this->db->get();
    return $query->result_array();
  }

  public function getstock($sts,$gol,$kodepro){
      $data = array();
      $datastc=array();
      $datatemp=array();
    if ($sts == "RS"){
 
      $this->db->select('A.warna');
      $this->db->from("{$this->stock} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->group_by("A.warna");
      $query = $this->db->get();
      if(!empty($query))
      {
        $datastc["color"] = $query->result();
        foreach($query->result() as $key=>$item){
         
          $ambildata=$this->getStockDetail($item->warna,$sts,$kodepro);
          $datatemp[$item->warna]=$ambildata;
          
        }
        $datastc[]=$datatemp;
        $data = $datastc;
      }

    }elseif($sts == "PS"){

      $this->db->select('A.warna');
      $this->db->from("{$this->stockpre} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->group_by("A.warna");
      $query = $this->db->get();
      if(!empty($query))
      {
        $datastc["color"] = $query->result();
        foreach($query->result() as $key=>$item){
         
          $ambildata=$this->getStockDetail($item->warna,$sts,$kodepro);
          $datatemp[$item->warna]=$ambildata;
          

        }
        $datastc[]=$datatemp;
        $data = $datastc;
      }
    }
    
    return $data;
  }

  public function getStockDetail($warna,$sts,$kodepro){
    if($sts=="RS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisa,"Roll",A.warna ');
      $this->db->from("{$this->stock} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->where('A.sisasls >', 0);
      $this->db->where('A.warna', $warna);
      // $this->db->group_by('A.warna','asc');
      $this->db->group_by('A.ukuran','desc');
    }elseif($sts=="PS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisa,"Roll",A.warna ');
      $this->db->from("{$this->stockpre} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->where('A.sisa >', 0);
      $this->db->where('A.warna', $warna);

    }
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getstock_($sts,$gol,$kodepro){
    if($sts=="RS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisa,"Roll",A.warna');
      $this->db->from("{$this->stock} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->where('A.sisasls >', 0);
      // $this->db->group_by('A.warna','asc');
      $this->db->group_by('A.ukuran','desc');
    }
    elseif($sts=="PS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisa,"Roll",A.warna');
      $this->db->from("{$this->stockpre} A");
      $this->db->where('A.kodepro', $kodepro);
      $this->db->where('A.sisa >', 0);
      
    }

    $query = $this->db->get();
    return $query->result_array();
  }

}