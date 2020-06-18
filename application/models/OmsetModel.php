<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OmsetModel extends CI_Model
{
  public $user = 'user';
  public $soh = 'so_h';
  public $cst = 'customer';
  

  public function __construct()

  {
    parent::__construct();
    $this->load->database();
  }

  public function getTotalOmset($username){
    $month = date('m');
    $this->db->select('COALESCE(SUM(B.grandtotal),0) as ttl');
    $this->db->from("{$this->user} A");
    $this->db->join("{$this->soh} B", 'A.username = B.sales','left');
    $this->db->where('A.email', $username);
    $this->db->where('Month(B.tgl)', $month);
    $query = $this->db->get();
    return $query->result();
  }

  public function getOmsetLimit ($username){
    $month = date('m');
    $this->db->select('A.noso,B.perusahaan,A.tgl,A.grandtotal,A.ref,A.stsapprove');
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
    $this->db->select('A.noso,B.perusahaan,A.tgl,A.grandtotal,A.ref,A.stsapprove');
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", 'A.cst = B.kodecst');
    $this->db->join("{$this->user} C", 'A.sales = C.username');
    $this->db->where('C.email', $username);
    $this->db->where('Month(A.tgl)', $month);
    $this->db->order_by("A.tgl", "DESC");
    $query = $this->db->get();
    return $query->result();
  }


  public function getOmsetYear ($username){
    $first_day_this_year = date('Y-01-01');
    $last_day_this_year = date('Y-12-31');
    $this->db->select("month(A.tgl) as bln,DATE_FORMAT(A.tgl,'%M') as bulan,sum(A.grandtotal) as ttl");
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->user} C", "A.sales = C.username");
    $this->db->where("C.email", $username);
    $this->db->where('A.tgl BETWEEN "'. date('Y-m-d', strtotime($first_day_this_year)). '" and "'. date('Y-m-d', strtotime($last_day_this_year)).'"');
    $this->db->group_by("DATE_FORMAT(A.tgl,'%M')","A.sales");
    $this->db->order_by("month(A.tgl)");
    $query = $this->db->get();
    return $query->result();
  }


  public function getOmsetMonth($username,$bln){
    $this->db->select("B.kodecst,B.perusahaan,SUM(A.grandtotal) AS ttl");
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", "A.cst = B.kodecst");
    $this->db->join("{$this->user} C", "A.sales = C.username");
    $this->db->where("month(A.tgl)", $bln);
    $this->db->where("C.email", $username);
    $this->db->group_by("B.perusahaan");
    $query = $this->db->get();
    return $query->result();
  }

  public function getOmsetCustomer($username,$bln,$cst){
    $this->db->select("B.perusahaan,A.noso,A.tgl,A.grandtotal,A.ref,A.stsapprove");
    $this->db->from("{$this->soh} A");
    $this->db->join("{$this->cst} B", "A.cst = B.kodecst");
    $this->db->join("{$this->user} C", "A.sales = C.username");
    $this->db->where("month(A.tgl)", $bln);
    $this->db->where("C.email", $username);
    $this->db->where("A.cst", $cst);  
    $this->db->order_by("A.tgl","DESC");
    $query = $this->db->get();
    return $query->result();
  }


  
}