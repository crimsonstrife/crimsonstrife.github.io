<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilities_model extends CI_Model {

	public function random_gen($length){
				$characters = 'abcdefghjkmnopqrstuvwxyz0123456789';
				$random_string_length = $length;
				$random = '';

		 		for ($i = 0; $i < $random_string_length; $i++) {
		      		$random .= strtolower($characters[rand(0, strlen($characters) - 1)]);
		 		}
		 		return $random;
	}



	public function create_project_url($url){

		$project_url = $url;
		//lower case everything
	    $project_url = strtolower($project_url);
	    //make alphaunermic
	    $project_url = preg_replace("/[^a-z0-9_\s-]/", "", $project_url);
	    //Clean multiple dashes or whitespaces
	    $project_url = preg_replace("/[\s-]+/", " ", $project_url);
	    //Convert whitespaces and underscore to dash
	    $project_url = preg_replace("/[\s_]/", "-", $project_url);

	    $length = 8;
	    
	    $random = $this->random_gen($length);

	    $final_url = $project_url.$random;

	    return $final_url ;
	}



	public function random_color(){

		$color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

		return $color;

	}


	public function get_csha(){
		$csha = "<div style='padding-bottom:50px;' class='copyright'> &copy;".date('Y')." | <a target='_blank' href='http://codecanyon.net/user/shahnewaz-rifat/' >Powered By Taskfeed v1.4</a></div>";
		return $csha;
	}





	public function get_cshaex(){

			$cshaex = "&copy;".date('Y').", Powered By <a target='_blank' href='http://codecanyon.net/user/shahnewaz-rifat/' >Taskfeed v1.4 </a> | 
			<strong> <a href='".base_url('about-taskfeed')."'>About Taskfeed [PE]</a> | <a href='".base_url('t-o-s')."'>Terms Of Services</a> | 
			<a href='".base_url('privacy-policy')."'>Privacy Policy</a></strong>

			<div class='pull-right'>
	            <a target='_blank'  class='codecanyon tooltips' title='Taskfeed On CodeCanyon'  href='http://codecanyon.net/user/shahnewaz-rifat/'>C</a>
	        </div>";

	        return $cshaex;
	}


// NOTHING AFTER THIS LINE
}

/*		
Copying/Distributing any part of the code is strictly prohibited.
created by A.B.M Shahnewaz Rifat, 2013 
All Rights Reserved
*/

/* End of file utilities_model.php */
/* Location: ./application/models/utilities_model.php */