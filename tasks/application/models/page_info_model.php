<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_info_model extends CI_Model {

	public function get_info($page){

	$this->db->where("page_name",$page);
	$qry = $this->db->get("pages");
	

	$res = $qry->result();

	return $res;
	}


}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/




