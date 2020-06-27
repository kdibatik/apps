<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MessageModel extends CI_Model
{
  public $user = 'user';
  public $msg = 'message';

  public function __construct()

  {
    parent::__construct();
    $this->load->database();
  }

  public function postMessage($username,$data){
    if($this->db->insert('message', $data)){
        return TRUE;
      }else{
        return FALSE;
      }
  }

  public function getMessage($username){
    $this->db->select('A.id,A.username,A.message,A.picurl,A.title,A.url,A.tglsave,A.stsread');
    $this->db->from("{$this->msg} A");
    $this->db->where('A.username', $username);
    $this->db->where('A.stsread', "0");
    $query = $this->db->get();
    return $query->result_array();
  }

}