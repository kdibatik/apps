<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockModel extends CI_Model
{
 
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
      
      $this->db->select('A.kodepro,A.warna,COUNT(A.ukuran) AS ukr,sum(A.ukuran * A.sisasls) as ttlwarna,A.unitqty');
      //$this->db->select('A.kodepro,count(B.kodepro) as warna');
      $this->db->from("{$this->stock} A");
      $this->db->join("{$this->prod} B", 'A.kodepro = B.kodepro');
      $this->db->join("{$this->golstock} C", 'B.gol = C.id');
      $this->db->where('C.id', $gol);
      $this->db->where('A.sisasls >', 0);
      $this->db->group_by("A.kodepro,A.warna");

    }else if($sts =="PS"){
     
      $this->db->select('A.kodepro,count(B.kodepro) as warna');
      $this->db->from("{$this->prod} A");
      $this->db->join("{$this->stockpre} B", 'A.kodepro = B.kodepro');
      $this->db->where('A.gol', $gol);
      $this->db->where('B.sisa >', 0);
      $this->db->group_by("A.kodepro");

    }

    $query = $this->db->get();
    return $query->result_array();
  }

  public function getstock($sts,$gol,$kodepro,$warna){
        $datastc = array();
      //  $warna=array();
      // $datatemp=array();

    if ($sts == "RS"){
      
      $this->db->select('A.warna,SUM(sisasls * ukuran) AS ttl');
      $this->db->from("{$this->stock} A");
      $this->db->like('A.kodepro', $kodepro);
      if($warna != "%"){
        $this->db->like('A.warna', $warna,'none',false);
      }
      
      $this->db->group_by("A.warna");
      $query = $this->db->get();
      if(!empty($query))
      {
        //Print_r($query->Result());
        foreach($query->result() as $row){
          //$datacolor["color"]=$row->warna;
          $ambildata=$this->getStockDetail($row->warna,$sts,$kodepro);
          $datastc["data"][]=array(
            'warna'=>$row->warna,
            'dataproduct'=>$ambildata
          );
        //  print_r($datastc);
        }
       
        $data = $datastc;
      }else{
        $data=null;
      }

    }elseif($sts == "PS"){

      $this->db->select('A.warna,SUM(sisa) AS ttl');
      $this->db->from("{$this->stockpre} A");
      $this->db->like('A.kodepro', $kodepro);
      //$this->db->like('A.warna', $warna);
      $this->db->group_by("A.warna");
      $query = $this->db->get();
      if(!empty($query))
      {
        
        foreach($query->result() as $row){
         
          $ambildata=$this->getStockDetail($row->warna,$sts,$kodepro);
          $datastc["data"][]=array(
            'warna'=>$row->warna,
            'dataproduct'=>$ambildata
          );

        }
        
          $data = $datastc;
      }else{
        $data=null;
      }
    }
    
    return $data;
  }

  public function getStockDetail($warna,$sts,$kodepro){
    if($sts=="RS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisasls as sisa,"Roll",A.warna');
      $this->db->from("{$this->stock} A");
      $this->db->like('A.kodepro', $kodepro);
      $this->db->where('A.sisasls >', 0);
      $this->db->like('A.warna', $warna);
      // $this->db->group_by('A.warna','asc');
      $this->db->group_by('A.ukuran','desc');
    }elseif($sts=="PS"){
      $this->db->select('A.kodepro,A.sisa,A.unitqty,A.warna');
      $this->db->from("{$this->stockpre} A");
      $this->db->like('A.kodepro', $kodepro);
      $this->db->where('A.sisa >', 0);
      $this->db->like('A.warna', $warna);

    }
    $query = $this->db->get();
    return $query->result_array();
  }

  public function getstock_($sts,$gol,$kodepro){
    if($sts=="RS"){
      $this->db->select('A.kodepro,A.ukuran,A.unitqty,A.sisasls as sisa,"Roll",A.warna');
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