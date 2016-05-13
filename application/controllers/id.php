<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class id extends CI_Controller
{	
	public function getId()
	{
		$id;
		$query= "SELECT LPAD((MAX(" . $_POST['Id'] . ") + 1),10,'0') AS 'id' FROM " . $_POST['tablename'];
		if(!(is_null($this->db->query($query)->row()->id)))
		{
			$id = $this->db->query($query)->row()->id;
		}
		else
		{
			$id = "0000000001";
		}
		$data = array(
		'success' => TRUE,
		'id' => $id
		);
		
		echo json_encode($data);
	}
}
?>