<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class id extends CI_Controller
{	
	public function getId()
	{
		$query= "SELECT (MAX(" . $_POST['Id'] . ") + 1) AS 'id' FROM " . $_POST['tablename'];
		$response = array(
		'success' => TRUE,
		'id' => $this->db->query($query)->row()->id
		);
	}
}
?>