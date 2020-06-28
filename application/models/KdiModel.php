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

  public function getTrackData($username,$dataso){
    $query = $this->db->query('
    SELECT noso,ref,DATE_FORMAT(tglsave,"%d %M %Y, %H:%i:%s") as tglso,
    (SELECT DATE_FORMAT(tglsave,"%d %M %Y, %H:%i:%s") FROM do_h WHERE noso="'.$dataso.'") AS tgldo,
    (SELECT DATE_FORMAT(tglsave,"%d %M %Y, %H:%i:%s") FROM inv_h WHERE noso="'.$dataso.'") AS tglinv,
    (SELECT DATE_FORMAT(tglsave,"%d %M %Y, %H:%i:%s") FROM lnscst_h INNER JOIN lnscst_d ON lnscst_h.nobyr=lnscst_d.nobyr WHERE lnscst_d.noinv=(SELECT noinv FROM inv_h WHERE noso="'.$dataso.'") LIMIT 1) AS tgllunas
    FROM so_h WHERE noso="'.$dataso.'"');
    return $query->result();
  }


}



// SELECT users.id, users.first_name, users.last_name, users.game_id
// FROM users
// WHERE users.id NOT IN
// (SELECT banned.users_id FROM banned) AND '.$col.' = '.$value.'
// ORDER BY '.$order_by.'