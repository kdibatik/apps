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
    $this->db->select('A.username,A.laveluser,A.name,A.email,A.idgoogle');
    $this->db->from("{$this->user} A");
    $this->db->where('username', $username);
      $query = $this->db->get();
    return $query->result();
  }
}