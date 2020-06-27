<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KdiModel extends CI_Model
{
  public $user = 'user';


  public function __construct()

  {
    parent::__construct();
    $this->load->database();
  }

  public function getProfileData ($username){
    
    $this->db->select('A.username,A.laveluser,A.name,A.email,A.idgoogle,A.picture');
    $this->db->from("{$this->user} A");
    // $this->db->join("{$this->soh} B", 'A.username = B.sales','left');
    $this->db->where('A.email', $username);
    // $this->db->where('Month(B.tgl)', $month);
    $query = $this->db->get();
    return $query->result();
  }


  public function savepciture($username,$data){
    
    $this->db->where('email', $username);
    return $this->db->update($this->user,$data);

  }

 

  

  

}