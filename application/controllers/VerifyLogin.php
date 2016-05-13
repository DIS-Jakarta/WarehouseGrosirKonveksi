<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class VerifyLogin extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
   $this->load->helper('url');
	$this->load->helper('form');
 }
 
 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');
 
   $this->form_validation->set_rules('userid', 'Userid', 'trim|required|xss_clean');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
   
   if($this->check_database(MD5($this->input->post('password'))))
   {
	   
   }
	   
   redirect('Home', 'refresh');
 }
 
 function check_database($password)
 {
	
   //Field validation succeeded.  Validate against database
   $userid = $this->input->post('userid');
   $result = $this->user->login($userid, $password);
	
   if($result)
   {
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'userid' => $row->userid,
         'groupid' => $row->groupid,
         'full_name' => $row->full_name,
         'address' => $row->address,
         'phone_number' => $row->phone_number,
         'email_address' => $row->email_address,
		 'is_login' => $row->is_login,
		 'logged_in' => TRUE
       );
	   
	   //echo '<script type="text/javascript">alert("logged_in : ' . $sess_array['is_login'] . '")</script>';
	 
	   if($sess_array['is_login'] == 1)
	   {
	   echo '<script type="text/javascript">alert("User is currently login")</script>';
	   redirect('Home', 'refresh');
	   }
	   else
	   {
	  
       $this->session->set_userdata('logged_in', $sess_array);
	   
	   }
     }
   }
   else
   {
	   echo '<script type="text/javascript">alert("Invalid username or password")</script>';
	   redirect('Home', 'refresh');
   }
 }
 
}
?>