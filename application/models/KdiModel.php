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
    $month = date('m');
    $this->db->select('A.username,A.laveluser,A.name,A.email,A.idgoogle,A.picture',((SELECT SUM(grandtotal) AS ttl FROM so_h WHERE sales=A.name AND MONTH(tgl)=$month) AS ttl))
    $this->db->from("{$this->user} A");
    $this->db->where('email', $username);
      $query = $this->db->get();
    return $query->result();
  }
}