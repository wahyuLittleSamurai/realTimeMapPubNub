  <?php
  defined('BASEPATH') OR exit('No direct script access allowed');
 
  class M_account extends CI_Model{

       function daftar($data)
       {
            $this->db->insert('tblUser',$data);
       }
	   function readUser($data)
	   {
			$this->db->select('id');
			$this->db->from('tblUser');
			$this->db->where('email',$data['email']);
			$this->db->where('password',$data['password']);
			$query=$this->db->get();
			return $query->num_rows();
	   }
	   function codeChangePassword($data)
	   {
		   $this->db->set('kode', $data['kode']);
		   $this->db->where('email',$data['email']);
		   $this->db->update('tblUser');
	   }
	   function changePassword($data)
	   {
		   $this->db->set('password', $data['password']);
		   $this->db->where('kode',$data['kode']);
		   $this->db->update('tblUser');
		   
	   }
	   
	  
  }